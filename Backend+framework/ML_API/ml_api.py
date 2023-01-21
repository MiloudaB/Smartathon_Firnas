# import keras
from tensorflow import keras
from flask_cors import CORS

# import keras_retinanet
from keras_retinanet import models
from keras_retinanet.utils.image import read_image_bgr, preprocess_image, resize_image
from keras_retinanet.utils.visualization import draw_box, draw_caption
from keras_retinanet.utils.colors import label_color
from keras_retinanet.utils.gpu import setup_gpu

# import miscellaneous modules
import matplotlib.pyplot as plt
import cv2
import os
import numpy as np
import time
from sklearn.preprocessing import StandardScaler

# import API stuff
from flask import Flask, request, jsonify
import base64

import numpy as np

app = Flask(__name__)

# use this to change which GPU to use
#gpu = 0

# set the modified tf session as backend in keras
#setup_gpu(gpu)

#######################
# adjust this to point to your downloaded/trained model
# models can be downloaded here: https://github.com/fizyr/keras-retinanet/releases
#model_path = os.path.join('..', 'snapshots', 'resnet50_coco_best_v2.1.0.h5')

@app.route("/predict", methods=["POST"])

def predict():
    # Get image from request
    image_b64 = request.json["image"]
    def base64_to_cv2(image_b64):
# Decode base64 to bytes
        image_bytes = base64.b64decode(image_b64)
# Convert bytes to numpy array
        image = np.frombuffer(image_bytes, np.uint8)

# Decode image
        image = cv2.imdecode(image, cv2.IMREAD_COLOR)

        return image
    image = base64_to_cv2(image_b64)
# load retinanet model
    model = models.load_model('/var/od/inference/model_v1.h5', backbone_name='resnet50')

# if the model is not converted to an inference model, use the line below
# see: https://github.com/fizyr/keras-retinanet#converting-a-training-model-to-inference-model
#model = models.convert_model(model)

    print(model.summary())

# load label to names mapping for visualization purposes
    labels_to_names = {0: 'GRAFFITI', 1: 'FADED_SIGNAGE', 2: 'POTHOLES', 3: 'GARBAGE', 4: 'CONSTRUCTION_ROAD', 5: 'BROKEN_SIGNAGE', 6: 'BAD_STREETLIGHT', 7: 'BAD_BILLBOARD', 8: 'SAND_ON_ROAD', 9: 'CLUTTER_SIDEWALK', 10: 'UNKEPT_FACADE', -1: 'BACKGROUND'}
# load image
#image = read_image_bgr('/var/od/images/bd3c64f33b05e592ae2b11a4f19a8b6a.jpg')

# copy to draw on
    draw = image.copy()
    draw = cv2.cvtColor(draw, cv2.COLOR_BGR2RGB)

# preprocess image for network
    image = preprocess_image(image)
    image, scale = resize_image(image)

# process image
    start = time.time()
    boxes, scores, labels = model.predict_on_batch(np.expand_dims(image, axis=0))
    print(labels)
    print("processing time: ", time.time() - start)

# correct for image scale
    #boxes /= scale
    
    #scaler = StandardScaler()
    #scaled_boxes = scaler.fit_transform(boxes)


 # Prepare response
    response = {
        "time": float(time.time() - start),
        "image": {
            "width": draw.shape[1],
            "height": draw.shape[0]
        },
        "predictions": []
    }
# Add predictions to response
    for box, score, label in zip(boxes[0], scores[0], labels[0]):
        # Scores are sorted so we can break
        if score < 0.2:
            break

        color = label_color(label)

        b = box.astype(int)
        print(b)
        draw_box(draw, b, color=color)

        caption = "{} {:.3f}".format(labels_to_names[label], score)
        draw_caption(draw, b, caption)

        # Append prediction to response
        response["predictions"].append({
            "x": str(b[0]),
            "y": str(b[1]),
            "width": str(b[2] - b[0]),
            "height": str(b[3] - b[1]),
            "confidence": str(score),
            "class": labels_to_names[label]
        })


# Encode image as base64
    _, buffer = cv2.imencode('.jpg', draw)
    image_b64 = base64.b64encode(buffer).decode()

# Add image to response
    response["image"]["b64"] = image_b64
    response = jsonify(response)
    # Set headers
    response.headers.add('Access-Control-Allow-Origin', '*')

    response.headers.add('Access-Control-Allow-Methods', 'GET,POST, OPTIONS')
    response.headers.add('Access-Control-Allow-Headers', 'Content-Type,Authorization')
    response.headers.add('Access-Control-Max-Age', '86400')
    response.headers['Referrer-Policy'] = 'no-referrer-when-downgrade'

    

    return response

    
if __name__ == "__main__":
    app.run(host='0.0.0.0',port=5000,debug=True)
    cors(app)




#old code for simple viz
# visualize detections
#for box, score, label in zip(boxes[0], scores[0], labels[0]):
    # scores are sorted so we can break
    #if score < 0.5:
    #    break
        
    #color = label_color(label)
    
  # b = box.astype(int)
    #draw_box(draw, b, color=color)
    
    #caption = "{} {:.3f}".format(labels_to_names[label], score)
   # draw_caption(draw, b, caption)
#cv2.imwrite('image_with_detections.jpg', draw)
