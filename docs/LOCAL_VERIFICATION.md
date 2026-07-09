# Risk Management / RBAC Local Verification

## 2026-07-09

This verification used a temporary SQLite database so the local MySQL setup was not changed.

Temporary database:

```text
/tmp/risk-management-proof.sqlite
```

## Environment

- PHP 8.5.6
- Laravel 11.11.1
- Composer 2.9.8
- Node.js 24.16.0
- npm 11.13.0
- Next.js 14.2.3

## Commands Run

Backend migration and seed:

```bash
rm -f /tmp/risk-management-proof.sqlite
touch /tmp/risk-management-proof.sqlite
APP_ENV=local APP_DEBUG=true DB_CONNECTION=sqlite DB_DATABASE=/tmp/risk-management-proof.sqlite php artisan migrate:fresh --seed
```

Result:

- Passed.
- Created users, roles, permissions, personal access tokens, cache, jobs, and Spatie permission tables.
- Seeded `admin` and `super admin` users.

Frontend install and checks:

```bash
npm install
npm run lint
npm run build
```

Result:

- `npm install` completed.
- `npm run lint` passed with no ESLint warnings or errors.
- `npm run build` passed.
- npm audit reported 24 vulnerabilities: 1 low, 11 moderate, 10 high, 2 critical.
- Build warned that Browserslist/caniuse data is outdated.

Backend tests:

```bash
APP_ENV=local APP_DEBUG=true DB_CONNECTION=sqlite DB_DATABASE=/tmp/risk-management-proof.sqlite php artisan test
```

Result:

- 1 unit test passed.
- Existing Breeze feature tests failed under the current runtime/configuration.
- PHP 8.5 emitted dependency deprecation warnings from older Laravel ecosystem packages.

## Auth And RBAC Proof

Backend server was started with:

```bash
APP_ENV=local \
APP_DEBUG=true \
APP_URL=http://localhost:8000 \
FRONTEND_URL=http://localhost:3000 \
SANCTUM_STATEFUL_DOMAINS=localhost:3000 \
SESSION_DOMAIN=localhost \
DB_CONNECTION=sqlite \
DB_DATABASE=/tmp/risk-management-proof.sqlite \
php artisan serve --host=127.0.0.1 --port=8000
```

Frontend server:

```bash
npm run dev
```

### Admin User

Credentials:

```text
Email: Luyanda@gmail.com
Password: password
```

Direct Sanctum API verification:

- `/sanctum/csrf-cookie` returned `204`.
- `/login` returned `204`.
- `/api/user` returned `200`.
- User role: `admin`.
- Permissions returned:
  - `view dashboard`
  - `view risks`
  - `view controls`
  - `view action plans`
  - `view settings`

### Super Admin User

Credentials:

```text
Email: Sinokuhle@gmail.com
Password: password
```

Direct Sanctum API verification:

- `/sanctum/csrf-cookie` returned `204`.
- `/login` returned `204`.
- `/api/user` returned `200`.
- User role: `super admin`.
- Permissions returned:
  - `view dashboard`
  - `view risks`
  - `view controls`
  - `view action plans`
  - `view settings`
  - `manage users`

## Browser Verification

Browser opened:

```text
http://localhost:3000/login
```

Verified:

- Login form renders.
- Email field is visible.
- Password field is visible.
- Remember me checkbox is visible.
- Login button is visible.

Screenshot:

![Risk Management login form](proof-assets/risk-login-form.jpg)

Current Browser limitation:

- Browser-driven form submission did not complete even though direct Sanctum API login works.
- No current Browser console error was captured for the Next.js login page during the failed submit attempt.
- Treat the frontend login UI as visible, but the Browser-driven login journey still needs follow-up before this can be shown as a complete end-to-end UI proof.

## Important Findings

- The backend RBAC foundation is real and verified through the API.
- Admin and super admin users have different permission scopes.
- The frontend is still mostly a Breeze/Next auth shell, not a complete risk-management product UI.
- The backend currently emits PHP 8.5 deprecation output that contaminates JSON responses in local debug mode. This should be cleaned up before treating the project as production-ready.
- The frontend dependency tree needs a security upgrade pass before this project is promoted strongly.
