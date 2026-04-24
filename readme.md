# BerryLearn

BerryLearn is a Laravel-based learning management system with role-based access control for students and admins. It supports course delivery, lesson content, lesson media, quizzes, results tracking, and a separate admin management area.

## Overview

The project is built around two user roles:

- `student`: browse courses, open lessons, watch or download learning materials, take quizzes, and review results
- `admin`: manage students, courses, lessons, quizzes, and platform results

The current student experience uses a course library on `/home` and a dedicated lesson page on `/courses/{course}`. The admin area is available under `/admin`.

## Tech Stack

- Laravel `10.x`
- PHP `8.1+`
- MySQL or MariaDB
- Blade templates
- Bootstrap 5
- Local file storage in `public/upload`

## Current Features

### Student

- Browse all available courses from the student home page
- Open a dedicated course page to view that course's lessons
- Read lesson content
- Watch uploaded MP4 lesson videos
- Open or download uploaded lesson PDFs
- Use a course-level YouTube search link for related learning videos
- Take multiple-choice quizzes with automatic scoring
- View quiz history from the profile page
- Update profile details and password

### Admin

- Dashboard with key platform counts
- Manage students
- Create, edit, and delete courses
- Create, edit, and delete lessons
- Upload lesson videos and PDFs
- Create quizzes and quiz questions
- Review quiz results

## Main Routes

### Public / Auth

- `/login`
- `/register`
- `/logout`

### Student

- `/home`
- `/courses/{course}`
- `/profile`
- `/quiz/{quiz}/submit`

### Admin

- `/admin`
- `/admin/students`
- `/admin/courses`
- `/admin/lessons`
- `/admin/quizzes`
- `/admin/quiz-results`

## Local Setup

### 1. Install dependencies

```bash
composer install
```

### 2. Create the environment file

```bash
cp .env.example .env
php artisan key:generate
```

On Windows:

```bash
copy .env.example .env
php artisan key:generate
```

### 3. Configure the database

Set the database values in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=berrylearn
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run migrations

```bash
php artisan migrate
```

### 5. Create an admin account

```bash
php artisan tinker
```

Then:

```php
\App\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin@berrylearn.com',
    'password' => bcrypt('password123'),
    'role' => 'admin',
]);
```

### 6. Start the app

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Open:

- `http://127.0.0.1:8000`
- or your local Apache/XAMPP proxy if you have mapped `localhost` to the Laravel server

## Lesson Media Uploads

Admins can attach learning materials to lessons.

### Dedicated lesson materials

- Video upload field: MP4, up to `50MB`
- PDF upload field: PDF, up to `20MB`

Uploaded files are stored under:

```text
public/upload
```

Students can then:

- watch uploaded MP4 files directly in the lesson page
- open uploaded PDFs in a new tab
- download either file

## Legacy Compatibility

BerryLearn currently supports two lesson media storage modes.

### New schema

If the `lessons` table contains:

- `video_path`
- `pdf_path`

then a lesson can hold both a video and a PDF separately.

### Legacy schema

If the server has not yet run the latest lessons migration, the app falls back to the original single-file `media_path` column. In that mode:

- a lesson can still store one uploaded file without crashing
- uploads do not produce a `500` error
- attaching both a video and a PDF to the same lesson is blocked until migrations are run

To enable full dual-upload support, run:

```bash
php artisan migrate
```

## Deployment Notes

After pulling new code onto a server:

```bash
git pull origin main
php artisan optimize:clear
php artisan view:cache
```

If the release includes schema changes, also run:

```bash
php artisan migrate
```

## Admin Stability Notes

The admin list pages have been hardened for older datasets and partially legacy databases:

- course, lesson, quiz, and result lists do not rely on `latest()` ordering
- admin views tolerate missing related records more safely
- timestamp display is guarded where older data may be incomplete

## Troubleshooting

### Uploads return `500`

Check:

```bash
php artisan migrate
php artisan optimize:clear
php artisan view:cache
```

If the server is still on the old lessons schema, uploads will use legacy single-file mode until the migration is applied.

### Admin pages return `500`

Clear caches and inspect the Laravel log:

```bash
php artisan optimize:clear
tail -n 80 storage/logs/laravel.log
```

### Files do not save

Make sure the app can write to `public/upload`, `storage`, and `bootstrap/cache`.

On Linux or macOS:

```bash
chmod -R 775 storage bootstrap/cache public/upload
```

## Project Notes

- The app uses server-rendered Blade views, not a SPA
- Role access is enforced through auth and role middleware
- The current README reflects the dedicated course-page flow and the current lesson media implementation
