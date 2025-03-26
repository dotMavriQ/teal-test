#!/bin/bash

# TEAL Installation Script
echo "Starting TEAL installation..."

# Step 1: Make sure the .env file exists
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env
    sed -i 's/APP_NAME=Laravel/APP_NAME=TEAL/g' .env
fi

# Generate application key
echo "Generating application key..."
php artisan key:generate

# Step 2: Check PostgreSQL connection
echo "Checking PostgreSQL connection..."
echo "NOTE: Make sure PostgreSQL is installed and running with the credentials in .env"

# Get database info from .env
DB_DATABASE=$(grep DB_DATABASE .env | cut -d '=' -f2)
DB_USERNAME=$(grep DB_USERNAME .env | cut -d '=' -f2)
DB_PASSWORD=$(grep DB_PASSWORD .env | cut -d '=' -f2)
DB_HOST=$(grep DB_HOST .env | cut -d '=' -f2)
DB_PORT=$(grep DB_PORT .env | cut -d '=' -f2)

# Check if psql is installed
if ! command -v psql &> /dev/null; then
    echo "WARNING: PostgreSQL client (psql) not found. Please install PostgreSQL."
    echo "You can continue, but the database setup may fail."
    read -p "Press enter to continue or Ctrl+C to cancel..."
else
    # Check if database exists, create if not
    if ! PGPASSWORD=$DB_PASSWORD psql -h $DB_HOST -p $DB_PORT -U $DB_USERNAME -lqt | cut -d \| -f 1 | grep -qw $DB_DATABASE; then
        echo "Creating PostgreSQL database: $DB_DATABASE..."
        PGPASSWORD=$DB_PASSWORD psql -h $DB_HOST -p $DB_PORT -U $DB_USERNAME -c "CREATE DATABASE $DB_DATABASE;"
        if [ $? -eq 0 ]; then
            echo "Database created successfully!"
        else
            echo "WARNING: Failed to create database. Please check your PostgreSQL installation and credentials."
            echo "You may need to create the database manually."
        fi
    else
        echo "Database $DB_DATABASE already exists, continuing..."
    fi
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