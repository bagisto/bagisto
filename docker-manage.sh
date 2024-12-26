#!/bin/bash

# Function to stop and remove existing container
cleanup_container() {
    echo "Stopping existing container if running..."
    docker stop bagisto-app 2>/dev/null || true
    echo "Removing container..."
    docker rm bagisto-app 2>/dev/null || true
}

# Function to start container
start_container() {
    echo "Building Docker image..."
    docker build -t bagisto-app .

    echo "Starting container..."
    docker run -d \
        --name bagisto-app \
        -p 80:80 \
        --env-file .env \
        bagisto-app

    echo "Waiting for container to be ready..."
    sleep 5

    echo "Running database setup..."


    echo "Application is ready at http://localhost"
}

case "$1" in
    "start")
        cleanup_container
        start_container
        ;;
    "stop")
        cleanup_container
        ;;
    "restart")
        cleanup_container
        start_container
        ;;
    *)
        echo "Usage: $0 {start|stop|restart}"
        exit 1
        ;;
esac 