<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BerryLearn LMS')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Section: Shared design tokens. */
        :root {
            --berry-primary: #0d6efd;
            --berry-success: #198754;
            --berry-info: #0dcaf0;
            --berry-warning: #ffc107;
            --berry-surface: #ffffff;
            --berry-border: rgba(13, 110, 253, 0.12);
            --berry-shadow: 0 16px 40px rgba(13, 110, 253, 0.08);
        }

        /* Section: Page shell and top navigation. */
        body {
            min-height: 100vh;
            background:
                linear-gradient(180deg, rgba(13, 110, 253, 0.08) 0, rgba(13, 110, 253, 0.03) 220px, #f8f9fa 220px),
                #f8f9fa;
            color: #1f2937;
        }
        .page-shell {
            padding-bottom: 3rem;
        }
        .navbar {
            box-shadow: 0 12px 30px rgba(13, 110, 253, 0.18);
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.01em;
        }
        .navbar .nav-link,
        .navbar .btn-link.nav-link {
            color: rgba(255, 255, 255, 0.82);
            border-radius: 999px;
            padding: 0.45rem 0.8rem;
            transition: background-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
        }
        .navbar .nav-link:hover,
        .navbar .btn-link.nav-link:hover,
        .navbar .nav-link.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.14);
            transform: translateY(-1px);
            text-decoration: none;
        }
        .user-pill {
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
            font-weight: 600;
        }

        /* Section: Shared cards, statistics, and actions. */
        .section-card,
        .card {
            border: 0;
            border-radius: 1rem;
            box-shadow: var(--berry-shadow);
        }
        .section-card .card-header,
        .card .card-header {
            background: #fff;
            border-bottom: 1px solid var(--berry-border);
        }
        .interactive-card {
            transition: transform 0.22s ease, box-shadow 0.22s ease;
        }
        .interactive-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 34px rgba(13, 110, 253, 0.14);
        }
        .stat-card .card-body {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }
        .stat-meta {
            margin: 0;
            opacity: 0.88;
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .stat-value {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1;
        }
        .action-chip {
            border-radius: 999px;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        /* Section: Search and filtering controls. */
        .soft-search {
            border: 1px solid var(--berry-border);
            border-radius: 999px;
            padding: 0.8rem 1rem 0.8rem 2.75rem;
            box-shadow: 0 10px 24px rgba(13, 110, 253, 0.06);
        }
        .soft-search:focus {
            border-color: rgba(13, 110, 253, 0.35);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.12);
        }
        .search-wrap {
            position: relative;
        }
        .search-wrap .search-label {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 0.85rem;
            font-weight: 600;
            pointer-events: none;
        }

        /* Section: Scrollable pills, course cards, and data tables. */
        .horizontal-scroll {
            display: flex;
            overflow-x: auto;
            gap: 10px;
            padding: 10px 0;
            white-space: nowrap;
            scrollbar-width: thin;
        }
        .horizontal-scroll::-webkit-scrollbar {
            height: 8px;
        }
        .horizontal-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .horizontal-scroll::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        .horizontal-scroll .btn {
            border-radius: 999px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .horizontal-scroll .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 18px rgba(13, 110, 253, 0.12);
        }
        .lesson-chip-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        .lesson-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 190px;
        }
        .course-browser-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }
        .course-card {
            border: 1px solid var(--berry-border);
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.94);
            box-shadow: 0 14px 28px rgba(13, 110, 253, 0.06);
            transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
        }
        .course-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 34px rgba(13, 110, 253, 0.12);
            border-color: rgba(13, 110, 253, 0.22);
        }
        .course-card.is-selected {
            border-color: rgba(13, 110, 253, 0.42);
            box-shadow: 0 18px 34px rgba(13, 110, 253, 0.14);
        }
        .course-card-body {
            display: flex;
            flex-direction: column;
            gap: 0.9rem;
            height: 100%;
            padding: 1rem;
        }
        .course-card-title {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
            color: #1f2937;
        }
        .course-card-meta {
            margin: 0;
            color: #6c757d;
            font-size: 0.92rem;
        }
        .course-card-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: auto;
        }
        .table-responsive {
            border-radius: 1rem;
        }
        .table > :not(caption) > * > * {
            padding-top: 0.9rem;
            padding-bottom: 0.9rem;
        }
        .table-hover tbody tr {
            transition: background-color 0.2s ease, transform 0.2s ease;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.04);
        }
        .filter-empty {
            border: 1px dashed rgba(13, 110, 253, 0.24);
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.72);
        }

        /* Section: Learning media and quiz blocks. */
        .media-container {
            max-width: 100%;
            margin: 20px 0;
        }
        .media-container img {
            max-width: 100%;
            height: auto;
            border-radius: 1rem;
            box-shadow: 0 14px 30px rgba(13, 110, 253, 0.12);
        }
        .media-container video {
            width: 100%;
            border-radius: 1rem;
            box-shadow: 0 14px 30px rgba(13, 110, 253, 0.12);
            background: #000;
        }
        .resource-card {
            border: 1px solid rgba(13, 110, 253, 0.12);
            border-radius: 1rem;
            background: rgba(248, 249, 250, 0.8);
        }
        .quiz-question-card {
            border: 1px solid rgba(13, 110, 253, 0.08);
        }
        .quiz-question-card .form-check {
            padding: 0.55rem 0.8rem 0.55rem 2rem;
            border-radius: 0.75rem;
            transition: background-color 0.2s ease;
        }
        .quiz-question-card .form-check:hover {
            background: rgba(13, 110, 253, 0.04);
        }
        .badge-soft {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            background: rgba(13, 110, 253, 0.08);
            color: var(--berry-primary);
            font-weight: 600;
        }
        .reveal-up,
        .reveal-up.in-view {
            opacity: 1;
            transform: translateY(0);
        }
        body.motion-ready .reveal-up {
            opacity: 0;
            transform: translateY(14px);
            transition: opacity 0.28s ease, transform 0.28s ease;
        }
        body.motion-ready .reveal-up.in-view {
            opacity: 1;
            transform: translateY(0);
        }
        @media (max-width: 991.98px) {
            .navbar .nav-link,
            .navbar .btn-link.nav-link {
                margin-top: 0.25rem;
            }
        }
        @media (max-width: 767.98px) {
            .lesson-chip-grid {
                display: grid;
                grid-template-columns: 1fr;
                white-space: normal;
                overflow: visible;
            }
            .lesson-chip {
                width: 100%;
                min-width: 0;
            }
        }
    </style>
</head>
<body>
    @php($user = auth()->user())
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ $user && $user->isAdmin() ? route('admin.dashboard') : route('home') }}">
                  BerryLearn
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        @if($user->isStudent())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}" href="{{ route('profile') }}">Profile</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.students*') ? 'active' : '' }}" href="{{ route('admin.students') }}">Students</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.courses*') ? 'active' : '' }}" href="{{ route('admin.courses') }}">Courses</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.lessons*') ? 'active' : '' }}" href="{{ route('admin.lessons') }}">Lessons</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.quizzes*') ? 'active' : '' }}" href="{{ route('admin.quizzes') }}">Quizzes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.quiz-results') ? 'active' : '' }}" href="{{ route('admin.quiz-results') }}">Results</a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <span class="nav-link user-pill">{{ $user->name }}</span>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link">Logout</button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-4 page-shell">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Section: Auto-dismiss feedback alerts after a short pause.
            document.querySelectorAll('.alert').forEach(function (alert) {
                window.setTimeout(function () {
                    if (!alert.classList.contains('show')) {
                        return;
                    }
                    bootstrap.Alert.getOrCreateInstance(alert).close();
                }, 4000);
            });

            // Section: Fade content in as it enters the viewport.
            const revealItems = document.querySelectorAll('.reveal-up');
            if ('IntersectionObserver' in window && revealItems.length > 0) {
                document.body.classList.add('motion-ready');
                const revealObserver = new IntersectionObserver(function (entries, observer) {
                    entries.forEach(function (entry) {
                        if (!entry.isIntersecting) {
                            return;
                        }
                        entry.target.classList.add('in-view');
                        observer.unobserve(entry.target);
                    });
                }, { threshold: 0.12 });

                revealItems.forEach(function (item) {
                    revealObserver.observe(item);
                });
            } else {
                revealItems.forEach(function (item) {
                    item.classList.add('in-view');
                });
            }

            // Section: Count up dashboard metrics for a livelier feel.
            document.querySelectorAll('[data-countup]').forEach(function (counter) {
                const target = Number(counter.getAttribute('data-countup'));
                if (!Number.isFinite(target)) {
                    return;
                }

                const duration = 700;
                const startTime = performance.now();

                const step = function (now) {
                    const progress = Math.min((now - startTime) / duration, 1);
                    counter.textContent = Math.floor(progress * target).toLocaleString();
                    if (progress < 1) {
                        window.requestAnimationFrame(step);
                    } else {
                        counter.textContent = target.toLocaleString();
                    }
                };

                window.requestAnimationFrame(step);
            });

            // Section: Filter interactive lists and show empty states when needed.
            document.querySelectorAll('[data-filter-input]').forEach(function (input) {
                const targetSelector = input.getAttribute('data-filter-input');
                const target = document.querySelector(targetSelector);
                const emptyTarget = document.querySelector(input.getAttribute('data-empty-target'));

                if (!target) {
                    return;
                }

                const applyFilter = function () {
                    const query = input.value.trim().toLowerCase();
                    let visibleCount = 0;

                    target.querySelectorAll('[data-filter-item]').forEach(function (item) {
                        const haystack = (item.getAttribute('data-filter-text') || item.textContent || '').toLowerCase();
                        const shouldShow = !query || haystack.includes(query);

                        item.classList.toggle('d-none', !shouldShow);

                        if (shouldShow) {
                            visibleCount += 1;
                        }
                    });

                    if (emptyTarget) {
                        emptyTarget.classList.toggle('d-none', visibleCount !== 0);
                    }
                };

                input.addEventListener('input', applyFilter);
                applyFilter();
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
