# Task Management Admin

Task Management Admin is a Laravel 13 application for structured admin operations, user management, task tracking, delayed-task email reminders, and exportable reporting. It combines a polished admin layout with role-based access control, responsive DataTables, SweetAlert2 interactions, SortableJS task ordering, modal-based task creation, admin-only soft delete support, and XLSX exports.

## Overview

The application currently focuses on four production-ready areas:

- Admin authentication and role-based access
- Admin user management
- Task management with a Kanban board, table view, archive, and categories
- Mail center tools for delayed-task reminders and custom emails

The interface is organized around a persistent admin layout with active route highlighting and Spatie Laravel Permission for access control.

## Implemented Features

### Authentication and Access

- Laravel Breeze authentication
- Verified admin routes
- Spatie roles and permissions
- Roles currently in use:
  - Admin
  - Team Member
  - Viewer

### User Management

- Create users
- Edit users
- View users in a responsive DataTable
- Toggle active and inactive status
- Upload profile images
- Store phone number and address
- Assign roles during create and edit

Inactive users are excluded from assignee selection and from the mail center user lists.

### Task Management

- Kanban board organized by task category
- Horizontal category tabs for quick switching
- Board columns:
  - Backlog
  - To Do
  - In Progress
  - Done
- Create Task modal from the board and table views
- Drag and drop ordering with SortableJS
- Task creation and editing
- Task detail modal
- Comments and activity history
- File attachments
- Task duplication
- Archive and restore tasks
- Admin-only task deletion with soft deletes
- Table view with DataTables
- Status updates from the table view
- Assigned by and assignee information

### Mail Center

The admin sidebar includes a dedicated `Send Mail` section for task-delay communication.

The sidebar also includes a `Mail System` toggle that controls whether automatic task-assignment emails are sent to the assignee when a task is created or duplicated.

It supports:

- Automated delayed-task mail to all active users
- Automated delayed-task mail to one active user
- Custom mail to one active user
- Automatic assignee mail when the mail system is turned on

The mail center only targets active users, and delayed-task reminders are based on overdue, incomplete tasks. Task emails use a polished summary layout with the full description shown in a wrapped content block so long text stays readable.

### Export System

The export module uses `maatwebsite/excel` and generates `.xlsx` files.

Supported export modes:

- Export all tasks
- Export tasks by date range
- Export tasks for a selected user
- Export all `To Do` tasks
- Export all `In Progress` tasks
- Export all `Done` tasks

Exported spreadsheets include:

- Auto-sized columns
- Styled header rows
- Colored status cells
- Colored priority cells

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

Configure your database and mail settings in `.env`, then run migrations and seeders:

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
- Create tasks from the modal on the board or table view
- Use the table view for a searchable, responsive list of tasks
- Open archived tasks to restore previously archived items
- Delete tasks as an admin when a task should be moved to soft delete
- Open Export Tasks to download XLSX reports
- Open Send Mail to notify active users about delayed work or send a custom email

## Routes

Common admin routes currently in use:

- `GET /dashboard`
- `GET /admin/view-users`
- `GET /admin/add-user`
- `POST /admin/add-user`
- `GET /admin/tasks`
- `GET /admin/tasks/table`
- `GET /admin/tasks/archived`
- `GET /admin/tasks/create`
- `DELETE /admin/tasks/{task}`
- `GET /admin/tasks/export`
- `GET /admin/tasks/export/download`
- `GET /admin/task-categories`
- `GET /admin/mail-center`
- `POST /admin/mail-center/delayed-all`
- `POST /admin/mail-center/delayed-user`
- `POST /admin/mail-center/custom`
- `GET /admin/mail-system`
- `PATCH /admin/mail-system`

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
- `app/Http/Controllers/Admin/TaskController.php` - task board, task CRUD, archive, comments, attachments, and ordering
- `app/Http/Controllers/Admin/TaskCategoryController.php` - task category management
- `app/Http/Controllers/Admin/TaskExportController.php` - Excel export entry points
- `app/Http/Controllers/Admin/MailCenterController.php` - delayed-task reminders and custom email sending
- `app/Exports/TasksExport.php` - reusable XLSX export query and formatting logic
- `resources/views/admin/users/` - user management views
- `resources/views/admin/tasks/` - task board, table, archive, category, and export views
- `resources/views/admin/mail-center/` - mail center dashboard and forms
- `resources/views/components/sidebar/sidebar.blade.php` - admin navigation
- `routes/user_routes/admin.php` - admin route definitions

## Notes

- Task uploads and user profile images are stored on the public disk.
- The board uses category tabs as the main navigation model for tasks.
- Statuses are intentionally kept simple: Backlog, To Do, In Progress, and Done.
- The export module is configured for XLSX output, not CSV.
- The mail center only targets active users.
- The mail system toggle controls assignment emails for newly created or duplicated tasks.
- Task deletion is soft delete based, so deleted tasks remain in the database unless permanently removed later.

## License

This project is open-sourced under the MIT license.
