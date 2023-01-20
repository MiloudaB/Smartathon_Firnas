import pandas as pd

# Read in the dataset
df = pd.read_csv("train.csv")

# Drop the 'class' column
df = df.drop(columns=['class'])

# Reorder the columns
df = df[["image_path","xmin","ymin","xmax","ymax","name"]]

df.rename(columns = {"image_path":"image_path", "xmin":"x1","ymin":"y1","xmax":"x2","ymax":"y2","name":"class_name"},inplace = True)

# Add 'images/' before the path of each picture
df['image_path'] = 'images//' + df['image_path']

# Multiply xmax, xmin, ymax, and ymin by 2
df[['x2','x1','y2','y1']] = df[['x2','x1','y2','y1']]*2


# Fix invalid boxes
invalid_boxes_x = df['x2'] < df['x1']
invalid_boxes_y = df['y2'] < df['y1']

if invalid_boxes_x.any():
    df.loc[invalid_boxes_x, ['x2','x1']] = df[['x1','x2']].values

if invalid_boxes_y.any():
    df.loc[invalid_boxes_y, ['y2','y1']] = df[['y1','y2']].values


# Fix invalid bounding box for image that go beyond the image width and height
df.x1=df.x1.clip(0,1920)
df.y1=df.y1.clip(0,1080)
df.x2=df.x2.clip(0,1920)
df.y2=df.y2.clip(0,1080)

# Convert columns 'x1', 'x2', 'y1', and 'y2' to base 10 integers
df[['x1', 'x2', 'y1', 'y2']] = df[['x1', 'x2', 'y1', 'y2']].apply(lambda x: x.astype(int))

# Write the modified dataset to a new CSV file
df.to_csv("modified_train.csv", index=False)
