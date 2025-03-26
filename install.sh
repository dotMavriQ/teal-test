#!/bin/bash

# TEAL Installation Script
echo "Starting TEAL installation..."

# Step 1: Make sure the .env file exists
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env
    sed -i 's/APP_NAME=Laravel/APP_NAME=TEAL/g' .env
    sed -i 's/DB_CONNECTION=mysql/DB_CONNECTION=sqlite/g' .env
    # Get the absolute path to the database
    DB_PATH=$(pwd)/database/database.sqlite
    echo "DB_CONNECTION=sqlite" >> .env
    echo "DB_DATABASE=$DB_PATH" >> .env
fi

# Generate application key
echo "Generating application key..."
php artisan key:generate

# Step 2: Make sure the SQLite database exists
if [ ! -f database/database.sqlite ]; then
    echo "Creating SQLite database..."
    touch database/database.sqlite
fi

# Step 3: Run migrations
echo "Running database migrations..."
php artisan migrate:fresh

# Step 4: Seed the database
echo "Seeding the database with initial data..."
php artisan db:seed

# Step 5: Create storage link
echo "Creating storage link..."
php artisan storage:link

# Step 6: Migrate data from old JSON files to new database
echo "Migrating data from JSON files to the database..."
php artisan migrate:file-data

# Final message
echo ""
echo "TEAL installation completed!"
echo "You can now start the application with: php artisan serve"
echo ""
echo "Default admin credentials:"
echo "Email: dotmavriq@dotmavriq.life"
echo "Password: TEALAdmin@2025#Secure"
echo ""