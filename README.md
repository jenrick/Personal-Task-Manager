# Personal Task Manager

Project Code: WST21-PM-2026-SF<br>
Student Name: DABALOS JENRICK S.<br>
Course & Year: BSIT 2 SECTION 8<br>
Database Used: SQLite

## Features

- Add Task
- View Tasks
- Edit Task
- Delete Task
- Update Status (Pending or Completed)
- Filter tasks by status and track overdue deadlines

## Built With

- Laravel 13
- PHP 8.3 or later
- SQLite
- Blade templates and CSS

## Run Locally

1. Install PHP dependencies with `composer install`.
2. Copy `.env.example` to `.env` if `.env` does not exist, then run `php artisan key:generate`.
3. Make sure `DB_CONNECTION=sqlite` is set in `.env` and create the database file if needed: `touch database/database.sqlite`.
4. Run `php artisan migrate`.
5. Start the app with `php artisan serve` and open the URL printed by Artisan.

## How It Works

The routes in `routes/web.php` send task requests to `TaskController`. The controller validates input and uses the `Task` Eloquent model to read and update the `tasks` table. Blade views render the task list and forms; Laravel's CSRF protection and method spoofing are used for form submissions.
