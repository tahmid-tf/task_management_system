# Task Management Admin

Task Management Admin is a Laravel-based admin panel for managing users and laying the foundation for a Jira-style task management system. The current codebase focuses on authentication, admin navigation, role-based access, and a polished user management experience built with DataTables, SweetAlert2, and Spatie Permission.

## Overview

This project is built on Laravel 13 and includes:

- Laravel Breeze authentication
- Admin layout with active sidebar highlighting
- Spatie roles and permissions
- User CRUD workflow with image upload
- Status toggling for active and inactive users
- DataTables-powered responsive user listing
- SweetAlert2 confirmation and detail modals

## Features

- Authentication and protected admin routes
- User creation with:
  - name
  - email
  - phone
  - address
  - role
  - status
  - profile image
- User editing with optional password update
- Role assignment through Spatie Permission
- Active and inactive status toggling
- Responsive table listing with:
  - view details modal
  - edit action
  - status toggle action
- Profile image upload via Laravel storage

## Requirements

- PHP 8.3 or higher
- Composer
- Node.js and npm
- MySQL, SQLite, or another Laravel-supported database

## Installation

Clone the repository and install the dependencies:

```bash
composer install
npm install
```

Create your environment file and generate an application key:

```bash
php -r "file_exists('.env') || copy('.env.example', '.env');"
php artisan key:generate
```

Configure your database credentials in `.env`, then run the migrations and seeders:

```bash
php artisan migrate --seed
```

Create the storage symlink for uploaded user images:

```bash
php artisan storage:link
```

Build the frontend assets:

```bash
npm run build
```

## Running The App

Start the Laravel development server:

```bash
php artisan serve
```

If you are using Vite during development, run:

```bash
npm run dev
```

## Usage

After logging in as an admin user:

- Go to `/admin/view-users` to see the user table
- Use `Add User` to create a new user
- Click the edit button to update an existing user
- Click the status icon to switch a user between active and inactive
- Click the view icon to open a SweetAlert detail card

## Admin Routes

- `GET /admin/view-users` - user listing
- `GET /admin/add-user` - create user form
- `POST /admin/add-user` - create user submission
- `GET /admin/users/{user}/edit` - edit user form
- `PUT /admin/users/{user}` - update user submission
- `PATCH /admin/users/{user}/toggle-status` - toggle active/inactive status

## Tech Stack

- Laravel 13
- Laravel Breeze
- Spatie Laravel Permission
- Bootstrap admin template
- DataTables
- SweetAlert2
- Feather Icons
- jQuery

## Project Structure

- `app/Http/Controllers/Admin/UserController.php` - admin user management actions
- `resources/views/admin/users/` - add, edit, and view user pages
- `resources/views/components/sidebar/sidebar.blade.php` - active admin sidebar
- `routes/user_routes/admin.php` - admin user routes

## Notes

- User images are stored on the `public` disk and served through the storage symlink.
- The current codebase is focused on user administration and reusable UI patterns for the future task board system.
- Roles are managed through Spatie Permission, while user status is handled separately through the `status` column.

## License

This project is open-sourced under the MIT license.
