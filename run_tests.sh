#!/bin/bash

# TEAL Test Runner
echo "Running TEAL tests..."

# Get database info from .env
DB_USERNAME=$(grep DB_USERNAME .env | cut -d '=' -f2)
DB_PASSWORD=$(grep DB_PASSWORD .env | cut -d '=' -f2)
DB_HOST=$(grep DB_HOST .env | cut -d '=' -f2)
DB_PORT=$(grep DB_PORT .env | cut -d '=' -f2)

# Make sure the testing database exists
echo "Setting up PostgreSQL test database..."
PGPASSWORD=$DB_PASSWORD psql -h $DB_HOST -p $DB_PORT -U $DB_USERNAME -c "DROP DATABASE IF EXISTS teal_testing;"
PGPASSWORD=$DB_PASSWORD psql -h $DB_HOST -p $DB_PORT -U $DB_USERNAME -c "CREATE DATABASE teal_testing;"

# Configure testing environment
export DB_CONNECTION=pgsql
export DB_DATABASE=teal_testing

# Run the tests with coverage report
php artisan test --coverage

echo ""
echo "Tests completed!"
echo ""