#!/bin/bash

# TEAL Test Runner
echo "Running TEAL tests..."

# Make sure the testing database exists
touch database/testing.sqlite

# Configure testing environment
export DB_CONNECTION=sqlite
export DB_DATABASE=$(pwd)/database/testing.sqlite

# Run the tests with coverage report
php artisan test --coverage

echo ""
echo "Tests completed!"
echo ""