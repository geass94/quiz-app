# Famous Quote Quiz

Laravel 9 web app where users (guests after registering) take a 10-question, time-limited quiz of famous quotes. The quiz runs in two modes — **Binary** (Yes/No) and **Multiple choice** (3 options) — selectable from a per-user settings screen. Admins can author additional questions and inspect the full quiz history; everyone can see the top scorers.

## Requirements

You need a database **before** you start the app — the project does not bundle one. Either MySQL 8.0+ or MariaDB 10.2+ works.

Pick one of the two run modes below.

### Option A — native PHP (no Docker)

- PHP 8.0+ with extensions: `pdo_mysql`, `mbstring`, `intl`, `zip`, `gd`, `bcmath`, `xml`
- Composer 2
- Node 18+ and npm
- A running MySQL/MariaDB you can reach from your host

### Option B — Docker

- Docker 20+ with Compose v2 (`docker compose ...`)
- A running MySQL/MariaDB **on your host** (or anywhere reachable). Compose talks to the host DB via `host.docker.internal`; it does not start a database container.

## 1. Get the code

```bash
git clone <this-repo> quiz-app
cd quiz-app
cp .env.example .env
```

## 2. Configure the database

Create an empty database and a user with privileges on it:

```sql
CREATE DATABASE quiz_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'quiz'@'%' IDENTIFIED BY 'quiz';
GRANT ALL ON quiz_app.* TO 'quiz'@'%';
FLUSH PRIVILEGES;
```

Then edit `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1          # native: 127.0.0.1   Docker: host.docker.internal
DB_PORT=3306
DB_DATABASE=quiz_app
DB_USERNAME=quiz
DB_PASSWORD=quiz
```

If your MySQL is bound to `127.0.0.1` only, allow connections from Docker by binding to `0.0.0.0` (or to the `docker0` bridge IP) in your MySQL config.

### Optional — load the bundled SQL dump

The repo ships with `Local_mysql-2026_05_01_12_51_57-dump.sql` at the project root: a snapshot of the schema plus seeded data (admin user, sample questions, and any history captured at dump time). Loading it is an alternative to running `migrate:fresh --seed` — useful if you want a pre-populated database immediately, or want to reproduce the exact dataset used during development.

```bash
# Native MySQL client
mysql -u quiz -p quiz_app < Local_mysql-2026_05_01_12_51_57-dump.sql

# Or against a Dockerised MySQL on the host
mysql -h 127.0.0.1 -u quiz -p quiz_app < Local_mysql-2026_05_01_12_51_57-dump.sql
```

If you load the dump, **skip** `php artisan migrate:fresh --seed` in the next step — the dump already contains the schema and seed data. You still need `composer install`, `key:generate`, and the npm steps.

## 3a. Run with Docker

```bash
docker compose up -d --build
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate:fresh --seed
docker compose exec app npm install
docker compose exec app npm run dev   # or: npm run prod
```

App is now at **http://localhost:8097**.

The `worker` and `scheduler` services in `docker-compose.yml` exist for queued jobs and cron-style tasks — this project doesn't currently use either, so you can ignore them or comment them out.

## 3b. Run natively

```bash
composer install
php artisan key:generate
php artisan migrate:fresh --seed
npm install
npm run dev          # or: npm run prod
php artisan serve    # serves on http://127.0.0.1:8000
```

## Default credentials

The seeder creates an admin and 24 lorem-ipsum quote questions (12 Binary + 12 Multi):

- **Email:** `admin@local.host`
- **Password:** `1234`

To play as a guest, register a fresh account at `/register`.

## What's where

| Path | Description |
| --- | --- |
| `/dashboard` | Take the quiz. Resumes in-progress sessions on refresh. |
| `/settings` | Switch mode (Binary / Multiple choice). Changing mode resets any in-progress session. |
| `/top-scorers` | Public leaderboard (best attempt per user, ranked by score then time used). |
| `/create-quiz` | **Admin only.** Add a quiz with questions and answers. |
| `/admin/history` | **Admin only.** Every completed session: name, last name, email, score, unanswered, submit date. |

## Useful commands

```bash
# Reset the database and reseed
php artisan migrate:fresh --seed

# Native asset rebuild
npm run dev          # development build, expanded
npm run prod         # production build, minified

# Same inside Docker
docker compose exec app php artisan migrate:fresh --seed
docker compose exec app npm run dev
```

## Project notes

- **Frontend:** Blade + vanilla ES6, bundled by Laravel Mix into `public/css/` and `public/js/`. Tailwind drives the chrome; `resources/css/quiz.css` covers everything specific to the quiz UI.
- **Architecture:** thin controllers; all business logic lives under `app/Services/` (`SessionService`, `QuizService`, `LeaderboardService`, `SettingsService`).
- **Data shaping:** Spatie Laravel Data classes under `app/Data/` replace traditional API resources for JSON responses.
- **Sessions are server-authoritative:** correct answers never travel to the client. Each click hits `/ajax/session/answer` which records the row and replies with feedback for that one question only.
- **Resume on refresh:** an in-progress session is auto-detected by `DashboardController` and rehydrated with the original 10 questions, the elapsed-adjusted timer, and any previously-revealed feedback.
