#!/bin/bash

# Start Laravel server in background
php artisan serve --host=0.0.0.0 --port=8000 &

# Give Laravel a few seconds to start
sleep 3

# Start ngrok tunnel
ngrok http 8000

