#   BerryLearn - Laravel RBAC Learning Management System

A comprehensive Learning Management System (LMS) built with Laravel featuring Role-Based Access Control (RBAC), course management, quizzes, and student progress tracking.

## 📋 Table of Contents

1. [Project Overview](#project-overview)
2. [Tech Stack](#tech-stack)
3. [Features](#features)
4. [Installation Guide (XAMPP)](#installation-guide-xampp)
5. [Authentication & RBAC](#authentication--rbac)
6. [Database Schema](#database-schema)
7. [Student Flow](#student-flow)
8. [Admin Flow](#admin-flow)
9. [File Upload Logic](#file-upload-logic)
10. [Quiz Scoring System](#quiz-scoring-system)
11. [Security Considerations](#security-considerations)
12. [Future Improvements](#future-improvements)

---

## 1. Project Overview

BerryLearn is a full-featured Learning Management System designed to run locally on XAMPP. It provides:

- **Student Portal**: Browse courses, study lessons, download materials, and take quizzes
- **Admin Dashboard**: Manage students, create content, and track progress
- **RBAC System**: Role-based access control separating student and admin capabilities
- **Media Management**: Upload and distribute learning materials
- **Assessment System**: Create quizzes with automatic scoring

---

## 2. Tech Stack

- **Framework**: Laravel 10.x/11.x (latest stable)
- **PHP**: 8.1+ (XAMPP compatible)
- **Database**: MySQL 5.7+ / MariaDB
- **Frontend**: Blade Templates + Bootstrap 5
- **Server**: Apache (via XAMPP)
- **File Storage**: Local `/public/upload` directory

**Note**: No JavaScript frameworks (React/Vue), no SPA architecture.

---

## 3. Features

### Student Features
- ✅ Browse available courses
- ✅ View lessons with content and downloadable media
- ✅ Take multiple-choice quizzes
- ✅ Instant quiz scoring and feedback
- ✅ Profile management (name, email, password)
- ✅ Quiz history and score tracking

### Admin Features
- ✅ Student management (view, edit, delete, reset passwords)
- ✅ Course creation and management
- ✅ Lesson creation with media uploads
- ✅ Quiz and question management
- ✅ View all quiz results with filtering
- ✅ Dashboard with system statistics

---

## 4. Installation Guide (XAMPP)

### Prerequisites
- XAMPP installed (includes Apache, MySQL, PHP)
- Composer installed ([getcomposer.org](https://getcomposer.org))
- Git (optional)

### Step 1: Start XAMPP Services
1. Open XAMPP Control Panel
2. Start **Apache** module
3. Start **MySQL** module

### Step 2: Create Database
1. Open browser and go to: `http://localhost/phpmyadmin`
2. Click "New" to create a database
3. Database name: `berrylearn`
4. Collation: `utf8mb4_unicode_ci`
5. Click "Create"

### Step 3: Extract/Clone Project
```bash
# Extract the project to:
C:\xampp\htdocs\berrylearn

# Or clone if using Git:
cd C:\xampp\htdocs
git clone <repository-url> berrylearn
```

### Step 4: Install Dependencies
```bash
cd C:\xampp\htdocs\berrylearn
composer install
```

### Step 5: Configure Environment
```bash
# Copy the example environment file
copy .env.example .env

# Generate application key
php artisan key:generate
```

### Step 6: Edit .env File
Open `.env` in a text editor and configure:

```env
APP_NAME=BerryLearn
APP_URL=http://localhost/berrylearn/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=berrylearn
DB_USERNAME=root
DB_PASSWORD=
```

**Note**: XAMPP's default MySQL user is `root` with no password.

### Step 7: Run Migrations
```bash
php artisan migrate
```

This creates all database tables.

### Step 8: Create Admin User (Optional)
```bash
php artisan tinker
```

Then run:
```php
\App\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin@berrylearn.com',
    'password' => bcrypt('password123'),
    'role' => 'admin'
]);
exit
```

### Step 9: Set Permissions
```bash
# Windows (if needed):
# Right-click storage and bootstrap/cache folders
# Properties → Security → Edit → Add write permissions

# Linux/Mac:
chmod -R 775 storage bootstrap/cache
```

### Step 10: Access Application
Open your browser and navigate to:

- **Application**: `http://localhost/berrylearn/public`
- **Login**: Use registered credentials
- **Admin Login**: admin@berrylearn.com / password123 (if created)

---

## 5. Authentication & RBAC

### User Roles

The system implements two distinct roles stored in the `users.role` column:

1. **Student** (`role = 'student'`)
   - Default role for new registrations
   - Access to `/home`, `/profile`, quiz submission
   - Cannot access admin routes

2. **Admin** (`role = 'admin'`)
   - Manually created via database or tinker
   - Access to `/admin/*` routes
   - Cannot access student-specific routes

### RBAC Implementation

#### Middleware: `RoleMiddleware`
Located at: `app/Http/Middleware/RoleMiddleware.php`

```php
public function handle(Request $request, Closure $next, string $role)
{
    if (!auth()->check()) {
        return redirect('/login');
    }

    if (auth()->user()->role !== $role) {
        abort(403, 'Unauthorized action.');
    }

    return $next($request);
}
```

#### Route Protection
Routes are grouped and protected by role:

```php
// Student routes
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/profile', [ProfileController::class, 'index']);
    // ...
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index']);
    Route::get('/students', [AdminController::class, 'students']);
    // ...
});
```

#### Middleware Registration
Register in `bootstrap/app.php` (Laravel 11+) or `app/Http/Kernel.php` (Laravel 10):

```php
protected $middlewareAliases = [
    'role' => \App\Http\Middleware\RoleMiddleware::class,
];
```

### Authentication Flow

1. User accesses `/login` or `/register`
2. Credentials validated against `users` table
3. Upon successful login:
   - Session created
   - User redirected based on role:
     - Admin → `/admin`
     - Student → `/home`
4. Subsequent requests checked by middleware
5. Unauthorized access → 403 Forbidden

---

## 6. Database Schema

### Entity-Relationship Overview

```
users (1) ──────── (*) quiz_results
courses (1) ──────── (*) lessons
lessons (1) ──────── (*) quizzes
quizzes (1) ──────── (*) questions
quizzes (1) ──────── (*) quiz_results
```

### Tables and Relationships

#### `users`
```sql
id               BIGINT UNSIGNED PRIMARY KEY
name             VARCHAR(255)
email            VARCHAR(255) UNIQUE
password         VARCHAR(255)  -- Hashed
role             ENUM('student', 'admin') DEFAULT 'student'
remember_token   VARCHAR(100) NULLABLE
created_at       TIMESTAMP
updated_at       TIMESTAMP
```

**Relationships**:
- Has many `quiz_results`

**Indexes**:
- Primary key on `id`
- Unique on `email`

---

#### `courses`
```sql
id               BIGINT UNSIGNED PRIMARY KEY
title            VARCHAR(255)
description      TEXT NULLABLE
created_at       TIMESTAMP
updated_at       TIMESTAMP
```

**Relationships**:
- Has many `lessons`

---

#### `lessons`
```sql
id               BIGINT UNSIGNED PRIMARY KEY
course_id        BIGINT UNSIGNED FOREIGN KEY → courses(id)
title            VARCHAR(255)
content          TEXT NULLABLE
media_path       VARCHAR(255) NULLABLE  -- Relative path to /public/upload
created_at       TIMESTAMP
updated_at       TIMESTAMP
```

**Relationships**:
- Belongs to `course`
- Has many `quizzes`

**Cascade**: `ON DELETE CASCADE` (deleting course removes lessons)

---

#### `quizzes`
```sql
id               BIGINT UNSIGNED PRIMARY KEY
lesson_id        BIGINT UNSIGNED FOREIGN KEY → lessons(id)
title            VARCHAR(255)
created_at       TIMESTAMP
updated_at       TIMESTAMP
```

**Relationships**:
- Belongs to `lesson`
- Has many `questions`
- Has many `quiz_results`

**Cascade**: `ON DELETE CASCADE`

---

#### `questions`
```sql
id               BIGINT UNSIGNED PRIMARY KEY
quiz_id          BIGINT UNSIGNED FOREIGN KEY → quizzes(id)
question_text    TEXT
option_a         VARCHAR(255)
option_b         VARCHAR(255)
option_c         VARCHAR(255)
option_d         VARCHAR(255)
correct_answer   ENUM('a', 'b', 'c', 'd')
created_at       TIMESTAMP
updated_at       TIMESTAMP
```

**Relationships**:
- Belongs to `quiz`

**Cascade**: `ON DELETE CASCADE`

---

#### `quiz_results`
```sql
id               BIGINT UNSIGNED PRIMARY KEY
user_id          BIGINT UNSIGNED FOREIGN KEY → users(id)
quiz_id          BIGINT UNSIGNED FOREIGN KEY → quizzes(id)
score            INTEGER  -- Number of correct answers
total            INTEGER  -- Total questions
created_at       TIMESTAMP
updated_at       TIMESTAMP
```

**Relationships**:
- Belongs to `user`
- Belongs to `quiz`

**Computed Attribute**:
```php
public function getPercentageAttribute()
{
    return $this->total > 0 ? round(($this->score / $this->total) * 100, 2) : 0;
}
```

---

## 7. Student Flow

### Course → Lesson → Quiz Flow

The student homepage implements a dynamic, hierarchical navigation system:

#### Step 1: View Courses
- **URL**: `/home`
- **Display**: Horizontally scrollable button list of all courses
- **Action**: Click course button

#### Step 2: View Lessons
- **URL**: `/home?course_id=1`
- **Display**: 
  - Selected course highlighted
  - Lessons for that course appear below in horizontal scroll
- **Action**: Click lesson button

#### Step 3: View Lesson Content
- **URL**: `/home?course_id=1&lesson_id=1`
- **Display**:
  - Lesson title and text content
  - Downloadable media (if available)
  - Quiz section (if available)

#### Step 4: Take Quiz
- **Display**: Multiple-choice questions with radio buttons
- **Action**: Select answers and submit
- **Processing**: POST to `/quiz/{quiz}/submit`

#### Step 5: View Results
- **URL**: Auto-redirect to results page
- **Display**:
  - Score (e.g., 8/10)
  - Percentage (80%)
  - Performance feedback
  - Options to return home or view quiz history

### Profile Management

Students can access `/profile` to:
- Update name and email
- Change password (requires current password)
- View quiz history with:
  - Quiz name and course
  - Score and percentage
  - Date taken
  - Color-coded badges (green ≥80%, blue ≥60%, yellow <60%)

---

## 8. Admin Flow

### Student Management

**Access**: `/admin/students`

**Capabilities**:
1. **View All Students**: Table listing with ID, name, email, join date
2. **Edit Student**: `/admin/students/{id}/edit`
   - Update name and email
   - Reset password (separate form)
3. **Delete Student**: Soft confirmation, removes all associated quiz results

### Course Creation

**Flow**:
1. Navigate to `/admin/courses`
2. Click "Create Course"
3. Fill form:
   - Title (required)
   - Description (optional)
4. Submit → Course created

**Management**:
- Edit existing courses
- Delete courses (cascades to lessons, quizzes, questions)
- View lesson count per course

### Lesson Management

**Flow**:
1. Navigate to `/admin/lessons/create`
2. Fill form:
   - Select course (dropdown)
   - Lesson title (required)
   - Content (textarea)
   - Media file (optional upload)
3. Submit → Lesson created, file stored in `/public/upload`

**Media Upload**:
- Allowed types: JPG, PNG, PDF, MP4, DOC, DOCX
- Max size: 10MB
- Validation in controller
- Stored with timestamp prefix: `{timestamp}_{filename}`

**Edit**:
- Update text fields
- Replace media file (old file deleted)
- Delete lesson (media file removed)

### Quiz Management

**Flow**:
1. Navigate to `/admin/quizzes/create`
2. Select lesson and enter quiz title
3. Submit → Quiz created
4. Redirect to `/admin/quizzes/{quiz}/questions`

**Question Management**:
- **Add Question Form** (left panel):
  - Question text
  - Four options (A, B, C, D)
  - Correct answer dropdown
  - Submit adds question
- **Existing Questions** (right panel):
  - Lists all questions with options
  - Shows correct answer
  - Delete button per question

### Results Viewing

**Access**: `/admin/quiz-results`

**Features**:
- Table of all quiz attempts
- Columns: Student, Quiz, Course, Score, Percentage, Date
- **Filters**:
  - By student (dropdown)
  - By course (dropdown)
  - Submit filter button
- Color-coded percentage badges

---

## 9. File Upload Logic

### Upload Directory Structure

```
/public
  /upload
    1708005123_lecture-notes.pdf
    1708005234_diagram.png
    1708005456_video-tutorial.mp4
```

### Backend Implementation

**Controller**: `LessonController@store`

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'media' => 'nullable|file|mimes:jpg,jpeg,png,pdf,mp4,doc,docx|max:10240',
    ]);

    if ($request->hasFile('media')) {
        $file = $request->file('media');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('upload'), $filename);
        $mediaPath = 'upload/' . $filename;
    }

    Lesson::create([
        'media_path' => $mediaPath ?? null,
        // ... other fields
    ]);
}
```

### Validation Rules

| Type | Extension | MIME Type | Max Size |
|------|-----------|-----------|----------|
| Image | jpg, jpeg, png | image/jpeg, image/png | 10MB |
| Document | pdf, doc, docx | application/pdf, application/msword | 10MB |
| Video | mp4 | video/mp4 | 10MB |

**Security**: Laravel's validation ensures:
- File type checking
- Size limits
- No executable files

### Display Logic

**Student View** (`student/home.blade.php`):

```php
@if($selectedLesson->media_path)
    @php
        $extension = pathinfo($selectedLesson->media_path, PATHINFO_EXTENSION);
    @endphp
    
    @if(in_array($extension, ['jpg', 'jpeg', 'png']))
        <img src="{{ asset($selectedLesson->media_path) }}" class="img-fluid">
    @endif
    
    <a href="{{ asset($selectedLesson->media_path) }}" download>
        📥 Download Media
    </a>
@endif
```

**Features**:
- Images displayed inline
- All files downloadable via link
- `asset()` helper generates correct URL

### File Deletion

When lesson is deleted or media replaced:

```php
if ($lesson->media_path && file_exists(public_path($lesson->media_path))) {
    unlink(public_path($lesson->media_path));
}
```

---

## 10. Quiz Scoring System

### Backend Logic

**Controller**: `QuizController@submit`

```php
public function submit(Request $request, Quiz $quiz)
{
    // Load all questions for this quiz
    $questions = $quiz->questions;
    
    // Initialize counters
    $score = 0;
    $total = $questions->count();
    
    // Check each answer
    foreach ($questions as $question) {
        $userAnswer = $request->input('question_' . $question->id);
        
        if ($userAnswer === $question->correct_answer) {
            $score++;
        }
    }
    
    // Store result
    QuizResult::create([
        'user_id' => Auth::id(),
        'quiz_id' => $quiz->id,
        'score' => $score,
        'total' => $total,
    ]);
    
    // Calculate percentage
    $percentage = round(($score / $total) * 100, 2);
    
    // Return result view
    return view('student.quiz-result', compact('quiz', 'score', 'total', 'percentage'));
}
```

### Frontend Implementation

**Quiz Form** (student/home.blade.php):

```html
<form method="POST" action="{{ route('quiz.submit', $quiz) }}">
    @csrf
    @foreach($quiz->questions as $question)
        <div class="card mb-3">
            <p>{{ $question->question_text }}</p>
            
            <input type="radio" name="question_{{ $question->id }}" value="a" required>
            A. {{ $question->option_a }}
            
            <input type="radio" name="question_{{ $question->id }}" value="b">
            B. {{ $question->option_b }}
            
            <!-- Options C and D ... -->
        </div>
    @endforeach
    
    <button type="submit">Submit Quiz</button>
</form>
```

**Key Points**:
- Each question has unique `name="question_{id}"`
- Radio buttons ensure one answer per question
- `required` attribute prevents submission without answers
- Submitted as POST data array

### Scoring Algorithm

```
For each question:
    IF user_answer == correct_answer THEN
        score += 1
    END IF

percentage = (score / total_questions) * 100
```

### Result Display

```php
@if($percentage >= 80)
    <div class="alert-success">Excellent work!</div>
@elseif($percentage >= 60)
    <div class="alert-info">Good job!</div>
@else
    <div class="alert-warning">Keep studying!</div>
@endif
```

### Database Storage

Each quiz attempt is permanently recorded:

```sql
INSERT INTO quiz_results (user_id, quiz_id, score, total, created_at)
VALUES (5, 12, 8, 10, '2024-02-14 10:30:00');
```

**Benefits**:
- Historical tracking
- Progress monitoring
- Re-take capability (new records created)
- Admin reporting

---

## 11. Security Considerations

### Password Security
- **Hashing**: Bcrypt algorithm via Laravel's `Hash::make()`
- **Never Stored Plain**: Original passwords discarded after hashing
- **Verification**: `Hash::check()` compares without decryption

```php
// Registration
$user->password = Hash::make($request->password);

// Login
if (Hash::check($request->password, $user->password)) {
    // Authenticated
}
```

### Role-Based Access Control
- **Middleware Enforcement**: Every protected route checks role
- **Database-Driven**: Roles stored in DB, not hardcoded
- **Fail-Secure**: Default deny (403) on unauthorized access

### File Upload Validation
- **Type Whitelist**: Only specified MIME types allowed
- **Size Limit**: 10MB maximum
- **Storage Location**: Public directory (safe, no executable permissions)
- **Filename Sanitization**: Timestamp prefix prevents collisions/overwrites

### SQL Injection Prevention
- **Eloquent ORM**: All queries use parameter binding
- **No Raw Queries**: Direct SQL avoided
- **Mass Assignment Protection**: `$fillable` arrays on all models

```php
// Safe (parameterized):
User::where('email', $request->email)->first();

// Unsafe (never used):
DB::select("SELECT * FROM users WHERE email = '$email'");
```

### CSRF Protection
- **Token Validation**: All POST/PUT/DELETE requests require `@csrf` token
- **Automatic**: Laravel middleware validates token
- **Forms**: `@csrf` directive in Blade templates

```html
<form method="POST">
    @csrf  <!-- Required -->
    <!-- form fields -->
</form>
```

### Session Security
- **Regeneration**: Session ID regenerated on login/logout
- **HTTP-Only Cookies**: JavaScript cannot access session cookies
- **Lifetime**: Configurable timeout (default 120 minutes)

### Future Security Enhancements
- Two-factor authentication (2FA)
- Password reset via email
- Account lockout after failed attempts
- Audit logging for admin actions
- Role permissions granularity

---

## 12. Future Improvements

### Feature Enhancements

#### 1. **Pagination**
- Course/lesson lists exceed 20 items
- Admin student table with 100+ records
- Quiz results table

**Implementation**:
```php
$students = User::where('role', 'student')->paginate(15);
```

#### 2. **API Version**
- RESTful API for mobile apps
- JWT authentication
- Endpoints for courses, lessons, quizzes

#### 3. **Notifications**
- Email on quiz completion
- Admin alerts for new registrations
- Course update announcements

**Laravel Notifications**:
```php
$user->notify(new QuizCompletedNotification($result));
```

#### 4. **Certificates**
- Generate PDF certificates on course completion
- Downloadable from student profile
- Unique verification codes

**Package**: `barryvdh/laravel-dompdf`

#### 5. **Advanced Quiz Types**
- True/False questions
- Multiple correct answers
- Fill-in-the-blank
- Essay questions (manual grading)

#### 6. **Learning Paths**
- Sequential course unlocking
- Prerequisites enforcement
- Progress tracking dashboard

#### 7. **Discussion Forums**
- Per-course discussion boards
- Student-to-student interaction
- Instructor moderation

#### 8. **Video Streaming**
- Integrated video player
- Progress tracking (watched vs. unwatched)
- Playback speed controls

**Package**: `videojs` or Laravel `Stream`

#### 9. **Analytics Dashboard**
- Student engagement metrics
- Average quiz scores
- Most popular courses
- Time-to-completion stats

**Charts**: `laravel-charts` or `chartjs`

#### 10. **Mobile App**
- Native iOS/Android apps
- Offline lesson viewing
- Push notifications

---

### Technical Improvements

#### 1. **Caching**
- Cache course lists
- Redis integration
- Query result caching

```php
$courses = Cache::remember('courses', 3600, function() {
    return Course::all();
});
```

#### 2. **File Storage Refactor**
- Move to Laravel Storage facade
- Support S3/cloud storage
- Private file downloads

```php
Storage::disk('s3')->put($path, $file);
```

#### 3. **Testing Suite**
- Unit tests for models
- Feature tests for controllers
- Browser tests with Dusk

```bash
php artisan test
```

#### 4. **Search Functionality**
- Full-text search for courses
- Laravel Scout + Algolia/MeiliSearch
- Autocomplete suggestions

#### 5. **Internationalization (i18n)**
- Multi-language support
- Laravel localization
- RTL language support

```php
__('messages.welcome')
```

---

## Troubleshooting

### Common Issues

**Issue**: White screen after installation
**Solution**: Check `.env` configuration, run `php artisan config:cache`

**Issue**: "Class 'RoleMiddleware' not found"
**Solution**: Register middleware in `bootstrap/app.php` or `Kernel.php`

**Issue**: File upload fails
**Solution**: 
- Check `upload_max_filesize` and `post_max_size` in `php.ini`
- Verify `/public/upload` directory exists and is writable

**Issue**: Database connection error
**Solution**: 
- Ensure MySQL is running in XAMPP
- Verify credentials in `.env`
- Check database exists in phpMyAdmin

**Issue**: 404 on `/admin` routes
**Solution**: 
- Ensure you're logged in as admin
- Check `users.role = 'admin'` in database

---

## Support & Contribution

For issues, questions, or contributions:
- Open issues on GitHub repository
- Submit pull requests with feature additions
- Follow Laravel coding standards

---

## License

This project is open-source software licensed under the MIT license.

---

## Credits

**Developed by**: [Your Name/Team]
**Framework**: Laravel (Taylor Otwell)
**UI**: Bootstrap 5

---

**Last Updated**: February 14, 2026
**Version**: 1.0.0