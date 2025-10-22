#!/bin/bash

# Start Laravel server in background
php artisan serve --host=0.0.0.0 --port=8000 &

# Wait a moment for Laravel to start
sleep 2

# Start ngrok tunnel
ngrok http 8000

