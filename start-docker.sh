#!/bin/bash

# Check if .env file exists
if [ ! -f .env ]; then
    echo "Error: .env file not found"
    exit 1
fi

# Read .env file and convert it to --env-file format
echo "Loading environment variables from .env file..."

# Start the Docker container with environment variables from .env
docker run -d \
    --env-file .env \
    -p 80:80 \
    bagisto:latest

echo "Container started with environment variables from .env file" 