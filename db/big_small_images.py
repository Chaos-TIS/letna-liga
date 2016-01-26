#!/usr/bin/env python
# -*- coding: utf-8 -*-
import os, sys, time
from os import path, listdir
from os.path import join
from PIL import Image

MAX_SIZE = 250
        
solutions_attachments_folder = "attachments/solutions/"
solutions_ids = [folder for folder in listdir(solutions_attachments_folder)
                 if not path.isfile(join(solutions_attachments_folder, folder))]

for sid in solutions_ids:
    images_path = join(solutions_attachments_folder, sid, "images/")
    if path.exists(images_path):
        
        for folder in ("big/", "small/"):
            if not path.exists(join(images_path, folder)):
                os.mkdir(join(images_path, folder))
                
        images = [file for file in listdir(images_path) if path.isfile(join(images_path, file))]
        for img in images:
            thumbnail = join(images_path, "small/", img)
            old_image = join(images_path, img)
            new_image = join(images_path, "big/", img)
            os.rename(old_image, new_image)
            with Image.open(new_image) as img_file:
                factor = MAX_SIZE/max(img_file.size);
                new_size = tuple(map(lambda x: round(factor*x), img_file.size))
                img_file.thumbnail(new_size, Image.ANTIALIAS)
                img_file.save(thumbnail, "JPEG")
