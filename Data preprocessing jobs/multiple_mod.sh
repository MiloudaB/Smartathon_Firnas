#!/bin/bash

screen python3 train.py csv /var/od/GARBAGE.csv  /var/od/class_names --snapshot  /snapshot/GARBAGE &
screen python3 train.py csv /var/od/BAD_BILLBOARD.csv  /var/od/class_names --snapshot  /snapshot/BAD_BILLBOARD &
screen python3 train.py csv /var/od/SAND_ON_ROAD.csv  /var/od/class_names --snapshot  /snapshot/SAND_ON_ROAD &
screen python3 train.py csv /var/od/GRAFFITI.csv  /var/od/class_names --snapshot  /snapshot/GRAFFITI &
screen python3 train.py csv /var/od/POTHOLES.csv  /var/od/class_names --snapshot  /snapshot/POTHOLES &
screen python3 train.py csv /var/od/CLUTTER_SIDEWALK.csv  /var/od/class_names --snapshot  /snapshot/CLUTTER_SIDEWALK &
screen python3 train.py csv /var/od/CONSTRUCTION_ROAD.csv  /var/od/class_names --snapshot  /snapshot/CONSTRUCTION_ROAD &
screen python3 train.py csv /var/od/BROKEN_SIGNAGE.csv  /var/od/class_names --snapshot  /snapshot/BROKEN_SIGNAGE &
screen python3 train.py csv /var/od/UNKEPT_FACADE.csv  /var/od/class_names --snapshot  /snapshot/UNKEPT_FACADE &
screen python3 train.py csv /var/od/FADED_SIGNAGE.csv  /var/od/class_names --snapshot  /snapshot/FADED_SIGNAGE &
screen python3 train.py csv /var/od/BAD_STREETLIGHT.csv  /var/od/class_names --snapshot  /snapshot/BAD_STREETLIGHT &
done



