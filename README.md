# Manage Studio

Manage Studio is a **human resources and operations dashboard** built on Laravel. It gives teams a single place to manage people, org structure, time, recruiting, projects, tasks, meetings, internal messaging, and HR workflows—with **role-based access** for administrators and employees.

**Author:** [Hrant Sahakyan](https://github.com/HrantIKM) — student at **Yerevan State University (YSU)**, **Information Security** programme.

---

## Table of contents

- [Author](#author)
- [Features](#features)
- [Tech stack](#tech-stack)
- [Requirements](#requirements)
- [Project setup](#project-setup)
- [Environment configuration](#environment-configuration)
- [Database and seeding](#database-and-seeding)
- [Frontend assets](#frontend-assets)
- [Running the application](#running-the-application)
- [Localization](#localization)
- [Real-time messaging (optional)](#real-time-messaging-optional)
- [Useful Artisan commands](#useful-artisan-commands)
- [Project structure (high level)](#project-structure-high-level)
- [Security notes](#security-notes)
- [License](#license)

---

## Author

This project was developed by **[Hrant Sahakyan](https://github.com/HrantIKM)**, a student of **Yerevan State University (YSU)** in the **Information Security** programme.

---

## Features

| Area | What the app supports |
|------|------------------------|
| **People** | Users (employees), profile, skills matrix, attendance (clock in/out, calendar feed), salaries, payslips (HR + “my payslips”), leave requests and balances |
| **Organization** | Departments (hierarchy, hub/map, table), positions, skills catalog |
| **Work** | Projects, tasks (table + Kanban board), timesheets, goals |
| **Collaboration** | Meetings (table + calendar, rooms, action items → tasks), direct messages, notifications |
| **Recruiting** | Vacancies, candidates (admin-only), public careers pages |
| **HR** | Performance reviews (HR views + employee “my feedback”), holidays |
| **Platform** | File uploads, DataTables-style listings, exports (where implemented), Barryvdh translation manager (dashboard), activity logging (Spatie) |

**Roles**

- **Admin** — full catalog (positions, vacancies, candidates) and broader HR data visibility.
- **User (employee)** — self-service and team tools; sensitive lists are scoped in controllers/models (e.g. own payslips, own balances, tasks visibility rules).

Public site routes (no dashboard): home and **careers** (`/careers`, vacancy detail, apply).

---

## Tech stack

| Layer | Technology |
|-------|------------|
| **Backend** | PHP **8.3**, **Laravel 12** |
| **Database** | **MySQL** (configurable via `.env`) |
| **Auth** | Laravel UI, sessions, **Laravel Sanctum** (where used) |
| **Permissions** | **Spatie Laravel Permission** (see Composer; use `composer install` without `--no-dev` for local development unless you move the package to `require`) |
| **Frontend** | **Laravel Mix 6**, **Webpack**, **Sass**, **Bootstrap 5**, **jQuery**, **Axios**, **Laravel Echo** + **Pusher JS** (compatible with Reverb) |
| **PDF / Excel** | DomPDF, Maatwebsite Excel |
| **i18n** | **mcamara/laravel-localization** — locale prefix on URLs |
| **Realtime** | **Laravel Reverb** (optional), configurable via `BROADCAST_DRIVER` |
| **Other** | Translation UI (barryvdh/laravel-translation-manager), Spatie Activity Log, etc. |

---

## Requirements

- **PHP 8.3+** with extensions typical for Laravel (e.g. `openssl`, `pdo`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`; plus `dom`, `libxml` as required by `composer.json`)
- **Composer 2**
- **Node.js 16+** and **npm** (for Mix)
- **MySQL 5.7+ / 8.x** (or MariaDB compatible with Laravel)

---

## Project setup

### 1. Clone and install PHP dependencies

```bash
git clone <your-repository-url> hrmanagement
cd hrmanagement
composer install
```

### 2. Environment file

```bash
copy .env.example .env
```

On macOS/Linux:

```bash
cp .env.example .env
```

Edit `.env` (see [Environment configuration](#environment-configuration)).

Generate the application key:

```bash
php artisan key:generate
```

### 3. Database

Create an empty MySQL database matching `DB_DATABASE` in `.env`, then:

```bash
php artisan migrate
php artisan db:seed
```

Optional: to **truncate** a defined set of tables before seeding (destructive), set in `.env`:

```env
SEED_TRUNCATE_ALL=true
```

Then run `php artisan db:seed` again. Set back to `false` for normal use.

### 4. Storage link

```bash
php artisan storage:link
```

### 5. JavaScript and CSS

```bash
npm install
npm run dev
```

For optimized production assets:

```bash
npm run production
```

### 6. Local development server

```bash
php artisan serve
```

Open `http://127.0.0.1:8000` (or your `APP_URL`). Dashboard URLs are prefixed with a **locale** (see [Localization](#localization)), for example:

- `http://127.0.0.1:8000/en/dashboard`
- `http://127.0.0.1:8000/hy/dashboard`

---

## Environment configuration

Important `.env` keys:

| Variable | Purpose |
|----------|---------|
| `APP_NAME` | Product name (sidebar, titles), default **Manage Studio** |
| `APP_URL` | Base URL used for links and assets |
| `APP_ENV`, `APP_DEBUG` | Environment and debug (never `APP_DEBUG=true` in production) |
| `DB_*` | MySQL connection |
| `BROADCAST_DRIVER` | `log`, `null`, `reverb`, `pusher`, etc. |
| `MAIL_*` | Outbound mail (e.g. Mailhog in local `.env.example`) |
| `SHOW_NOTIFICATION` | UI notification behavior flag |

After changing config, clear cached config:

```bash
php artisan config:clear
```

---

## Database and seeding

`php artisan db:seed` runs `DatabaseSeeder`, which typically seeds:

- Roles and permissions  
- Departments, positions, sample skills  
- **Admin user** and demo users  
- Sidebar menu entries  

**Default admin account (from `AdminUserSeeder`):**

- **Email:** `admin@admin.com`  
- **Password:** (see `database/seeders/User/AdminUserSeeder.php`)

Change this password immediately on any shared or production environment.

---

## Frontend assets

- **Mix entry:** `webpack.mix.js` — combines dashboard core scripts into `public/js/dashboard/bundle.js`, compiles `resources/js/app.js`, dashboard modules (`chat`, `department/hub`, `task/board`, etc.), and Sass (`resources/sass/dashboard/dashboard-app.scss`, `resources/sass/app.scss`).
- **Watching during development:**

```bash
npm run watch
```

---

## Running the application

| Command | Description |
|---------|-------------|
| `php artisan serve` | PHP built-in web server |
| `npm run watch` | Rebuild JS/CSS on file changes |

Login is via Laravel’s auth routes (`Auth::routes` with **register** and **password reset** disabled in `routes/web.php`). After login, users are sent to the dashboard (`RouteServiceProvider::HOME`).

---

## Localization

- Locales are configured in **`config/laravellocalization.php`** (supported: **English `en`**, **Armenian `hy`** by default).
- **Routes:** `routes/web.php` and `routes/dashboard.php` are wrapped with `LaravelLocalization::setLocale()` so URLs include the locale segment.
- **Strings:** `resources/lang/{locale}/*.php` — keep **English** and **Armenian** files aligned when adding keys.
- **Dashboard JS** may use shared translation patterns (e.g. `__dashboard` language files where implemented).

---

## Real-time messaging (optional)

For live message updates, the app can use **Laravel Reverb** (or Pusher-compatible services). When broadcasting is not configured, messaging still works over HTTP; the UI may show a notice that live updates are off.

Typical steps (Reverb):

1. Install/configure Reverb per [Laravel Reverb documentation](https://laravel.com/docs/reverb).  
2. Set `BROADCAST_DRIVER=reverb` and the `REVERB_*` / `VITE_*` variables as required by your Laravel version.  
3. Run the Reverb server alongside `php artisan serve` and build front-end assets so Echo connects to your host/port.

Refer to `config/reverb.php` and `config/broadcasting.php` for available options.

---

## Useful Artisan commands

```bash
php artisan migrate
php artisan migrate:fresh --seed   # destructive: drops all tables, migrates, seeds
php artisan db:seed
php artisan storage:link
php artisan config:clear
php artisan cache:clear
php artisan route:list
php artisan queue:work          # if you switch QUEUE_CONNECTION to database/redis
```

**Code quality (dev):** the project includes **Pint**, **PHP CS Fixer**, **PHPStan/Larastan** in `composer.json` — run them according to your team’s workflow (e.g. `./vendor/bin/pint`).

---

## Project structure (high level)

| Path | Role |
|------|------|
| `app/Http/Controllers/Dashboard/` | Dashboard HTTP layer |
| `app/Models/` | Eloquent models (users, departments, tasks, …) |
| `app/Services/` | Domain/business services |
| `resources/views/components/dashboard/` | Blade UI for dashboard |
| `resources/js/` | Source JavaScript (compiled by Mix) |
| `resources/sass/dashboard/` | Dashboard styles |
| `routes/dashboard.php` | Authenticated dashboard routes + middleware |
| `routes/web.php` | Public site + auth |
| `database/migrations/` | Schema |
| `database/seeders/` | Seed data |

---

## Security notes

- Never commit real `.env` files or production credentials.
- Rotate the **seed admin password** before deploying.
- Keep `APP_DEBUG=false` in production.
- Run `composer audit` / dependency updates regularly.

---

## License

This application is based on Laravel and ships with Laravel’s **MIT** license for the framework portions. Add or adjust your own license for proprietary project code as required by your organization.
