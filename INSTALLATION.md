# Quick Installation Guide for BerryLearn

## For XAMPP Users (Windows/Mac/Linux)

### 1. Prerequisites
- XAMPP installed with Apache & MySQL
- Composer installed (https://getcomposer.org)

### 2. Quick Setup (5 minutes)

```bash
# Step 1: Extract to htdocs
# Place berrylearn folder in: C:\xampp\htdocs\berrylearn

# Step 2: Open terminal in project directory
cd C:\xampp\htdocs\berrylearn

# Step 3: Install dependencies
composer install

# Step 4: Setup environment
copy .env.example .env
php artisan key:generate

# Step 5: Configure .env (open in notepad)
# Set these values:
DB_DATABASE=berrylearn
DB_USERNAME=root
DB_PASSWORD=
# (Leave password empty for XAMPP default)

# Step 6: Create database
# Go to http://localhost/phpmyadmin
# Create new database named: berrylearn

# Step 7: Run migrations
php artisan migrate

# Step 8: Create admin user (optional)
php artisan tinker
```

In tinker, type:
```php
\App\Models\User::create(['name' => 'Admin', 'email' => 'admin@test.com', 'password' => bcrypt('password'), 'role' => 'admin']);
exit
```

### 3. Access Application

**URL**: http://localhost/berrylearn/public

**Admin Login**:
- Email: admin@test.com
- Password: password

**Register** as student via /register route

### 4. Middleware Registration

**IMPORTANT**: For Laravel 11+, add this to `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ]);
})
```

For Laravel 10, add to `app/Http/Kernel.php`:

```php
protected $middlewareAliases = [
    'role' => \App\Http\Middleware\RoleMiddleware::class,
];
```

## Troubleshooting

**Error: "Class 'RoleMiddleware' not found"**
→ Register middleware as shown above

**Error: Database connection failed**
→ Check MySQL is running in XAMPP
→ Verify database name matches .env

**Error: 404 on routes**
→ Ensure you're accessing via /public
→ Or setup virtual host

**File uploads not working**
→ Create /public/upload directory
→ Set write permissions

## Next Steps

1. Read the full README.md for detailed documentation
2. Create courses via admin panel
3. Add lessons and quizzes
4. Register test students

## Support

See README.md sections:
- Database Schema (Section 6)
- Admin Flow (Section 8)
- Troubleshooting (bottom of README)
