#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Sun Jan  1 06:23:15 2023

@author: manianis
"""
from PIL import Image

# Open the image file
image = Image.open('koala.jpeg')
image = image.quantize(colors=9)
image.save('koala.png')
image = Image.open('koala.png')
image = image.convert('RGB')

# Get the width and height of the image
width, height = image.size

# Calculate the pixel size
pixel_size = 18

# Create a new image with the pixelated size
new_width, new_height = width // pixel_size, height // pixel_size
new_image = Image.new('RGB', (new_width, new_height))

colors = []
pixels = []


# Iterate over the image pixels
for py in range(new_height):
    for px in range(new_width):
        x, y = px * pixel_size, py * pixel_size
        # Calculate the average color of the pixel block
        r, g, b = 0, 0, 0
        count = 0
        try:
            p = image.getpixel((x + pixel_size // 2, y + pixel_size // 2))
            r += p[0]
            g += p[1]
            b += p[2]
            count += 1
        except:
            pass
        r //= count
        g //= count
        b //= count
        
        color = (r, g, b)
        if color not in colors:
            colors.append(color)
        index = colors.index(color)

        pixels.append(index)
        # Set the pixel block color to the new image
        new_image.putpixel((x // pixel_size, y // pixel_size), (r, g, b))
        

# Save the new image
new_image.save('pixelated2.png')

print(pixels)
for idx, color in enumerate(colors):
    print(f".color{idx} {{ background-color: rgb{color}; color: rgb{color}; }}")


# new_colors = []
# same_colors = [[0,3,5,7,8,9,11,12,16,17,19,20,21,22,23,27,29,42,38,40,32,44,46,54,55,31,33,35,49],[30,1,2,10,13,14,24,25,26],[4,6,15,18,28,34,36,39,41],[37,43,52,53],[45,47,48,50,51,56,57,58]]
# color_dict = {}
# for idxCol, cols in enumerate(same_colors):
#     r, g, b = 0, 0, 0
#     for col in cols:
#         color_dict[col] = idxCol
#         r += colors[col][0]
#         g += colors[col][1]
#         b += colors[col][2]
#     new_colors.append((r // len(cols), g // len(cols), b // len(cols)))
    
# for idx in range(len(pixels)):
#     pixels[idx] = color_dict[pixels[idx]]
    
# print(pixels)
# for idx, color in enumerate(new_colors):
#     print(f".color{idx} {{ background-color: rgb{color}; color: rgb{color}; }}")
