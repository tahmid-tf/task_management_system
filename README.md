# Task Management Admin

Task Management Admin is a polished Laravel 13 workspace for managing users, tasks, roles, mail workflows, exports, and reporting from a single admin panel. The application is built for day-to-day operations and focuses on a clean user experience, role-based access, and fast AJAX-driven interactions.

This README reflects the current codebase only.

## Overview

The system combines:

- Breeze authentication
- Spatie role and permission control
- Admin navigation with active route highlighting
- User management with image upload and status control
- Task board, table, archive, export, and calendar views
- Mail workflows for delayed-task notifications and custom messages
- XLSX exports through Maatwebsite Excel
- A built-in user manual for all roles

## Roles

The application currently supports these roles:

- Admin
- Team Member
- Viewer

## Core Features

### Authentication and Access Control

- Laravel Breeze authentication
- Verified route protection
- Active account middleware for blocked users
- Logout confirmation in the admin topbar
- Sidebar access tailored by role

### User Management

- Create users with AJAX submission
- Edit users in a modal workflow
- View users in a responsive DataTable
- Toggle user status between active and inactive
- Prevent the logged-in admin from disabling their own account
- Upload and preview profile images
- Store phone number and address information
- Assign roles during create and edit
- Hide inactive users from assignee selection
- Send status-change notifications by email

### Task Management

- Kanban board with category tabs
- Columns for Backlog, To Do, In Progress, and Done
- Drag and drop ordering with SortableJS
- Modal-based task creation
- Modal-based task editing from the table view
- Task detail modal
- Comments and activity history
- Attachments
- Archive and restore flow
- Soft delete support for admin task deletion
- Assigned by and assignee visibility
- Status updates from the table with AJAX
- Team Member role can only manage their assigned tasks

### Calendar View

- Admin calendar for all tasks
- Team Member calendar scoped to their own tasks
- Hover summaries for task counts by status
- Traffic-light style date intensity based on workload and overdue pressure

### Mail System

The admin panel includes a mail area for operational communication.

Available workflows:

- Send delayed task reminders to all active users
- Send delayed task reminders to a selected active user
- Send a custom mail to a selected active user
- Automatically email the assignee when a task is created or duplicated
- Notify users when their account status changes

Mail rules:

- Only active users appear in recipient lists
- The mail system can be turned on or off from the sidebar
- Long task descriptions are rendered in a clean wrapped layout
- Task creation shows a loading state while email delivery is processed

### Export System

Exports are handled with `maatwebsite/excel` and download as `.xlsx` files.

Supported export modes:

- Export all tasks
- Export tasks by date range
- Export tasks for a selected user
- Export all To Do tasks
- Export all In Progress tasks
- Export all Done tasks

Export formatting includes:

- Auto-sized columns
- Styled headers
- Colored status cells
- Colored priority cells

### Dashboard

The dashboard provides a simple operational snapshot, including:

- Total task counts
- Status distribution
- Recent activity
- Recent tasks
- User totals
- Quick access to the core admin areas

### User Manual

A full in-app user manual is available from the sidebar for:

- Admin
- Team Member
- Viewer

It explains the interface in plain language so non-technical users can get started quickly.

## Requirements

- PHP 8.3 or higher
- Composer
- Node.js and npm
- MySQL or another Laravel-supported database
- PHP `zip` extension enabled for Excel exports

## Installation

Install PHP and JavaScript dependencies:

```bash
composer install
npm install
```

Create the environment file and application key:

```bash
php -r "file_exists('.env') || copy('.env.example', '.env');"
php artisan key:generate
```

Configure your database, mail, queue, and app settings in `.env`, then run migrations and seeders:

```bash
php artisan migrate --seed
```

Create the public storage link for profile images and task attachments:

```bash
php artisan storage:link
```

Build frontend assets:

```bash
npm run build
```

You can also run the bundled setup script:

```bash
composer run setup
```

## Development

Start the application:

```bash
php artisan serve
```

In another terminal, run Vite:

```bash
npm run dev
```

Or launch the full local workflow:

```bash
composer run dev
```

## Testing

Run the application test suite with:

```bash
php artisan test
```

The current PHPUnit configuration uses a local MySQL test database named `task_management_test`.

## Usage

After signing in with an authorized account:

1. Open the dashboard for a workspace overview.
2. Open the admin dashboard through `/admin` or `/dashboard` depending on your role.
3. Open Users to create, edit, and manage roles and status.
4. Open Tasks to work with the Kanban board and category tabs.
5. Use Table View for searchable task management and status changes.
6. Use Calendar View to review task load by date.
7. Open Archived Tasks to restore archived items.
8. Open Export Tasks to download XLSX reports.
9. Open Send Mail to notify active users about delayed work or send custom mail.
10. Open Mail System to turn automatic assignment emails on or off.
11. Open User Manual for a role-friendly guide to the application.

## Important Routes

The main admin-facing routes currently include:

- `GET /dashboard`
- `GET /admin`
- `GET /admin/view-users`
- `GET /admin/add-user`
- `POST /admin/add-user`
- `GET /admin/users/{user}/edit`
- `PUT /admin/users/{user}`
- `PATCH /admin/users/{user}/toggle-status`
- `GET /admin/tasks`
- `GET /admin/tasks/calendar`
- `GET /admin/tasks/table`
- `GET /admin/tasks/archived`
- `GET /admin/tasks/export`
- `GET /admin/tasks/export/download`
- `GET /admin/mail-center`
- `POST /admin/mail-center/delayed-all`
- `POST /admin/mail-center/delayed-user`
- `POST /admin/mail-center/custom`
- `GET /admin/mail-system`
- `PATCH /admin/mail-system`
- `GET /admin/user-manual`

## Tech Stack

- Laravel 13.8
- Laravel Breeze 2.4
- Spatie Laravel Permission 8.3
- Maatwebsite Excel 3.1
- Bootstrap-based admin layout
- jQuery
- DataTables
- SweetAlert2
- SortableJS
- Feather Icons

## Project Structure

- `app/Http/Controllers/Admin/UserController.php` - user CRUD and status control
- `app/Http/Controllers/Admin/TaskController.php` - task board, task CRUD, archive, comments, attachments, and ordering
- `app/Http/Controllers/Admin/TaskCalendarController.php` - calendar aggregation and role-aware task data
- `app/Http/Controllers/Admin/TaskCategoryController.php` - task category management
- `app/Http/Controllers/Admin/TaskExportController.php` - Excel export entry points
- `app/Http/Controllers/Admin/MailCenterController.php` - delayed-task reminders and custom mail sending
- `app/Http/Controllers/Admin/MailSystemController.php` - automatic email toggle
- `app/Exports/TasksExport.php` - reusable XLSX export query and formatting logic
- `resources/views/admin/users/` - user management views
- `resources/views/admin/tasks/` - task board, table, archive, calendar, and export views
- `resources/views/admin/mail-center/` - mail center dashboard and forms
- `resources/views/help/user-manual.blade.php` - user manual page
- `resources/views/components/sidebar/sidebar.blade.php` - admin navigation
- `routes/user_routes/admin.php` - admin route definitions

## Notes

- Profile images and task attachments are stored on the public disk.
- The task board uses category tabs as the main navigation model.
- Statuses are intentionally kept to Backlog, To Do, In Progress, and Done.
- Table interactions favor AJAX to keep the interface responsive.
- Export output is XLSX, not CSV.
- The mail center targets active users only.
- Automatic assignment emails follow the mail system toggle.
- Task deletion is soft delete based.
- Inactive users cannot access the dashboard until reactivated.

## Copyright

Copyright © Tahmid Ferdous

## License

This project is open-sourced under the MIT license.
