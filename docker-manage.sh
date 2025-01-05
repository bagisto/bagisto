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
        -p 8080:8080 \
        --env-file .env \
        bagisto-app

    echo "Waiting for container to be ready..."
    sleep 10

    echo "Running setup steps..."
    docker exec bagisto-app php artisan optimize:clear
    docker exec bagisto-app php artisan config:cache
    docker exec bagisto-app php artisan route:cache
    docker exec bagisto-app php artisan view:cache

    echo "Application is ready at http://localhost:8080"
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
    "logs")
        docker logs -f bagisto-app
        ;;
    "shell")
        docker exec -it bagisto-app bash
        ;;
    *)
        echo "Usage: $0 {start|stop|restart|logs|shell}"
        exit 1
        ;;
esac 