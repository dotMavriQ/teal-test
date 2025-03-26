# TEAL

TEAL is a Laravel-based application for tracking and managing reading collections. The application provides tools for importing data from Goodreads, organizing books, and tracking reading progress.

## Core Features

- Book collection management with CRUD operations
- Import functionality for Goodreads data
- Custom cover image support
- Reading status tracking
- Clean URL structure with slugs
- Dark-themed UI based on Gruvbox color palette
- Responsive design for desktop and mobile

## Technology

- Laravel 10
- Bootstrap 5 with custom theming
- SQLite database
- Eloquent ORM
- Laravel's built-in authentication

## Installation

1. Clone the repository
2. Run the installation script: `./install.sh`
3. Start the server with `php artisan serve`

The installation script will:
- Create the necessary .env file
- Set up the SQLite database
- Run migrations and seed the database
- Create a storage link for file uploads
- Migrate any existing file-based data to the database

## Usage

### Importing Books

1. Export your Goodreads library as CSV
2. Convert to JSON format
3. Use the Import Books page to upload the JSON file

### Default Login

After installation, you can log in with:
- Email: dotmavriq@dotmavriq.life
- Password: TEALAdmin@2025#Secure

## Testing

The application includes comprehensive tests for all features. To run the tests:

```bash
./run_tests.sh
```

This will run all tests using an in-memory SQLite database and generate a coverage report.

## License

This application is licensed under the MIT license.