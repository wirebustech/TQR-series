<?php
session_start();

$pageTitle = 'Webinar Details - TQRS';
$pageDescription = 'Join live webinars on qualitative research methodologies and connect with experts in the field.';

include_once __DIR__ . '/includes/header.php';

$texts = [
    'webinarDetails' => 'Webinar Details',
    'liveNow' => 'LIVE NOW',
    'upcoming' => 'Upcoming',
    'past' => 'Past',
    'register' => 'Register',
    'registered' => 'Registered',
    'joinNow' => 'Join Now',
    'watchRecording' => 'Watch Recording',
    'duration' => 'Duration',
    'date' => 'Date',
    'time' => 'Time',
    'timezone' => 'Timezone',
    'speaker' => 'Speaker',
    'speakers' => 'Speakers',
    'category' => 'Category',
    'level' => 'Level',
    'beginner' => 'Beginner',
    'intermediate' => 'Intermediate',
    'advanced' => 'Advanced',
    'description' => 'Description',
    'agenda' => 'Agenda',
    'learningObjectives' => 'Learning Objectives',
    'prerequisites' => 'Prerequisites',
    'materials' => 'Materials',
    'certificate' => 'Certificate',
    'certificateAvailable' => 'Certificate available upon completion',
    'chat' => 'Chat',
    'questions' => 'Questions',
    'participants' => 'Participants',
    'online' => 'Online',
    'sendMessage' => 'Send Message',
    'messagePlaceholder' => 'Type your message...',
    'askQuestion' => 'Ask a Question',
    'questionPlaceholder' => 'Type your question...',
    'raiseHand' => 'Raise Hand',
    'handRaised' => 'Hand Raised',
    'mute' => 'Mute',
    'unmute' => 'Unmute',
    'video' => 'Video',
    'screenShare' => 'Screen Share',
    'fullscreen' => 'Fullscreen',
    'settings' => 'Settings',
    'leaveWebinar' => 'Leave Webinar',
    'confirmLeave' => 'Are you sure you want to leave the webinar?',
    'yes' => 'Yes',
    'no' => 'No',
    'webinarEnded' => 'Webinar has ended',
    'webinarStarting' => 'Webinar starting soon',
    'countdown' => 'Countdown',
    'days' => 'days',
    'hours' => 'hours',
    'minutes' => 'minutes',
    'seconds' => 'seconds',
    'reminder' => 'Set Reminder',
    'reminderSet' => 'Reminder Set',
    'share' => 'Share',
    'downloadSlides' => 'Download Slides',
    'resources' => 'Resources',
    'relatedWebinars' => 'Related Webinars',
    'noRelatedWebinars' => 'No related webinars found',
    'loginToRegister' => 'Please log in to register for this webinar',
    'loginBtn' => 'Log In',
    'registerBtn' => 'Register',
    'registrationClosed' => 'Registration is closed for this webinar',
    'webinarFull' => 'This webinar is full',
    'waitlist' => 'Join Waitlist',
    'webinarCancelled' => 'This webinar has been cancelled',
    'webinarPostponed' => 'This webinar has been postponed',
    'newDate' => 'New Date',
    'tba' => 'TBA'
];
if ($lang !== 'en') {
    foreach ($texts as $k => $v) {
        $texts[$k] = translateText($v, $lang, 'en');
    }
}

// Get webinar ID from URL
$webinarId = intval($_GET['id'] ?? 1);

// Mock webinar data
$webinars = [
    1 => [
        'id' => 1,
        'title' => 'Advanced Grounded Theory Methodology',
        'description' => 'Explore advanced techniques in grounded theory methodology for qualitative research. This comprehensive webinar will cover theoretical sampling, constant comparative analysis, and advanced coding techniques.',
        'long_description' => '
            <h3>Webinar Overview</h3>
            <p>This advanced webinar is designed for researchers who have a basic understanding of grounded theory and want to deepen their knowledge and skills. We will explore sophisticated techniques that can enhance the quality and rigor of your grounded theory research.</p>
            
            <h3>What You Will Learn</h3>
            <ul>
                <li>Advanced theoretical sampling strategies</li>
                <li>Sophisticated coding techniques and memo writing</li>
                <li>Integration of multiple data sources</li>
                <li>Quality criteria and validation strategies</li>
                <li>Common pitfalls and how to avoid them</li>
            </ul>
            
            <h3>Who Should Attend</h3>
            <p>This webinar is ideal for:</p>
            <ul>
                <li>Graduate students conducting grounded theory research</li>
                <li>Academic researchers using grounded theory methodology</li>
                <li>Qualitative researchers seeking to enhance their skills</li>
                <li>Anyone with basic grounded theory knowledge</li>
            </ul>
        ',
        'speakers' => [
            [
                'name' => 'Dr. Sarah Johnson',
                'title' => 'Professor of Qualitative Research',
                'institution' => 'University of Research',
                'bio' => 'Dr. Sarah Johnson is a leading expert in grounded theory methodology with over 20 years of experience in qualitative research.',
                'avatar' => 'assets/images/authors/sarah.jpg'
            ],
            [
                'name' => 'Dr. Michael Chen',
                'title' => 'Research Methodologist',
                'institution' => 'Research Institute',
                'bio' => 'Dr. Michael Chen specializes in advanced qualitative methodologies and has published extensively on grounded theory.',
                'avatar' => 'assets/images/authors/michael.jpg'
            ]
        ],
        'date' => '2024-02-15',
        'time' => '14:00',
        'timezone' => 'EST',
        'duration' => '90 minutes',
        'category' => 'methodology',
        'level' => 'advanced',
        'status' => 'upcoming', // upcoming, live, past, cancelled, postponed
        'image' => 'assets/images/webinar-1.jpg',
        'max_participants' => 100,
        'registered_count' => 67,
        'price' => 0, // 0 for free
        'certificate' => true,
        'materials' => [
            'Pre-webinar reading materials',
            'Slides and handouts',
            'Sample coding examples',
            'Reference list'
        ],
        'agenda' => [
            ['time' => '14:00-14:10', 'topic' => 'Introduction and Overview'],
            ['time' => '14:10-14:30', 'topic' => 'Advanced Theoretical Sampling'],
            ['time' => '14:30-14:50', 'topic' => 'Sophisticated Coding Techniques'],
            ['time' => '14:50-15:10', 'topic' => 'Memo Writing and Theory Development'],
            ['time' => '15:10-15:25', 'topic' => 'Quality Criteria and Validation'],
            ['time' => '15:25-15:45', 'topic' => 'Q&A Session'],
            ['time' => '15:45-15:50', 'topic' => 'Wrap-up and Next Steps']
        ],
        'learning_objectives' => [
            'Understand advanced theoretical sampling strategies',
            'Master sophisticated coding techniques',
            'Develop effective memo writing skills',
            'Apply quality criteria to grounded theory research',
            'Avoid common pitfalls in grounded theory methodology'
        ],
        'prerequisites' => [
            'Basic understanding of grounded theory methodology',
            'Experience with qualitative research methods',
            'Familiarity with coding techniques'
        ]
    ],
    2 => [
        'id' => 2,
        'title' => 'NVivo Software Masterclass',
        'description' => 'Master NVivo software for qualitative data analysis with hands-on examples and practical tips.',
        'long_description' => '
            <h3>Webinar Overview</h3>
            <p>This hands-on masterclass will guide you through the advanced features of NVivo software for qualitative data analysis. Learn how to efficiently organize, code, and analyze your qualitative data.</p>
            
            <h3>What You Will Learn</h3>
            <ul>
                <li>Advanced NVivo features and capabilities</li>
                <li>Efficient data organization strategies</li>
                <li>Advanced coding and querying techniques</li>
                <li>Visualization and reporting tools</li>
                <li>Best practices for team collaboration</li>
            </ul>
        ',
        'speakers' => [
            [
                'name' => 'Emily Rodriguez',
                'title' => 'NVivo Certified Trainer',
                'institution' => 'Data Analysis Institute',
                'bio' => 'Emily Rodriguez is a certified NVivo trainer with extensive experience in qualitative data analysis.',
                'avatar' => 'assets/images/authors/emily.jpg'
            ]
        ],
        'date' => '2024-02-20',
        'time' => '15:00',
        'timezone' => 'EST',
        'duration' => '120 minutes',
        'category' => 'software',
        'level' => 'intermediate',
        'status' => 'upcoming',
        'image' => 'assets/images/webinar-2.jpg',
        'max_participants' => 50,
        'registered_count' => 45,
        'price' => 25,
        'certificate' => true,
        'materials' => [
            'NVivo trial version',
            'Sample datasets',
            'Step-by-step guides',
            'Reference materials'
        ],
        'agenda' => [
            ['time' => '15:00-15:15', 'topic' => 'Introduction to NVivo'],
            ['time' => '15:15-15:45', 'topic' => 'Data Import and Organization'],
            ['time' => '15:45-16:15', 'topic' => 'Coding Techniques'],
            ['time' => '16:15-16:45', 'topic' => 'Advanced Queries'],
            ['time' => '16:45-17:15', 'topic' => 'Visualization and Reporting'],
            ['time' => '17:15-17:30', 'topic' => 'Q&A and Troubleshooting']
        ],
        'learning_objectives' => [
            'Navigate NVivo interface efficiently',
            'Import and organize various data types',
            'Apply advanced coding techniques',
            'Create complex queries and reports',
            'Generate visualizations and presentations'
        ],
        'prerequisites' => [
            'Basic computer skills',
            'Understanding of qualitative research',
            'NVivo trial version installed'
        ]
    ]
];

$webinar = $webinars[$webinarId] ?? $webinars[1];

// Calculate webinar status
$now = new DateTime();
$webinarDateTime = new DateTime($webinar['date'] . ' ' . $webinar['time']);
$webinarEndTime = clone $webinarDateTime;
$webinarEndTime->add(new DateInterval('PT' . intval($webinar['duration']) . 'M'));

if ($webinar['status'] === 'live' || ($now >= $webinarDateTime && $now <= $webinarEndTime)) {
    $webinar['status'] = 'live';
} elseif ($now > $webinarEndTime) {
    $webinar['status'] = 'past';
}

// Mock related webinars
$relatedWebinars = array_filter($webinars, function($w) use ($webinar) {
    return $w['id'] !== $webinar['id'] && 
           ($w['category'] === $webinar['category'] || $w['level'] === $webinar['level']);
});

// Mock participants
$participants = [
    ['name' => 'Dr. Sarah Johnson', 'avatar' => 'assets/images/authors/sarah.jpg', 'online' => true],
    ['name' => 'Dr. Michael Chen', 'avatar' => 'assets/images/authors/michael.jpg', 'online' => true],
    ['name' => 'Emily Rodriguez', 'avatar' => 'assets/images/authors/emily.jpg', 'online' => false],
    ['name' => 'Marcus Thompson', 'avatar' => 'assets/images/avatar-default.jpg', 'online' => true],
    ['name' => 'Lisa Wang', 'avatar' => 'assets/images/avatar-default.jpg', 'online' => false]
];

$isRegistered = isset($_SESSION['user_id']) && rand(0, 1); // Mock registration status
$isLive = $webinar['status'] === 'live';
?>

<!-- Webinar Header -->
<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?lang=<?= urlencode($lang) ?>" class="text-white">Home</a></li>
                        <li class="breadcrumb-item"><a href="webinars.php?lang=<?= urlencode($lang) ?>" class="text-white">Webinars</a></li>
                        <li class="breadcrumb-item active text-white"><?= htmlspecialchars($webinar['title']) ?></li>
                    </ol>
                </nav>
                
                <div class="mb-3">
                    <span class="badge bg-<?= $webinar['status'] === 'live' ? 'danger' : ($webinar['status'] === 'upcoming' ? 'success' : 'secondary') ?> fs-6">
                        <?= $webinar['status'] === 'live' ? htmlspecialchars($texts['liveNow']) : htmlspecialchars($texts[$webinar['status']]) ?>
                    </span>
                    <span class="badge bg-light text-dark ms-2"><?= htmlspecialchars($texts[$webinar['level']]) ?></span>
                    <span class="badge bg-light text-dark ms-2"><?= htmlspecialchars($texts[$webinar['category']] ?? $webinar['category']) ?></span>
                </div>
                
                <h1 class="display-5 fw-bold mb-3"><?= htmlspecialchars($webinar['title']) ?></h1>
                <p class="lead mb-4"><?= htmlspecialchars($webinar['description']) ?></p>
                
                <div class="d-flex flex-wrap gap-3 align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-calendar-event me-2"></i>
                        <span><?= date('l, F j, Y', strtotime($webinar['date'])) ?></span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-clock me-2"></i>
                        <span><?= htmlspecialchars($webinar['time']) ?> <?= htmlspecialchars($webinar['timezone']) ?></span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-stopwatch me-2"></i>
                        <span><?= htmlspecialchars($webinar['duration']) ?></span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-people me-2"></i>
                        <span><?= $webinar['registered_count'] ?>/<?= $webinar['max_participants'] ?> <?= htmlspecialchars($texts['registered']) ?></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <?php if ($webinar['status'] === 'upcoming'): ?>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($isRegistered): ?>
                            <button class="btn btn-success btn-lg mb-2 w-100">
                                <i class="bi bi-check-circle"></i> <?= htmlspecialchars($texts['registered']) ?>
                            </button>
                        <?php else: ?>
                            <button class="btn btn-light btn-lg mb-2 w-100" onclick="registerForWebinar()">
                                <i class="bi bi-calendar-plus"></i> <?= htmlspecialchars($texts['register']) ?>
                            </button>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <small><?= htmlspecialchars($texts['loginToRegister']) ?></small>
                            <div class="mt-2">
                                <a href="login.php?lang=<?= urlencode($lang) ?>" class="btn btn-light btn-sm">
                                    <?= htmlspecialchars($texts['loginBtn']) ?>
                                </a>
                                <a href="register.php?lang=<?= urlencode($lang) ?>" class="btn btn-outline-light btn-sm">
                                    <?= htmlspecialchars($texts['registerBtn']) ?>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php elseif ($webinar['status'] === 'live'): ?>
                    <button class="btn btn-danger btn-lg mb-2 w-100" onclick="joinWebinar()">
                        <i class="bi bi-camera-video"></i> <?= htmlspecialchars($texts['joinNow']) ?>
                    </button>
                <?php elseif ($webinar['status'] === 'past'): ?>
                    <button class="btn btn-light btn-lg mb-2 w-100" onclick="watchRecording()">
                        <i class="bi bi-play-circle"></i> <?= htmlspecialchars($texts['watchRecording']) ?>
                    </button>
                <?php endif; ?>
                
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-light" onclick="setReminder()">
                        <i class="bi bi-bell"></i> <?= htmlspecialchars($texts['reminder']) ?>
                    </button>
                    <button class="btn btn-outline-light" onclick="shareWebinar()">
                        <i class="bi bi-share"></i> <?= htmlspecialchars($texts['share']) ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Live Webinar Interface (shown when live) -->
<?php if ($isLive): ?>
<div class="bg-dark text-white py-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <!-- Video Player -->
                <div class="position-relative bg-black rounded" style="height: 400px;">
                    <div class="d-flex align-items-center justify-content-center h-100">
                        <div class="text-center">
                            <i class="bi bi-camera-video-off fs-1 text-muted mb-3"></i>
                            <p class="text-muted">Live stream will begin shortly</p>
                        </div>
                    </div>
                    
                    <!-- Video Controls -->
                    <div class="position-absolute bottom-0 start-0 end-0 p-3 bg-dark bg-opacity-75">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-light" onclick="toggleMute()">
                                    <i class="bi bi-mic"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-light" onclick="toggleVideo()">
                                    <i class="bi bi-camera-video"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-light" onclick="toggleScreenShare()">
                                    <i class="bi bi-display"></i>
                                </button>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-light" onclick="toggleFullscreen()">
                                    <i class="bi bi-fullscreen"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-light" onclick="openSettings()">
                                    <i class="bi bi-gear"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="leaveWebinar()">
                                    <?= htmlspecialchars($texts['leaveWebinar']) ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Chat Interface -->
                <div class="card bg-dark border-secondary">
                    <div class="card-header bg-dark border-secondary">
                        <ul class="nav nav-tabs card-header-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active text-white" data-bs-toggle="tab" href="#chat">
                                    <i class="bi bi-chat"></i> <?= htmlspecialchars($texts['chat']) ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" data-bs-toggle="tab" href="#questions">
                                    <i class="bi bi-question-circle"></i> <?= htmlspecialchars($texts['questions']) ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" data-bs-toggle="tab" href="#participants">
                                    <i class="bi bi-people"></i> <?= htmlspecialchars($texts['participants']) ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-0">
                        <div class="tab-content">
                            <!-- Chat Tab -->
                            <div class="tab-pane fade show active" id="chat">
                                <div class="chat-messages p-3" style="height: 300px; overflow-y: auto;">
                                    <div class="chat-message mb-2">
                                        <small class="text-muted">Dr. Sarah Johnson</small>
                                        <p class="mb-1">Welcome everyone to today's webinar!</p>
                                    </div>
                                    <div class="chat-message mb-2">
                                        <small class="text-muted">Marcus Thompson</small>
                                        <p class="mb-1">Excited to learn about advanced grounded theory!</p>
                                    </div>
                                </div>
                                <div class="chat-input p-3 border-top">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="<?= htmlspecialchars($texts['messagePlaceholder']) ?>">
                                        <button class="btn btn-primary">
                                            <i class="bi bi-send"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Questions Tab -->
                            <div class="tab-pane fade" id="questions">
                                <div class="p-3">
                                    <button class="btn btn-warning btn-sm mb-3" onclick="raiseHand()">
                                        <i class="bi bi-hand-index"></i> <?= htmlspecialchars($texts['raiseHand']) ?>
                                    </button>
                                    <div class="questions-list">
                                        <div class="question mb-2 p-2 bg-secondary rounded">
                                            <small class="text-muted">Lisa Wang</small>
                                            <p class="mb-1">How do you handle theoretical saturation in large datasets?</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Participants Tab -->
                            <div class="tab-pane fade" id="participants">
                                <div class="p-3">
                                    <div class="participants-list">
                                        <?php foreach ($participants as $participant): ?>
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="position-relative">
                                                    <img src="<?= htmlspecialchars($participant['avatar']) ?>" 
                                                         alt="<?= htmlspecialchars($participant['name']) ?>" 
                                                         class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                                    <?php if ($participant['online']): ?>
                                                        <span class="position-absolute bottom-0 end-0 bg-success rounded-circle" style="width: 8px; height: 8px;"></span>
                                                    <?php endif; ?>
                                                </div>
                                                <span class="text-white"><?= htmlspecialchars($participant['name']) ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Webinar Content -->
<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <!-- Speakers -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0"><?= htmlspecialchars($texts['speakers']) ?></h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($webinar['speakers'] as $speaker): ?>
                            <div class="d-flex mb-3">
                                <img src="<?= htmlspecialchars($speaker['avatar']) ?>" alt="<?= htmlspecialchars($speaker['name']) ?>" 
                                     class="rounded-circle me-3" style="width: 64px; height: 64px; object-fit: cover;">
                                <div>
                                    <h6 class="mb-1"><?= htmlspecialchars($speaker['name']) ?></h6>
                                    <p class="text-muted mb-1"><?= htmlspecialchars($speaker['title']) ?></p>
                                    <p class="text-muted mb-2"><?= htmlspecialchars($speaker['institution']) ?></p>
                                    <p class="small"><?= htmlspecialchars($speaker['bio']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Description -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0"><?= htmlspecialchars($texts['description']) ?></h5>
                    </div>
                    <div class="card-body">
                        <?= $webinar['long_description'] ?>
                    </div>
                </div>
                
                <!-- Agenda -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0"><?= htmlspecialchars($texts['agenda']) ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <?php foreach ($webinar['agenda'] as $item): ?>
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0 me-3">
                                        <span class="badge bg-primary"><?= htmlspecialchars($item['time']) ?></span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0"><?= htmlspecialchars($item['topic']) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Learning Objectives -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0"><?= htmlspecialchars($texts['learningObjectives']) ?></h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <?php foreach ($webinar['learning_objectives'] as $objective): ?>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    <?= htmlspecialchars($objective) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                
                <!-- Prerequisites -->
                <?php if (!empty($webinar['prerequisites'])): ?>
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0"><?= htmlspecialchars($texts['prerequisites']) ?></h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <?php foreach ($webinar['prerequisites'] as $prerequisite): ?>
                                    <li class="mb-2">
                                        <i class="bi bi-arrow-right text-primary me-2"></i>
                                        <?= htmlspecialchars($prerequisite) ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="col-lg-4">
                <!-- Materials -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h6 class="card-title mb-0"><?= htmlspecialchars($texts['materials']) ?></h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <?php foreach ($webinar['materials'] as $material): ?>
                                <li class="mb-2">
                                    <i class="bi bi-file-earmark-text text-primary me-2"></i>
                                    <?= htmlspecialchars($material) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <button class="btn btn-outline-primary btn-sm w-100">
                            <i class="bi bi-download"></i> <?= htmlspecialchars($texts['downloadSlides']) ?>
                        </button>
                    </div>
                </div>
                
                <!-- Certificate -->
                <?php if ($webinar['certificate']): ?>
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body text-center">
                            <i class="bi bi-award text-warning fs-1 mb-3"></i>
                            <h6><?= htmlspecialchars($texts['certificate']) ?></h6>
                            <p class="text-muted small"><?= htmlspecialchars($texts['certificateAvailable']) ?></p>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Related Webinars -->
                <?php if (!empty($relatedWebinars)): ?>
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h6 class="card-title mb-0"><?= htmlspecialchars($texts['relatedWebinars']) ?></h6>
                        </div>
                        <div class="card-body">
                            <?php foreach (array_slice($relatedWebinars, 0, 3) as $related): ?>
                                <div class="d-flex mb-3">
                                    <img src="<?= htmlspecialchars($related['image']) ?>" alt="<?= htmlspecialchars($related['title']) ?>" 
                                         class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-1">
                                            <a href="webinar-details.php?id=<?= $related['id'] ?>&lang=<?= urlencode($lang) ?>" class="text-decoration-none">
                                                <?= htmlspecialchars($related['title']) ?>
                                            </a>
                                        </h6>
                                        <small class="text-muted">
                                            <?= date('M j, Y', strtotime($related['date'])) ?> • <?= htmlspecialchars($related['duration']) ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Leave Webinar Modal -->
<div class="modal fade" id="leaveWebinarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= htmlspecialchars($texts['leaveWebinar']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><?= htmlspecialchars($texts['confirmLeave']) ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= htmlspecialchars($texts['no']) ?></button>
                <button type="button" class="btn btn-danger" onclick="confirmLeaveWebinar()"><?= htmlspecialchars($texts['yes']) ?></button>
            </div>
        </div>
    </div>
</div>

<script>
// Webinar interactions
function registerForWebinar() {
    if (confirm('Register for this webinar?')) {
        // In a real app, this would make an API call
        showToast('Success', 'Successfully registered for the webinar!');
        location.reload();
    }
}

function joinWebinar() {
    // In a real app, this would redirect to the live webinar interface
    showToast('Info', 'Joining webinar...');
}

function watchRecording() {
    // In a real app, this would open the recording
    showToast('Info', 'Opening webinar recording...');
}

function setReminder() {
    const reminderBtn = event.target.closest('button');
    reminderBtn.innerHTML = '<i class="bi bi-bell-fill"></i> <?= htmlspecialchars($texts['reminderSet']) ?>';
    reminderBtn.classList.remove('btn-outline-light');
    reminderBtn.classList.add('btn-success');
    showToast('Success', 'Reminder set for this webinar');
}

function shareWebinar() {
    const url = window.location.href;
    const title = '<?= addslashes($webinar['title']) ?>';
    
    if (navigator.share) {
        navigator.share({
            title: title,
            url: url
        });
    } else {
        navigator.clipboard.writeText(url).then(() => {
            showToast('Success', 'Webinar link copied to clipboard');
        });
    }
}

// Live webinar controls
function toggleMute() {
    const btn = event.target.closest('button');
    const icon = btn.querySelector('i');
    
    if (icon.classList.contains('bi-mic')) {
        icon.className = 'bi bi-mic-mute';
        btn.innerHTML = '<i class="bi bi-mic-mute"></i>';
    } else {
        icon.className = 'bi bi-mic';
        btn.innerHTML = '<i class="bi bi-mic"></i>';
    }
}

function toggleVideo() {
    const btn = event.target.closest('button');
    const icon = btn.querySelector('i');
    
    if (icon.classList.contains('bi-camera-video')) {
        icon.className = 'bi bi-camera-video-off';
        btn.innerHTML = '<i class="bi bi-camera-video-off"></i>';
    } else {
        icon.className = 'bi bi-camera-video';
        btn.innerHTML = '<i class="bi bi-camera-video"></i>';
    }
}

function toggleScreenShare() {
    showToast('Info', 'Screen sharing feature coming soon');
}

function toggleFullscreen() {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen();
    } else {
        document.exitFullscreen();
    }
}

function openSettings() {
    showToast('Info', 'Settings panel coming soon');
}

function leaveWebinar() {
    const modal = new bootstrap.Modal(document.getElementById('leaveWebinarModal'));
    modal.show();
}

function confirmLeaveWebinar() {
    // In a real app, this would properly end the webinar session
    window.location.href = 'webinars.php?lang=<?= urlencode($lang) ?>';
}

function raiseHand() {
    const btn = event.target.closest('button');
    btn.classList.toggle('btn-warning');
    btn.classList.toggle('btn-success');
    
    if (btn.classList.contains('btn-success')) {
        btn.innerHTML = '<i class="bi bi-hand-index-fill"></i> <?= htmlspecialchars($texts['handRaised']) ?>';
        showToast('Success', 'Hand raised! The speaker will call on you.');
    } else {
        btn.innerHTML = '<i class="bi bi-hand-index"></i> <?= htmlspecialchars($texts['raiseHand']) ?>';
    }
}

// Chat functionality
document.addEventListener('DOMContentLoaded', function() {
    const chatInput = document.querySelector('.chat-input input');
    if (chatInput) {
        chatInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && this.value.trim()) {
                // In a real app, this would send the message via WebSocket
                const message = this.value;
                this.value = '';
                showToast('Success', 'Message sent');
            }
        });
    }
});

// Countdown timer for upcoming webinars
<?php if ($webinar['status'] === 'upcoming'): ?>
function updateCountdown() {
    const webinarDate = new Date('<?= $webinar['date'] ?> <?= $webinar['time'] ?>');
    const now = new Date();
    const diff = webinarDate - now;
    
    if (diff > 0) {
        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);
        
        // Update countdown display if it exists
        const countdownElement = document.getElementById('countdown');
        if (countdownElement) {
            countdownElement.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
        }
    }
}

setInterval(updateCountdown, 1000);
updateCountdown();
<?php endif; ?>
</script>

<?php include_once __DIR__ . '/includes/footer.php'; ?> 