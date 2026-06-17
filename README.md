# IPA Empanelment Portal

A Laravel-based admin portal for the **Indian Ports Association (IPA) Center of Excellence** empanelment process. It lets professionals register, verify their email, and submit an Expression of Interest (EOI) with their education and work experience, while super-admins review, edit, and export applicant records.

---

## Features

- **Admin authentication** — registration, login, logout, and password reset with a dedicated `admin` auth guard (separate from the default user guard).
- **Email verification** — a 6-digit code is emailed on registration and expires after 48 hours; unverified accounts cannot log in.
- **Role-based access** — regular admins can create and manage their own application; super-admins can view, edit, delete, and export *all* applicants. Enforced via the `admin.super` middleware.
- **Applicant management** — name, address, contact, category selection, multiple education entries, and multiple experience entries, plus resume and additional-document uploads (PDF/DOC/DOCX).
- **CSV export** — super-admins can export the full applicant list.
- **Security hardening** — bcrypt password hashing (12 rounds), session regeneration on login/logout, login rate-limiting, encrypted route keys so applicant IDs are never exposed in URLs, and mass-assignment protection.

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 12 (PHP 8.2+) |
| Database | MySQL |
| Frontend | Blade templates, Bootstrap-based admin theme |
| Build | Vite |
| Deployment | Docker / Docker Compose (optional) |

---

## Getting Started (Local)

```bash
# 1. Install dependencies
composer install
npm install

# 2. Environment
cp .env.example .env
php artisan key:generate

# 3. Configure your database in .env
#    DB_DATABASE=empanelmentadmin
#    DB_USERNAME=root
#    DB_PASSWORD=

# 4. Run migrations
php artisan migrate

# 5. Build assets and serve
npm run dev
php artisan serve
```

The app runs at `http://localhost:8000`. The admin area lives under `/admin`.

> Mail is set to the `log` driver by default, so verification codes appear in `storage/logs/laravel.log` during local development.

---

## Docker

A Docker setup is included. See [`DOCKER_SETUP.md`](DOCKER_SETUP.md) for full instructions.

```bash
cp .env.docker.example .env.docker
docker-compose up -d
```

---

## Project Structure

```
app/
  Http/Controllers/   # Auth, applicant, and admin management controllers
  Http/Middleware/    # AdminSuper (role enforcement), Authenticate
  Models/             # Admin, Applicant, ApplicantEducation, ApplicantExperience
  Notifications/      # Registration + password reset emails
database/migrations/  # Schema (admins, applicants, education, experience, etc.)
resources/views/admin # Blade views for the portal
routes/web.php        # All routes
tests/Feature/        # Feature tests
```

---

## Testing

```bash
php artisan test
```

See [`APPLICANT_GUIDE.md`](APPLICANT_GUIDE.md) for the end-user walkthrough.

---

## Author

Jesika Choudhary — B.Tech Information Technology, Manipal University Jaipur.
