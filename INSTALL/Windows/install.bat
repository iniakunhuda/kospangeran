@echo off
echo Starting Docker Compose...
docker-compose up -d

echo Opening http://localhost:8000...
start "" "http://localhost:8000"
pause
