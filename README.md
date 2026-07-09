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
- Browser-verified frontend login for `admin` and `super admin`: available.
- Browser screenshots for role-aware dashboards: available.
- Permission-protected risk register CRUD API: available.
- Seeded risk register demo records: available.
- Permission-protected control register CRUD API: available.
- Seeded control register demo records linked to seeded risks: available.
- Permission-protected action plan CRUD API: available.
- Seeded action plan demo records linked to seeded risks and controls: available.
- Permission-protected dashboard summary API: available.

Related frontend:

- [risk-management-front-end-next](https://github.com/silindokuhleL/risk-management-front-end-next)

## What This Project Proves

- Laravel API setup for a decoupled frontend.
- Sanctum-based first-party SPA authentication.
- Role and permission modeling with Spatie Permission.
- Seeded permission scopes for admin and super admin users.
- Authenticated API response that exposes roles and nested permissions to the frontend.
- Foundation for role-aware risk-management workflows.
- Permission-protected risk register workflow with risk scoring, owners, status, and category filters.
- Permission-protected control register workflow with linked risks, owners, effectiveness status, control type, due dates, and test dates.
- Permission-protected action plan workflow with linked risks, optional linked controls, owners, priorities, statuses, due dates, and completed dates.
- Permission-protected dashboard summary workflow across risks, controls, and action plans.

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
app/Http/Controllers/Api/RiskController.php         # Risk register API controller
app/Http/Controllers/Api/ControlController.php      # Control register API controller
app/Http/Controllers/Api/ActionPlanController.php   # Action plan API controller
app/Http/Controllers/Api/DashboardSummaryController.php # Dashboard summary API controller
app/Http/Requests/StoreRiskRequest.php              # Risk creation validation
app/Http/Requests/UpdateRiskRequest.php             # Risk update validation
app/Http/Requests/StoreControlRequest.php           # Control creation validation
app/Http/Requests/UpdateControlRequest.php          # Control update validation
app/Http/Requests/StoreActionPlanRequest.php        # Action plan creation validation
app/Http/Requests/UpdateActionPlanRequest.php       # Action plan update validation
app/Http/Resources/RiskResource.php                 # Risk API resource
app/Http/Resources/ControlResource.php              # Control API resource
app/Http/Resources/ActionPlanResource.php           # Action plan API resource
app/Http/Resources/DashboardSummaryResource.php     # Dashboard summary API resource
app/Models/Risk.php                                 # Risk model and owner relationship
app/Models/Control.php                              # Control model with risk and owner relationships
app/Models/ActionPlan.php                           # Action plan model with risk, control, and owner relationships
app/Models/User.php                                 # Uses Spatie HasRoles
app/Services/RiskService.php                        # Risk register business workflow
app/Services/ControlService.php                     # Control register business workflow
app/Services/ActionPlanService.php                  # Action plan business workflow
app/Services/DashboardSummaryService.php            # Dashboard summary workflow
database/seeders/RolesAndPermissionsSeeder.php      # Roles and permissions
database/seeders/UserSeeder.php                     # Seeded demo users
database/seeders/RiskSeeder.php                     # Seeded demo risks
database/seeders/ControlSeeder.php                  # Seeded demo controls
database/seeders/ActionPlanSeeder.php               # Seeded demo action plans
database/migrations/*create_risks_table.php         # Risk register table
database/migrations/*create_controls_table.php      # Control register table
database/migrations/*create_action_plans_table.php  # Action plans table
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

The risk register API is protected by Sanctum and the existing `view risks` permission:

```php
Route::middleware(['auth:sanctum', 'permission:view risks'])->group(function () {
    Route::apiResource('risks', RiskController::class);
});
```

Risk endpoints:

```text
GET    /api/risks
POST   /api/risks
GET    /api/risks/{risk}
PATCH  /api/risks/{risk}
DELETE /api/risks/{risk}
```

The API resource returns owner details, inherent score, residual score, status, category, and review dates. Controllers stay thin and call `RiskService` for workflow operations.

The control register API is protected by Sanctum and the existing `view controls` permission:

```php
Route::middleware(['auth:sanctum', 'permission:view controls'])->group(function () {
    Route::apiResource('controls', ControlController::class);
});
```

Control endpoints:

```text
GET    /api/controls
POST   /api/controls
GET    /api/controls/{control}
PATCH  /api/controls/{control}
DELETE /api/controls/{control}
```

The control API resource returns linked risk details, owner details, control type, effectiveness, status, due date, and tested date. Controllers stay thin and call `ControlService` for workflow operations.

The action plan API is protected by Sanctum and the existing `view action plans` permission:

```php
Route::middleware(['auth:sanctum', 'permission:view action plans'])->group(function () {
    Route::apiResource('action-plans', ActionPlanController::class);
});
```

Action plan endpoints:

```text
GET    /api/action-plans
POST   /api/action-plans
GET    /api/action-plans/{action_plan}
PATCH  /api/action-plans/{action_plan}
DELETE /api/action-plans/{action_plan}
```

The action plan API resource returns linked risk details, optional linked control details, owner details, priority, status, due date, and completed date. Controllers stay thin and call `ActionPlanService` for workflow operations.

The dashboard summary API is protected by Sanctum and the existing `view dashboard` permission:

```php
Route::middleware(['auth:sanctum', 'permission:view dashboard'])
    ->get('/dashboard/summary', DashboardSummaryController::class);
```

Dashboard endpoint:

```text
GET /api/dashboard/summary
```

The dashboard summary resource returns grouped counts for risks, controls, and action plans. The controller stays thin and calls `DashboardSummaryService` for the summary workflow.

## Visual And Verification Proof

- Local verification log: [docs/LOCAL_VERIFICATION.md](docs/LOCAL_VERIFICATION.md)
- Login form screenshot: [docs/proof-assets/risk-login-form.jpg](docs/proof-assets/risk-login-form.jpg)
- Admin dashboard screenshot: [docs/proof-assets/risk-admin-dashboard.png](docs/proof-assets/risk-admin-dashboard.png)
- Super admin dashboard screenshot: [docs/proof-assets/risk-super-admin-dashboard.png](docs/proof-assets/risk-super-admin-dashboard.png)

Verified locally on 2026-07-09:

- Temporary SQLite migration and seed completed.
- Admin and super-admin users can authenticate through direct Sanctum API requests.
- `/api/user` returns different role and permission scopes for `admin` and `super admin`.
- `RiskRegisterApiTest` verifies guest blocking, permission blocking, and admin create/list/show/update/delete risk workflow.
- `ControlRegisterApiTest` verifies guest blocking, permission blocking, and admin create/list/show/update/delete control workflow.
- `ActionPlanApiTest` verifies guest blocking, permission blocking, and admin create/list/show/update/delete action plan workflow.
- `DashboardSummaryApiTest` verifies guest blocking, permission blocking, and seeded dashboard summary counts.
- Browser verified the frontend login flow for both seeded users.
- Browser verified that admin does not see the `Users` navigation link.
- Browser verified that super admin sees the `Users` navigation link.
- Frontend lint and production build pass.

Known proof gaps:

- Existing Breeze feature tests are failing under the current local runtime/configuration.
- PHP 8.5 dependency deprecation output should be cleaned up.
- Frontend dependency vulnerabilities need a security upgrade pass.

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
- [ ] Full `php artisan test` passes.
- [x] `php artisan test tests/Feature/RiskRegisterApiTest.php` passes.
- [x] Admin user can authenticate through the frontend.
- [x] Super admin user can authenticate through the frontend.
- [x] `/api/user` returns roles and permissions for admin.
- [x] `/api/user` returns roles and permissions for super admin.
- [x] Permission-protected risk register API exists.
- [x] Risk register API has focused feature-test coverage.
- [x] Permission-protected control register API exists.
- [x] Control register API has focused feature-test coverage.
- [x] Permission-protected action plan API exists.
- [x] Action plan API has focused feature-test coverage.
- [x] Permission-protected dashboard summary API exists.
- [x] Dashboard summary API has focused feature-test coverage.
- [x] Browser/API screenshots captured for both permission scopes.

## Portfolio Notes

This backend is useful proof for authentication, RBAC, and early risk-management domain workflows. It should still be presented as an early GRC API rather than a complete GRC platform.

The next strongest improvement is to add a small risk domain module, for example:

- permission-protected user management
- frontend dashboard summary cards

That would turn the current RBAC, risk, control, action-plan, and dashboard-summary foundation into a stronger full product case study.
