#!/bin/bash

# Check if .env file exists
if [ ! -f .env ]; then
    echo "Creating .env file from .env.example..."
    cp .env.example .env
    echo "Please edit .env file with your specific configuration"
    exit 1
fi

# Check if container already exists
if [ "$(docker ps -q -f name=bagisto)" ]; then
    echo "Stopping running container..."
    docker stop bagisto
fi

if [ "$(docker ps -aq -f status=exited -f name=bagisto)" ]; then
    echo "Removing existing container..."
    docker rm bagisto
fi

# Build the Docker image
echo "Building Docker image..."
docker build -t bagisto:latest .

# Start the container with environment variables from .env
echo "Starting container..."
docker run -d \
    --env-file .env \
    -p 80:80 \
    --name bagisto \
    bagisto:latest

echo "Container started! The application should be available at http://localhost"
echo "Admin Panel: http://localhost/admin"
echo "Default Admin Credentials:"
echo "  Email: admin@example.com"
echo "  Password: admin123"
echo "You can view the logs using: docker logs -f bagisto" 