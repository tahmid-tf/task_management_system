# Task Management Admin

Task Management Admin is a Laravel 13 workspace for structured team operations. It combines authenticated admin access, role-based permissions, user management, task tracking, mail automation, and Excel exports in a polished admin interface built for day-to-day operations.

## Overview

The application is organized around three core areas:

- User administration
- Task management
- Communication and reporting

The UI uses a persistent admin layout with active navigation highlighting, SweetAlert2 confirmations, responsive DataTables, and AJAX-driven interactions where it matters most.

## What Is Implemented

### Authentication and Access Control

- Laravel Breeze authentication
- Verified routes for protected areas
- Spatie Laravel Permission for roles and permissions
- Active account enforcement through middleware
- Logout confirmation modal in the admin topbar

### Roles

The system currently uses these roles:

- Admin
- Team Member
- Viewer

### User Management

- Create users with AJAX submission
- Edit users in a modal workflow
- View users in a responsive DataTable
- Toggle active and inactive status with confirmation
- Prevent the logged-in admin from disabling their own account
- Send status-change emails on active and inactive updates
- Upload and preview profile images
- Store phone number and address
- Assign roles during create and edit
- Highlight the active route in the sidebar

Inactive users:

- Cannot access the dashboard
- Cannot use authenticated admin pages
- Are blocked at login with a clear message
- Are excluded from assignee selection and mail center recipient lists

### Task Management

- Kanban board organized by category
- Horizontal category tabs for quick switching
- Board columns:
  - Backlog
  - To Do
  - In Progress
  - Done
- SortableJS drag and drop ordering
- Task creation in a modal from the board
- Task editing in a modal from the table view
- AJAX status updates from the task table
- Task detail modal
- Task comments and activity history
- File attachments
- Task duplication
- Archive and restore workflow
- Soft delete support for admin-only task deletion
- Responsive table view for all tasks
- Assigned by and assignee visibility
- Active-user filtering for task assignment

### Mail System

The admin sidebar includes a dedicated mail section for operational communication.

Available mail workflows:

- Send delayed task reminders to all active users
- Send delayed task reminders to a selected active user
- Send custom mail to a selected active user
- Automatically email the assignee when a task is created or duplicated
- Send user status-change notifications when accounts are activated or deactivated

Additional mail behavior:

- Only active users appear in mail recipient selectors
- Task reminder emails use a polished layout
- Long task descriptions render in a readable wrapped block
- The mail system can be toggled on or off from the sidebar

### Export System

Task exports use `maatwebsite/excel` and generate `.xlsx` files.

Supported export modes:

- Export all tasks
- Export tasks by date range
- Export tasks for a selected user
- Export all `To Do` tasks
- Export all `In Progress` tasks
- Export all `Done` tasks

Export formatting includes:

- Auto-sized columns
- Styled header rows
- Colored status cells
- Colored priority cells

### Dashboard

The dashboard provides a current snapshot of the workspace, including:

- Total task counts
- Status distribution
- Recent activity
- Recent tasks
- User totals
- Quick links to board, exports, and user management

## Requirements

- PHP 8.3 or higher
- Composer
- Node.js and npm
- MySQL or another Laravel-supported database
- PHP `zip` extension enabled for Excel export support

## Installation

Install dependencies:

```bash
composer install
npm install
```

Create the environment file and application key:

```bash
php -r "file_exists('.env') || copy('.env.example', '.env');"
php artisan key:generate
```

Configure your database, mail, and app settings in `.env`, then run the migrations and seeders:

```bash
php artisan migrate --seed
```

Create the storage symlink for profile images and task attachments:

```bash
php artisan storage:link
```

Build frontend assets:

```bash
npm run build
```

You can also use the bundled setup script:

```bash
composer run setup
```

## Development

Run the application locally:

```bash
php artisan serve
```

In a second terminal, run Vite:

```bash
npm run dev
```

Or start the full local workflow:

```bash
composer run dev
```

## Usage

After signing in with an authorized account:

- Open the dashboard for a workspace overview
- Open Users to manage accounts and roles
- Open Tasks to use the Kanban board
- Switch task categories using the horizontal tabs
- Use Table View for a searchable, responsive task list
- Open Archived Tasks to restore archived items
- Open Export Tasks to download XLSX reports
- Open Send Mail to notify active users about delayed work or send custom mail
- Use Mail System to control automatic task-assignment emails

## Common Routes

Current admin-facing routes include:

- `GET /dashboard`
- `GET /admin/view-users`
- `GET /admin/add-user`
- `POST /admin/add-user`
- `GET /admin/users/{user}/edit`
- `PUT /admin/users/{user}`
- `PATCH /admin/users/{user}/toggle-status`
- `GET /admin/tasks`
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

- `app/Http/Controllers/Admin/UserController.php` - user CRUD and status control
- `app/Http/Controllers/Admin/TaskController.php` - task board, task CRUD, archive, comments, attachments, and ordering
- `app/Http/Controllers/Admin/TaskCategoryController.php` - task category management
- `app/Http/Controllers/Admin/TaskExportController.php` - Excel export entry points
- `app/Http/Controllers/Admin/MailCenterController.php` - delayed-task reminders and custom mail sending
- `app/Exports/TasksExport.php` - reusable XLSX export query and formatting logic
- `resources/views/admin/users/` - user management views
- `resources/views/admin/tasks/` - task board, table, archive, category, and export views
- `resources/views/admin/mail-center/` - mail center dashboard and forms
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
- Inactive users cannot access the application until reactivated.

## License

This project is open-sourced under the MIT license.
