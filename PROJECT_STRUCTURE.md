# BerryLearn Project Structure

## Directory Tree

```
berrylearn/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminController.php          # Admin dashboard & student mgmt
│   │   │   ├── AdminQuizController.php      # Quiz & question management
│   │   │   ├── AuthController.php           # Login, register, logout
│   │   │   ├── CourseController.php         # Course CRUD
│   │   │   ├── HomeController.php           # Student homepage
│   │   │   ├── LessonController.php         # Lesson CRUD + file uploads
│   │   │   ├── ProfileController.php        # Student profile & password
│   │   │   └── QuizController.php           # Quiz submission & scoring
│   │   └── Middleware/
│   │       └── RoleMiddleware.php           # RBAC enforcement
│   └── Models/
│       ├── Course.php                       # Has many Lessons
│       ├── Lesson.php                       # Belongs to Course, has many Quizzes
│       ├── Question.php                     # Belongs to Quiz
│       ├── Quiz.php                         # Belongs to Lesson, has many Questions
│       ├── QuizResult.php                   # Belongs to User & Quiz
│       └── User.php                         # Role: student/admin
│
├── database/
│   └── migrations/
│       ├── 2024_01_01_000000_create_users_table.php
│       ├── 2024_01_01_000001_create_courses_table.php
│       ├── 2024_01_01_000002_create_lessons_table.php
│       ├── 2024_01_01_000003_create_quizzes_table.php
│       ├── 2024_01_01_000004_create_questions_table.php
│       └── 2024_01_01_000005_create_quiz_results_table.php
│
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php               # Main layout with nav & Bootstrap
│       ├── auth/
│       │   ├── login.blade.php             # Login form
│       │   └── register.blade.php          # Registration form
│       ├── student/
│       │   ├── home.blade.php              # Dynamic course→lesson→quiz flow
│       │   ├── profile.blade.php           # Edit profile & quiz history
│       │   └── quiz-result.blade.php       # Score display after submission
│       └── admin/
│           ├── dashboard.blade.php         # Stats & quick actions
│           ├── quiz-results.blade.php      # Filterable results table
│           ├── courses/
│           │   ├── index.blade.php         # List courses
│           │   ├── create.blade.php        # Create course form
│           │   └── edit.blade.php          # Edit course form
│           ├── lessons/
│           │   ├── index.blade.php         # List lessons
│           │   ├── create.blade.php        # Create lesson + media upload
│           │   └── edit.blade.php          # Edit lesson
│           ├── quizzes/
│           │   ├── index.blade.php         # List quizzes
│           │   ├── create.blade.php        # Create quiz
│           │   ├── edit.blade.php          # Edit quiz
│           │   └── questions.blade.php     # Add/manage questions
│           └── students/
│               ├── index.blade.php         # List students
│               └── edit.blade.php          # Edit student + reset password
│
├── routes/
│   └── web.php                             # All route definitions with RBAC
│
├── public/
│   ├── index.php                           # Application entry point
│   └── upload/                             # Lesson media storage
│       └── .gitkeep
│
├── .env.example                            # Environment configuration template
├── .gitignore                              # Git ignore rules
├── composer.json                           # PHP dependencies
├── artisan                                 # Laravel CLI tool
├── README.md                               # Full documentation (16 pages)
├── INSTALLATION.md                         # Quick setup guide
└── PROJECT_STRUCTURE.md                    # This file
```

## Key Architectural Patterns

### MVC Structure
- **Models**: Eloquent ORM with relationships
- **Views**: Blade templates with Bootstrap 5
- **Controllers**: Single responsibility, thin controllers

### Authentication Flow
1. `AuthController` handles login/register
2. Session-based authentication
3. `RoleMiddleware` checks user role on protected routes
4. Redirects to role-appropriate dashboard

### Student User Flow
```
Login → /home → Select Course → Select Lesson → View Content → Take Quiz → See Results
                                                              ↓
                                                         /profile (history)
```

### Admin User Flow
```
Login → /admin → Dashboard → Manage:
                             ├─ Students (CRUD, reset password)
                             ├─ Courses (CRUD)
                             ├─ Lessons (CRUD + media uploads)
                             ├─ Quizzes (CRUD + questions)
                             └─ Results (view, filter)
```

### File Upload Architecture
```
Admin uploads media
       ↓
LessonController validates
       ↓
File moved to /public/upload/{timestamp}_{filename}
       ↓
Relative path stored in lessons.media_path
       ↓
Student views lesson
       ↓
asset() helper generates URL
       ↓
Download link displayed
```

### Quiz Scoring Architecture
```
Student submits form
       ↓
QuizController receives POST with question_N: answer
       ↓
Loop through quiz->questions
       ↓
Compare user_answer == correct_answer
       ↓
Increment score if match
       ↓
Calculate percentage
       ↓
Store in quiz_results table
       ↓
Display results page
```

## Database Relationships

```
User (1) ────────────────────── (*) QuizResult
Course (1) ─────────────────── (*) Lesson
Lesson (1) ─────────────────── (*) Quiz
Quiz (1) ───────────────────── (*) Question
Quiz (1) ───────────────────── (*) QuizResult
```

**Cascade Deletes**:
- Delete Course → Deletes Lessons → Deletes Quizzes → Deletes Questions
- Delete User → Deletes QuizResults
- Delete Lesson (manual) → Deletes media file from disk

## Security Layers

1. **Authentication**: Session-based (Laravel default)
2. **RBAC**: RoleMiddleware checks `users.role` column
3. **CSRF**: @csrf tokens on all forms
4. **Password Hashing**: Bcrypt via Hash facade
5. **SQL Injection**: Eloquent parameterized queries
6. **File Validation**: MIME type & size checks
7. **XSS Protection**: Blade `{{ }}` auto-escapes output

## Extensibility Points

### Adding New Roles
1. Modify `users.role` enum in migration
2. Create new middleware or extend RoleMiddleware
3. Add routes with role protection
4. Create role-specific views

### Adding Quiz Types
1. Create new question types table
2. Extend Question model (polymorphic)
3. Update QuizController scoring logic
4. Add frontend input types

### Adding Notifications
1. `composer require laravel/notifications`
2. Create notification classes
3. Trigger on events (quiz complete, etc.)
4. Configure mail/database/slack channels

### API Addition
1. Create API routes in `routes/api.php`
2. Use API resources for JSON responses
3. Implement JWT authentication
4. Version with `/api/v1` prefix

## Performance Considerations

### Current Bottlenecks
- No pagination on large result sets
- N+1 queries without eager loading
- No caching layer

### Recommended Optimizations
```php
// Eager loading
$courses = Course::with('lessons')->get();

// Pagination
$students = User::paginate(20);

// Query result caching
Cache::remember('courses', 3600, fn() => Course::all());
```

## Testing Strategy

### Unit Tests
- Model relationships
- Middleware logic
- Scoring algorithm

### Feature Tests
- Authentication flows
- CRUD operations
- File uploads
- Quiz submission

### Browser Tests (Dusk)
- Full user journeys
- Student quiz flow
- Admin content creation

## Deployment Checklist

- [ ] Set `APP_ENV=production` in .env
- [ ] Set `APP_DEBUG=false`
- [ ] Generate new `APP_KEY`
- [ ] Configure production database
- [ ] Set up file storage (S3/cloud)
- [ ] Enable HTTPS
- [ ] Configure mail server
- [ ] Set up backups
- [ ] Configure caching (Redis)
- [ ] Set up queue workers
- [ ] Enable error monitoring (Sentry)

## Contributing Guidelines

1. Follow Laravel coding standards
2. Write tests for new features
3. Update README for API changes
4. Use meaningful commit messages
5. Create feature branches
6. Submit pull requests with description

## License

MIT License - See LICENSE file for details
