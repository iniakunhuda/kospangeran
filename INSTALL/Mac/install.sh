#!/bin/bash
echo "Starting Docker Compose..."
docker-compose up -d

echo "Opening http://localhost:8000..."
open "http://localhost:8000"
