# Task Management Admin

Task Management Admin is a Laravel 13 application for structured admin operations, user management, and task tracking. It combines a polished SB Admin style interface with role-based access control, responsive data tables, SweetAlert2 interactions, and Excel exports for task reporting.

## Overview

The application currently includes two main areas:

- Admin user management
- Task management with a Kanban board, table view, archived tasks, categories, and XLSX exports

The UI is organized around a persistent admin layout with sidebar navigation, active route highlighting, and role-aware access using Spatie Laravel Permission.

## Key Features

- Authentication with Laravel Breeze
- Role-based access control with Spatie Permission
- Admin sidebar with active route highlighting
- Responsive user management workflow
- Task board with category tabs
- Drag and drop task ordering with SortableJS
- Task table with DataTables and responsive behavior
- Archived task view with restore support
- SweetAlert2 confirmations and detail modals
- XLSX export system for task reports

## Roles

The application currently uses these roles:

- Admin
- Team Member
- Viewer

Access is restricted by role so that sensitive task actions remain available only to authorized users.

## User Management

The admin user module supports:

- Create user
- Edit user
- View user list
- Toggle active and inactive status
- Upload profile image
- Assign Spatie roles
- Store phone number and address

Inactive users are excluded from new task assignee selections.

## Task Management

The task module supports:

- Task board grouped by category
- Horizontal category tabs
- Board columns: Backlog, To Do, In Progress, Done
- Task creation and editing
- Quick create from the board
- Drag and drop ordering between board columns
- Task detail modal
- Comments
- Attachments
- Activity history
- Task duplication
- Task archive and restore
- Assigned by and assignee display
- Status changes from the table view

## Export System

The export module uses `maatwebsite/excel` and downloads `.xlsx` files.

Supported export modes:

- Export all tasks
- Export tasks by date range
- Export tasks for a selected user
- Export all `To Do` tasks
- Export all `In Progress` tasks
- Export all `Done` tasks

Exported spreadsheets include:

- Auto-sized columns
- Styled header row
- Background-colored status cells
- Background-colored priority cells

## Requirements

- PHP 8.3 or higher
- Composer
- Node.js and npm
- MySQL, SQLite, or another Laravel-supported database
- PHP `zip` extension enabled for XLSX export support

## Installation

Install dependencies:

```bash
composer install
npm install
```

Create the environment file and generate an application key:

```bash
php -r "file_exists('.env') || copy('.env.example', '.env');"
php artisan key:generate
```

Configure your database connection in `.env`, then run migrations and seeders:

```bash
php artisan migrate --seed
```

Create the storage symlink for uploaded images and task attachments:

```bash
php artisan storage:link
```

Build the frontend assets:

```bash
npm run build
```

## Development

Run the application locally:

```bash
php artisan serve
```

In another terminal, run Vite for frontend assets:

```bash
npm run dev
```

If you prefer the combined project script, you can also use:

```bash
composer run dev
```

## Usage

After signing in with an authorized account:

- Open the Users section to manage admin users
- Open the Tasks section to view the Kanban board
- Use category tabs to switch between task groups
- Use the table view for a searchable, responsive list of tasks
- Open archived tasks to restore previously archived items
- Open Export Tasks to download XLSX reports

## Routes

Common admin routes currently in use:

- `GET /admin/view-users`
- `GET /admin/add-user`
- `POST /admin/add-user`
- `GET /admin/tasks`
- `GET /admin/tasks/table`
- `GET /admin/tasks/archived`
- `GET /admin/tasks/export`
- `GET /admin/tasks/export/download`
- `GET /admin/task-categories`

## Tech Stack

- Laravel 13
- Laravel Breeze
- Spatie Laravel Permission
- Maatwebsite Laravel Excel
- Bootstrap-based admin layout
- DataTables
- SweetAlert2
- SortableJS
- jQuery
- Feather Icons

## Project Structure

- `app/Http/Controllers/Admin/UserController.php` - admin user CRUD and status control
- `app/Http/Controllers/Admin/TaskController.php` - task board, task CRUD, archive, comments, and attachments
- `app/Http/Controllers/Admin/TaskCategoryController.php` - task category management
- `app/Http/Controllers/Admin/TaskExportController.php` - Excel export entry points
- `app/Exports/TasksExport.php` - reusable XLSX export query and formatting logic
- `resources/views/admin/users/` - user management views
- `resources/views/admin/tasks/` - task board, table, archive, category, and export views
- `resources/views/components/sidebar/sidebar.blade.php` - admin navigation
- `routes/user_routes/admin.php` - admin route definitions

## Notes

- Task uploads and user profile images are stored on the public disk.
- The board uses category tabs as the main navigation model for tasks.
- Statuses are intentionally kept simple: Backlog, To Do, In Progress, and Done.
- The export module is configured for XLSX output, not CSV.

## License

This project is open-sourced under the MIT license.
