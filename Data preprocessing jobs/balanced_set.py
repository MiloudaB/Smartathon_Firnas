import pandas as pd
from sklearn.utils import resample
import random

# Read in your data
data = pd.read_csv("modified_train.csv")

# Find the minority class label and number of samples
minority_label = data.label.value_counts().idxmin()
minority_class = data[data.label == minority_label]
minority_class_size = len(minority_class)

downsampled_data_all = pd.DataFrame()
for i in range(50):
    # Downsample all classes to the minority class size
    downsampled_data = pd.DataFrame()
    for label in data.label.unique():
        if label != minority_label:
            majority_class = data[data.label == label]
            downsampled_majority = resample(majority_class, 
                                            replace=False,    # sample without replacement
                                            n_samples=minority_class_size,    # match minority class
                                            random_state=random.randint(0,100))  # random seed results
            downsampled_data = pd.concat([downsampled_data, downsampled_majority])
        print("dataset generated n°",i)

    # Combine the downsampled data with the minority class
    downsampled_data = pd.concat([downsampled_data, minority_class])
    downsampled_data_all = pd.concat([downsampled_data_all, downsampled_data])

# Display new class counts
downsampled_data_all.label.value_counts()

# Write the downsampled data back to a CSV file
downsampled_data_all.to_csv('balanced_train.csv', index=False)
