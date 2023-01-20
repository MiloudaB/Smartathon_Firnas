import pandas as pd

# Read the CSV file into a Pandas DataFrame
df = pd.read_csv('modified_train.csv')

# Get a list of all the unique labels in the DataFrame
labels = df['label'].unique()

# Iterate over the labels and create a separate DataFrame for each label
for label in labels:
    label_df = df[df['label'] == label]
    # Save the label's DataFrame to a CSV file
    label_df.to_csv(f'{label}.csv', index=False)
    print('{label} done')
