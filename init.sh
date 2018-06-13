#!/bin/bash

# Create persist volume for mysql
docker volume create --name=mysql-data

# Create persist volume for elasticsearch
docker volume create --name esdata

# Set current working directory
cd "$(dirname "$0")"
