import csv
import os
import shutil

# CSV file containing image filenames
csv_file = 'test.csv'

# Origin directory containing the images
origin_dir = 'images/'

# Destination directory to copy the images to
destination_dir = 'test/'

# Create the destination directory if it doesn't exist
if not os.path.exists(destination_dir):
    os.mkdir(destination_dir)

# Read the CSV file
with open(csv_file, 'r') as f:
    reader = csv.reader(f)
    # Skip the header row
    next(reader)
    # Iterate over the rows in the CSV file
    i = 0
    for row in reader:
        image_filename = row[0]
        # Construct the path to the image in the origin directory
        origin_path = os.path.join(origin_dir, image_filename)
        # Construct the path to the image in the destination directory
        destination_path = os.path.join(destination_dir, image_filename)
        # Copy the image from the origin directory to the destination directory
        shutil.copy2(origin_path, destination_path)
        i = i + 1
        print(f'Copied {image_filename} from {origin_path} to {destination_path}')
print("number of records",i)