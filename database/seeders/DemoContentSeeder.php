<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        $users = [];

        foreach ($this->users() as $userData) {
            $users[$userData['email']] = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']),
                    'role' => $userData['role'],
                ]
            );
        }

        $quizzes = [];

        foreach ($this->courses() as $courseData) {
            $course = Course::updateOrCreate(
                ['title' => $courseData['title']],
                ['description' => $courseData['description']]
            );

            foreach ($courseData['lessons'] as $lessonData) {
                $lesson = Lesson::updateOrCreate(
                    [
                        'course_id' => $course->id,
                        'title' => $lessonData['title'],
                    ],
                    [
                        'content' => $lessonData['content'],
                        'media_path' => null,
                    ]
                );

                $quiz = Quiz::updateOrCreate(
                    [
                        'lesson_id' => $lesson->id,
                        'title' => $lessonData['quiz']['title'],
                    ],
                    []
                );

                $quizzes[$quiz->title] = $quiz;

                foreach ($lessonData['quiz']['questions'] as $questionData) {
                    Question::updateOrCreate(
                        [
                            'quiz_id' => $quiz->id,
                            'question_text' => $questionData['question_text'],
                        ],
                        [
                            'option_a' => $questionData['option_a'],
                            'option_b' => $questionData['option_b'],
                            'option_c' => $questionData['option_c'],
                            'option_d' => $questionData['option_d'],
                            'correct_answer' => $questionData['correct_answer'],
                        ]
                    );
                }
            }
        }

        foreach ($this->quizResults() as $resultData) {
            $user = $users[$resultData['email']] ?? null;
            $quiz = $quizzes[$resultData['quiz']] ?? null;

            if (!$user || !$quiz) {
                continue;
            }

            QuizResult::firstOrCreate([
                'user_id' => $user->id,
                'quiz_id' => $quiz->id,
                'score' => $resultData['score'],
                'total' => $resultData['total'],
            ]);
        }
    }

    private function users(): array
    {
        return [
            [
                'name' => 'Admin User',
                'email' => 'admin@berrylearn.com',
                'password' => 'password123',
                'role' => 'admin',
            ],
            [
                'name' => 'Alice Johnson',
                'email' => 'alice@student.com',
                'password' => 'student123',
                'role' => 'student',
            ],
            [
                'name' => 'Brian Smith',
                'email' => 'brian@student.com',
                'password' => 'student123',
                'role' => 'student',
            ],
            [
                'name' => 'Chioma Davis',
                'email' => 'chioma@student.com',
                'password' => 'student123',
                'role' => 'student',
            ],
        ];
    }

    private function courses(): array
    {
        return array_merge([
            [
                'title' => 'Introduction to Computer Basics',
                'description' => 'Learn the basics of computer hardware, software, files, and safe computer use.',
                'lessons' => [
                    [
                        'title' => 'Understanding Computer Hardware',
                        'content' => 'This lesson explains input devices, output devices, storage, memory, and the CPU.',
                        'quiz' => [
                            'title' => 'Hardware Basics Quiz',
                            'questions' => [
                                [
                                    'question_text' => 'Which part of the computer performs calculations?',
                                    'option_a' => 'Monitor',
                                    'option_b' => 'Keyboard',
                                    'option_c' => 'CPU',
                                    'option_d' => 'Speaker',
                                    'correct_answer' => 'c',
                                ],
                                [
                                    'question_text' => 'Which device is mainly used for pointing and clicking?',
                                    'option_a' => 'Mouse',
                                    'option_b' => 'Printer',
                                    'option_c' => 'Projector',
                                    'option_d' => 'Scanner',
                                    'correct_answer' => 'a',
                                ],
                                [
                                    'question_text' => 'Which of these is a storage device?',
                                    'option_a' => 'RAM',
                                    'option_b' => 'Hard drive',
                                    'option_c' => 'GPU',
                                    'option_d' => 'Motherboard',
                                    'correct_answer' => 'b',
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Operating Systems and File Management',
                        'content' => 'This lesson covers the purpose of operating systems, folders, files, and common file operations.',
                        'quiz' => [
                            'title' => 'OS and Files Quiz',
                            'questions' => [
                                [
                                    'question_text' => 'Which software manages hardware and files?',
                                    'option_a' => 'Browser',
                                    'option_b' => 'Operating system',
                                    'option_c' => 'Text editor',
                                    'option_d' => 'Spreadsheet',
                                    'correct_answer' => 'b',
                                ],
                                [
                                    'question_text' => 'What is a folder mainly used for?',
                                    'option_a' => 'To browse the internet',
                                    'option_b' => 'To organize files',
                                    'option_c' => 'To print documents',
                                    'option_d' => 'To install apps',
                                    'correct_answer' => 'b',
                                ],
                                [
                                    'question_text' => 'Which action moves a file to the trash?',
                                    'option_a' => 'Rename',
                                    'option_b' => 'Delete',
                                    'option_c' => 'Download',
                                    'option_d' => 'Refresh',
                                    'correct_answer' => 'b',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Web Development Fundamentals',
                'description' => 'Learn how websites are built with HTML and CSS.',
                'lessons' => [
                    [
                        'title' => 'HTML Page Structure',
                        'content' => 'This lesson introduces HTML tags, headings, paragraphs, links, and lists.',
                        'quiz' => [
                            'title' => 'HTML Fundamentals Quiz',
                            'questions' => [
                                [
                                    'question_text' => 'Which tag creates the largest heading in HTML?',
                                    'option_a' => '<h1>',
                                    'option_b' => '<p>',
                                    'option_c' => '<div>',
                                    'option_d' => '<li>',
                                    'correct_answer' => 'a',
                                ],
                                [
                                    'question_text' => 'Which tag is used to create a hyperlink?',
                                    'option_a' => '<img>',
                                    'option_b' => '<a>',
                                    'option_c' => '<ul>',
                                    'option_d' => '<table>',
                                    'correct_answer' => 'b',
                                ],
                                [
                                    'question_text' => 'Which tag is commonly used for a paragraph?',
                                    'option_a' => '<p>',
                                    'option_b' => '<h3>',
                                    'option_c' => '<span>',
                                    'option_d' => '<ol>',
                                    'correct_answer' => 'a',
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => 'CSS Styling Basics',
                        'content' => 'This lesson covers selectors, colors, spacing, borders, and typography basics.',
                        'quiz' => [
                            'title' => 'CSS Basics Quiz',
                            'questions' => [
                                [
                                    'question_text' => 'Which CSS property changes text color?',
                                    'option_a' => 'background-color',
                                    'option_b' => 'font-style',
                                    'option_c' => 'color',
                                    'option_d' => 'border',
                                    'correct_answer' => 'c',
                                ],
                                [
                                    'question_text' => 'Which CSS property adds space inside an element?',
                                    'option_a' => 'margin',
                                    'option_b' => 'padding',
                                    'option_c' => 'display',
                                    'option_d' => 'position',
                                    'correct_answer' => 'b',
                                ],
                                [
                                    'question_text' => 'Which symbol starts a class selector in CSS?',
                                    'option_a' => '#',
                                    'option_b' => '.',
                                    'option_c' => '*',
                                    'option_d' => '@',
                                    'correct_answer' => 'b',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'PHP Programming for Beginners',
                'description' => 'Understand PHP syntax, variables, forms, and validation.',
                'lessons' => [
                    [
                        'title' => 'PHP Variables and Data Types',
                        'content' => 'This lesson explains variables, strings, integers, booleans, and arrays in PHP.',
                        'quiz' => [
                            'title' => 'PHP Variables Quiz',
                            'questions' => [
                                [
                                    'question_text' => 'Which symbol starts a variable in PHP?',
                                    'option_a' => '#',
                                    'option_b' => '$',
                                    'option_c' => '%',
                                    'option_d' => '&',
                                    'correct_answer' => 'b',
                                ],
                                [
                                    'question_text' => 'Which of these is a boolean value?',
                                    'option_a' => 'hello',
                                    'option_b' => '42',
                                    'option_c' => 'true',
                                    'option_d' => 'array',
                                    'correct_answer' => 'c',
                                ],
                                [
                                    'question_text' => 'Which function displays output in PHP?',
                                    'option_a' => 'print',
                                    'option_b' => 'input',
                                    'option_c' => 'select',
                                    'option_d' => 'returnValue',
                                    'correct_answer' => 'a',
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => 'PHP Forms and Validation',
                        'content' => 'This lesson explains handling form input, request data, and validation rules.',
                        'quiz' => [
                            'title' => 'Form Validation Quiz',
                            'questions' => [
                                [
                                    'question_text' => 'Which Laravel method is used to validate request data?',
                                    'option_a' => 'check()',
                                    'option_b' => 'validate()',
                                    'option_c' => 'save()',
                                    'option_d' => 'bind()',
                                    'correct_answer' => 'b',
                                ],
                                [
                                    'question_text' => 'Which HTTP method is commonly used for submitting forms?',
                                    'option_a' => 'GET only',
                                    'option_b' => 'POST only',
                                    'option_c' => 'POST or PUT depending on action',
                                    'option_d' => 'DELETE only',
                                    'correct_answer' => 'c',
                                ],
                                [
                                    'question_text' => 'What does validation help prevent?',
                                    'option_a' => 'Styled pages',
                                    'option_b' => 'Bad or missing input',
                                    'option_c' => 'Faster internet',
                                    'option_d' => 'Server shutdown',
                                    'correct_answer' => 'b',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Database Design with MySQL',
                'description' => 'Study relational databases, SQL queries, and table relationships.',
                'lessons' => [
                    [
                        'title' => 'Relational Database Concepts',
                        'content' => 'This lesson introduces tables, rows, primary keys, and foreign keys.',
                        'quiz' => [
                            'title' => 'Database Concepts Quiz',
                            'questions' => [
                                [
                                    'question_text' => 'What uniquely identifies each row in a table?',
                                    'option_a' => 'Foreign key',
                                    'option_b' => 'Primary key',
                                    'option_c' => 'Index page',
                                    'option_d' => 'Column name',
                                    'correct_answer' => 'b',
                                ],
                                [
                                    'question_text' => 'A foreign key usually points to what?',
                                    'option_a' => 'Another database server',
                                    'option_b' => 'A CSS file',
                                    'option_c' => 'A primary key in another table',
                                    'option_d' => 'A random value',
                                    'correct_answer' => 'c',
                                ],
                                [
                                    'question_text' => 'Which structure stores records in a relational database?',
                                    'option_a' => 'Slide',
                                    'option_b' => 'Table',
                                    'option_c' => 'Canvas',
                                    'option_d' => 'Route',
                                    'correct_answer' => 'b',
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Writing SQL Queries',
                        'content' => 'This lesson covers SELECT, WHERE, ORDER BY, INSERT, UPDATE, and DELETE.',
                        'quiz' => [
                            'title' => 'SQL Queries Quiz',
                            'questions' => [
                                [
                                    'question_text' => 'Which SQL statement retrieves data?',
                                    'option_a' => 'SELECT',
                                    'option_b' => 'INSERT',
                                    'option_c' => 'UPDATE',
                                    'option_d' => 'DELETE',
                                    'correct_answer' => 'a',
                                ],
                                [
                                    'question_text' => 'Which clause filters rows in SQL?',
                                    'option_a' => 'ORDER BY',
                                    'option_b' => 'GROUP BY',
                                    'option_c' => 'WHERE',
                                    'option_d' => 'LIMITS',
                                    'correct_answer' => 'c',
                                ],
                                [
                                    'question_text' => 'Which SQL statement adds a new row?',
                                    'option_a' => 'CREATE',
                                    'option_b' => 'INSERT',
                                    'option_c' => 'ALTER',
                                    'option_d' => 'SHOW',
                                    'correct_answer' => 'b',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $this->generatedCourses());
    }

    private function quizResults(): array
    {
        return [
            ['email' => 'alice@student.com', 'quiz' => 'Hardware Basics Quiz', 'score' => 3, 'total' => 3],
            ['email' => 'alice@student.com', 'quiz' => 'HTML Fundamentals Quiz', 'score' => 2, 'total' => 3],
            ['email' => 'brian@student.com', 'quiz' => 'PHP Variables Quiz', 'score' => 3, 'total' => 3],
            ['email' => 'brian@student.com', 'quiz' => 'SQL Queries Quiz', 'score' => 2, 'total' => 3],
            ['email' => 'chioma@student.com', 'quiz' => 'OS and Files Quiz', 'score' => 1, 'total' => 3],
            ['email' => 'chioma@student.com', 'quiz' => 'Database Concepts Quiz', 'score' => 3, 'total' => 3],
        ];
    }

    private function generatedCourses(): array
    {
        $topics = [
            'Digital Literacy',
            'Keyboarding Skills',
            'Microsoft Word Basics',
            'Spreadsheet Foundations',
            'Presentation Design',
            'Internet Research Skills',
            'Email Communication',
            'Online Collaboration',
            'Graphic Design Basics',
            'Video Editing Fundamentals',
            'Social Media Strategy',
            'Content Writing Basics',
            'Business Communication',
            'Public Speaking',
            'Project Planning',
            'Team Leadership',
            'Critical Thinking',
            'Problem Solving',
            'Time Management',
            'Study Skills',
            'Financial Literacy',
            'Entrepreneurship Basics',
            'Customer Service',
            'Sales Fundamentals',
            'Marketing Principles',
            'Branding Basics',
            'Data Analysis Basics',
            'Statistics Foundations',
            'Artificial Intelligence Awareness',
            'Cloud Computing Basics',
            'Networking Fundamentals',
            'Cyber Hygiene Practices',
            'Operating System Navigation',
            'Mobile Productivity',
            'UI Design Basics',
            'UX Research Basics',
            'Product Management',
            'Agile Fundamentals',
            'Software Testing Basics',
            'Version Control Essentials',
            'API Fundamentals',
            'Technical Writing',
            'Career Development',
            'Interview Preparation',
            'Resume Building',
            'Freelancing Essentials',
            'E-Commerce Basics',
            'Search Engine Optimization',
            'Personal Productivity Systems',
            'Innovation and Creativity',
        ];

        $generatedCourses = [];

        foreach ($topics as $index => $topic) {
            $generatedCourses[] = $this->buildGeneratedCourse($topic, $index + 1);
        }

        return $generatedCourses;
    }

    private function buildGeneratedCourse(string $topic, int $position): array
    {
        $courseTitle = $topic . ' Accelerator';
        $lessonBlueprints = [
            [
                'lesson_title' => $topic . ' Essentials',
                'quiz_title' => $topic . ' Checkpoint Quiz',
                'focus' => 'core workflow, key vocabulary, and practical habits',
            ],
            [
                'lesson_title' => $topic . ' Guided Workflow',
                'quiz_title' => $topic . ' Guided Workflow Quiz',
                'focus' => 'repeatable steps, decision points, and a clean working process',
            ],
            [
                'lesson_title' => $topic . ' Applied Practice',
                'quiz_title' => $topic . ' Applied Practice Quiz',
                'focus' => 'real examples, hands-on application, and confidence-building reps',
            ],
            [
                'lesson_title' => $topic . ' Troubleshooting and Review',
                'quiz_title' => $topic . ' Troubleshooting Quiz',
                'focus' => 'common mistakes, review strategies, and correction techniques',
            ],
            [
                'lesson_title' => $topic . ' Mastery Sprint',
                'quiz_title' => $topic . ' Mastery Sprint Quiz',
                'focus' => 'speed, confidence, and applying the topic independently',
            ],
        ];

        return [
            'title' => $courseTitle,
            'description' => 'A focused learning path for building confidence in ' . $topic . ' through five practical lessons and short checkpoint quizzes.',
            'lessons' => array_map(
                fn (array $lessonBlueprint, int $lessonNumber) => [
                    'title' => $lessonBlueprint['lesson_title'],
                    'content' => 'Lesson ' . $lessonNumber . ' in this course focuses on ' . $lessonBlueprint['focus'] . ' used in ' . $topic . '. Work through the examples carefully, practice the pattern being taught, and use the quiz to confirm what you understand before moving to the next lesson.',
                    'quiz' => [
                        'title' => $lessonBlueprint['quiz_title'],
                        'questions' => [
                            [
                                'question_text' => 'What is the main goal of the "' . $lessonBlueprint['lesson_title'] . '" lesson in ' . $topic . '?',
                                'option_a' => 'Build practical understanding through guided steps',
                                'option_b' => 'Memorize random facts without practice',
                                'option_c' => 'Avoid structure and skip review',
                                'option_d' => 'Focus only on theory with no application',
                                'correct_answer' => 'a',
                            ],
                            [
                                'question_text' => 'Which habit best supports progress during "' . $lessonBlueprint['lesson_title'] . '"?',
                                'option_a' => 'Ignoring feedback',
                                'option_b' => 'Practicing consistently and reviewing key ideas',
                                'option_c' => 'Rushing without understanding',
                                'option_d' => 'Skipping the basics',
                                'correct_answer' => 'b',
                            ],
                            [
                                'question_text' => 'When starting a task related to "' . $lessonBlueprint['lesson_title'] . '", what should you do first?',
                                'option_a' => 'Guess and hope for the best',
                                'option_b' => 'Copy someone else without thinking',
                                'option_c' => 'Understand the objective and follow a clear process',
                                'option_d' => 'Avoid planning completely',
                                'correct_answer' => 'c',
                            ],
                        ],
                    ],
                ],
                $lessonBlueprints,
                range(1, count($lessonBlueprints))
            ),
        ];
    }
}
