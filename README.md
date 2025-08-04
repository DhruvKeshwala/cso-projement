# Laravel User CRUD Application

A complete CRUD (Create, Read, Update, Delete) application built with Laravel for managing users.

## Features

- **User Management**: Full CRUD operations for users
- **Validation**: Server-side validation for all user fields
- **Modern UI**: Bootstrap 5 with responsive design
- **AJAX Integration**: Asynchronous operations with JSON responses
- **Modal Forms**: Add and Edit operations via Bootstrap modals
- **Real-time Updates**: Dynamic table updates without page refresh

## Requirements

- PHP 8.1 or higher
- Composer
- MySQL/PostgreSQL/SQLite
- Laravel 10.x

## Installation

1. **Clone the repository** (if not already done):
   ```bash
   git clone <repository-url>
   cd CSO-Projement
   ```

2. **Install PHP dependencies**:
   ```bash
   composer install
   ```

3. **Copy environment file**:
   ```bash
   cp .env.example .env
   ```

4. **Generate application key**:
   ```bash
   php artisan key:generate
   ```

5. **Configure database**:
   Edit `.env` file and set your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations**:
   ```bash
   php artisan migrate
   ```

7. **Seed the database** (optional):
   ```bash
   php artisan db:seed
   ```

8. **Start the development server**:
   ```bash
   php artisan serve
   ```

9. **Access the application**:
   Open your browser and navigate to `http://localhost:8000`

## API Endpoints

The application provides the following RESTful API endpoints:

- `GET /users` - List all users
- `POST /users` - Create a new user
- `GET /users/{id}` - Get a specific user
- `PUT /users/{id}` - Update a user
- `DELETE /users/{id}` - Delete a user

All endpoints return JSON responses.

## Validation Rules

- **Name**: Required, string, max 255 characters
- **Email**: Required, valid email format, unique
- **Password**: Required, minimum 6 characters

## File Structure

```
app/
├── Http/Controllers/
│   └── UserController.php          # Main CRUD controller
├── Models/
│   └── User.php                    # User model
resources/
└── views/
    └── users/
        └── index.blade.php         # Main user management interface
database/
├── migrations/
│   └── 0001_01_01_000000_create_users_table.php
└── seeders/
    ├── DatabaseSeeder.php
    └── UserSeeder.php
routes/
└── web.php                         # Application routes
```

## Usage

1. **View Users**: The main page displays all users in a table format
2. **Add User**: Click the "Add User" button to open a modal form
3. **Edit User**: Click the edit icon (pencil) next to any user
4. **Delete User**: Click the delete icon (trash) next to any user

## Technologies Used

- **Backend**: Laravel 10.x
- **Frontend**: Bootstrap 5, jQuery, AJAX
- **Database**: MySQL/PostgreSQL/SQLite
- **Validation**: Laravel Validator
- **UI Components**: Bootstrap Modals, Font Awesome Icons

## Security Features

- CSRF protection for all forms
- Password hashing using Laravel's Hash facade
- Input validation and sanitization
- SQL injection prevention through Eloquent ORM

## Testing

To run the application tests:
```bash
php artisan test
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
