import pandas as pd

# Read the CSV files into DataFrames
df1 = pd.read_csv('downsampled_annotations2.csv')
df2 = pd.read_csv('downsampled_annotations1.csv')

# Compare the DataFrames
if df1.equals(df2):
    print("The two CSV files are the same.")
else:
    print("The two CSV files are different.")
