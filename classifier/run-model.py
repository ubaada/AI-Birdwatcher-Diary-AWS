import sys
import os

# Image / Matrix processing libraries
from PIL import Image
import numpy as np

# ML lib to run the model
import tflite_runtime.interpreter as tflite


# Load Model
model_path = os.path.join(os.path.dirname(__file__), "bird-model.tflite")
interpreter = tflite.Interpreter(model_path= str(model_path))
interpreter.allocate_tensors()

# Load labels (bird names)
labels = []
label_path = os.path.join(os.path.dirname(__file__), "labels.txt")
lines = open(str(label_path), "r").read().split("\n")
for l in lines:
    name_pair = l.split(",")
    labels.append(name_pair)
    
# Get input and output tensors.
input_details = interpreter.get_input_details()
output_details = interpreter.get_output_details()

# Recognises the given image of a bird and outputs its name.
def classify_image(img):
    img_small = img.resize((224, 224), Image.ANTIALIAS)
    img_arr = np.array(img_small)
    norm_x = img_arr / 1
    coll_x = norm_x[np.newaxis,...] # add an additional dimension
    final_x= np.uint8(coll_x)

    # Set input
    interpreter.set_tensor(input_details[0]['index'], final_x)

    # Predict bird
    interpreter.invoke()

    # Get predicton, remove extra dimension and normalize between [0,1]
    output = np.squeeze(interpreter.get_tensor(output_details[0]['index'])) / 255

    # Get bird class with highest probability
    max_ind = np.argmax(output)
    prediction = labels[max_ind]
    prediction.append(output[max_ind]) # add prediction probability
    return labels[max_ind]

def classify_file(file_address):
    # Load image
    img = Image.open(file_address)
    return classify_image(img)
    
if __name__ == "__main__":
    # if a file is passed as arg then classify it and quit
    # ELSE start classifying server
    if len(sys.argv) > 1:
        # Classify given file and quit
        command = sys.argv[1]
        print(classify_file(command))
    else:
        print("Starting AI classifier server ...")
