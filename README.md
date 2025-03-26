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
- File-based data storage
- Custom authentication system

## Installation

1. Clone the repository
2. Run `composer install`
3. Copy `.env.example` to `.env`
4. Run `php artisan key:generate`
5. Create storage link with `php artisan storage:link`
6. Set up the admin user with `php artisan setup:users`
7. Start the server with `php artisan serve`

## Usage

### Importing Books

1. Export your Goodreads library as CSV
2. Convert to JSON format
3. Use the Import Books page to upload the JSON file

## License

This application is licensed under the MIT license.