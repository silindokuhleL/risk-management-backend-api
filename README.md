# Risk Management Backend API

Laravel API backend for a Risk Management / RBAC proof project. The API uses Laravel Sanctum for SPA authentication and Spatie Permission for role and permission modeling.

> Repository name note: the public repo is currently named `rick-management-backend-api`. The project itself is the Risk Management Backend API.

## Portfolio Proof Status

Current status:

- Public backend repository: available.
- Laravel API backend: available.
- Laravel Sanctum auth scaffolding: available.
- Spatie Permission installed and configured: available.
- Roles and permissions migrations: available.
- Seeded `admin` and `super admin` users: available.
- Authenticated `/api/user` endpoint returns user with roles and permissions: available.
- Full risk CRUD/domain API: not present in this repo yet.
- Browser/API screenshots: still to capture.

Related frontend:

- [risk-management-front-end-next](https://github.com/silindokuhleL/risk-management-front-end-next)

## What This Project Proves

- Laravel API setup for a decoupled frontend.
- Sanctum-based first-party SPA authentication.
- Role and permission modeling with Spatie Permission.
- Seeded permission scopes for admin and super admin users.
- Authenticated API response that exposes roles and nested permissions to the frontend.
- Foundation for role-aware risk-management workflows.

## Tech Stack

- Laravel 11
- PHP 8.2+
- Laravel Sanctum
- Spatie Laravel Permission
- SQLite/MySQL-compatible Laravel migrations
- PHPUnit

## RBAC Model

Seeded roles:

- `admin`
- `super admin`

Seeded permissions:

- `view dashboard`
- `view risks`
- `view controls`
- `view action plans`
- `view settings`
- `manage users`

Role permission split:

- `admin` can view dashboard, risks, controls, action plans, and settings.
- `super admin` can do everything the admin can do, plus manage users.

## Seeded Demo Users

```text
Admin
Email: Luyanda@gmail.com
Password: password
Role: admin

Super Admin
Email: Sinokuhle@gmail.com
Password: password
Role: super admin
```

## Key Files

```text
routes/api.php                                      # Authenticated user endpoint
routes/auth.php                                     # Breeze/Sanctum auth routes
app/Models/User.php                                 # Uses Spatie HasRoles
database/seeders/RolesAndPermissionsSeeder.php      # Roles and permissions
database/seeders/UserSeeder.php                     # Seeded demo users
database/migrations/*permission*                    # Spatie permission tables
config/permission.php                               # Spatie Permission config
config/sanctum.php                                  # Sanctum config
```

## API Proof

The authenticated user endpoint loads roles and permissions:

```php
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    $user = Auth::user();
    $user->load('roles.permissions');

    return $user;
});
```

This allows the frontend to make role-aware decisions after login.

## Local Setup

Install dependencies:

```bash
composer install
```

Create environment file:

```bash
cp .env.example .env
php artisan key:generate
```

Run migrations and seeders:

```bash
php artisan migrate --seed
```

Start the API:

```bash
php artisan serve
```

Default API URL:

```text
http://localhost:8000
```

## Verification Checklist

- [ ] `composer install` completes successfully.
- [ ] `php artisan migrate:fresh --seed` completes successfully.
- [ ] `php artisan test` passes.
- [ ] Admin user can authenticate through the frontend.
- [ ] Super admin user can authenticate through the frontend.
- [ ] `/api/user` returns roles and permissions for admin.
- [ ] `/api/user` returns roles and permissions for super admin.
- [ ] Browser/API screenshots captured for both permission scopes.

## Portfolio Notes

This backend is useful proof for authentication and RBAC, but it should be presented honestly as an RBAC/auth foundation rather than a complete risk-management domain system.

The next strongest improvement is to add a small risk domain module, for example:

- risk register
- risk categories
- controls
- action plans
- permission-protected user management

That would turn the current RBAC foundation into a stronger full product case study.
