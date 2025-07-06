#!/bin/bash

# Load .env variables safely

echo "Running migrations and seeders..."

php artisan optimize
php artisan config:clear
php artisan migrate --force
php artisan db:seed --force
composer require predis/predis

echo "✅ Migrations and seeders completed successfully."
echo "✅ Setup completed!"
