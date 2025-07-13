<?php
include_once __DIR__ . '/../includes/translate.php';

$lang = $_GET['lang'] ?? 'en';
$texts = [
    'title' => 'Live Webinar - TQRS',
    'liveWebinar' => 'Live Webinar',
    'webinarTitle' => 'Webinar Title',
    'presenter' => 'Presenter',
    'attendees' => 'Attendees',
    'chat' => 'Chat',
    'questions' => 'Questions',
    'resources' => 'Resources',
    'typeMessage' => 'Type your message...',
    'send' => 'Send',
    'askQuestion' => 'Ask a Question',
    'typeQuestion' => 'Type your question...',
    'submitQuestion' => 'Submit Question',
    'downloadResources' => 'Download Resources',
    'leaveWebinar' => 'Leave Webinar',
    'mute' => 'Mute',
    'unmute' => 'Unmute',
    'videoOn' => 'Video On',
    'videoOff' => 'Video Off',
    'screenShare' => 'Screen Share',
    'stopSharing' => 'Stop Sharing',
    'raiseHand' => 'Raise Hand',
    'lowerHand' => 'Lower Hand'
];
if ($lang !== 'en') {
    foreach ($texts as $k => $v) {
        $texts[$k] = translateText($v, $lang, 'en');
    }
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($texts['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .webinar-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .webinar-header {
            background: #2c3e50;
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .webinar-content {
            flex: 1;
            display: flex;
            overflow: hidden;
        }
        .video-section {
            flex: 2;
            background: #000;
            position: relative;
        }
        .sidebar {
            flex: 1;
            background: #f8f9fa;
            border-left: 1px solid #dee2e6;
            display: flex;
            flex-direction: column;
        }
        .chat-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 1rem;
        }
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            margin-bottom: 1rem;
        }
        .chat-input {
            display: flex;
            gap: 0.5rem;
        }
        .controls {
            position: absolute;
            bottom: 1rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 0.5rem;
        }
        .control-btn {
            background: rgba(0,0,0,0.7);
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .control-btn:hover {
            background: rgba(0,0,0,0.9);
        }
        .control-btn.active {
            background: #dc3545;
        }
        .lang-switcher {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div class="lang-switcher">
        <form method="get">
            <select name="lang" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="en"<?= $lang=='en'?' selected':'' ?>>English</option>
                <option value="fr"<?= $lang=='fr'?' selected':'' ?>>Français</option>
                <option value="es"<?= $lang=='es'?' selected':'' ?>>Español</option>
            </select>
        </form>
    </div>
    
    <div class="webinar-container">
        <!-- Header -->
        <div class="webinar-header">
            <div>
                <h4 id="webinarTitle"><?= htmlspecialchars($texts['webinarTitle']) ?></h4>
                <small id="presenterName"><?= htmlspecialchars($texts['presenter']) ?>: John Doe</small>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span id="attendeeCount"><?= htmlspecialchars($texts['attendees']) ?>: 0</span>
                <button class="btn btn-outline-light btn-sm" onclick="leaveWebinar()">
                    <?= htmlspecialchars($texts['leaveWebinar']) ?>
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="webinar-content">
            <!-- Video Section -->
            <div class="video-section">
                <video id="mainVideo" autoplay muted style="width: 100%; height: 100%; object-fit: cover;">
                    <source src="" type="video/webm">
                </video>
                
                <!-- Controls -->
                <div class="controls">
                    <button class="control-btn" id="muteBtn" onclick="toggleMute()">
                        <i class="bi bi-mic"></i>
                    </button>
                    <button class="control-btn" id="videoBtn" onclick="toggleVideo()">
                        <i class="bi bi-camera-video"></i>
                    </button>
                    <button class="control-btn" id="shareBtn" onclick="toggleScreenShare()">
                        <i class="bi bi-display"></i>
                    </button>
                    <button class="control-btn" id="handBtn" onclick="toggleHand()">
                        <i class="bi bi-hand-index"></i>
                    </button>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs" id="webinarTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="chat-tab" data-bs-toggle="tab" data-bs-target="#chat" type="button" role="tab">
                            <i class="bi bi-chat"></i> <?= htmlspecialchars($texts['chat']) ?>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="questions-tab" data-bs-toggle="tab" data-bs-target="#questions" type="button" role="tab">
                            <i class="bi bi-question-circle"></i> <?= htmlspecialchars($texts['questions']) ?>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="resources-tab" data-bs-toggle="tab" data-bs-target="#resources" type="button" role="tab">
                            <i class="bi bi-file-earmark"></i> <?= htmlspecialchars($texts['resources']) ?>
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="webinarTabContent">
                    <!-- Chat Tab -->
                    <div class="tab-pane fade show active" id="chat" role="tabpanel">
                        <div class="chat-container">
                            <div class="chat-messages" id="chatMessages">
                                <!-- Chat messages will be loaded here -->
                            </div>
                            <div class="chat-input">
                                <input type="text" class="form-control" id="chatInput" placeholder="<?= htmlspecialchars($texts['typeMessage']) ?>">
                                <button class="btn btn-primary" onclick="sendMessage()">
                                    <?= htmlspecialchars($texts['send']) ?>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Questions Tab -->
                    <div class="tab-pane fade" id="questions" role="tabpanel">
                        <div class="chat-container">
                            <div class="chat-messages" id="questionsList">
                                <!-- Questions will be loaded here -->
                            </div>
                            <div class="chat-input">
                                <input type="text" class="form-control" id="questionInput" placeholder="<?= htmlspecialchars($texts['typeQuestion']) ?>">
                                <button class="btn btn-primary" onclick="submitQuestion()">
                                    <?= htmlspecialchars($texts['submitQuestion']) ?>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Resources Tab -->
                    <div class="tab-pane fade" id="resources" role="tabpanel">
                        <div class="p-3">
                            <h6><?= htmlspecialchars($texts['resources']) ?></h6>
                            <div id="resourcesList">
                                <!-- Resources will be loaded here -->
                            </div>
                            <button class="btn btn-outline-primary mt-3" onclick="downloadResources()">
                                <i class="bi bi-download"></i> <?= htmlspecialchars($texts['downloadResources']) ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/websocket-client.js"></script>
    <script>
        // Webinar state
        let isMuted = false;
        let isVideoOn = true;
        let isScreenSharing = false;
        let isHandRaised = false;
        let websocket = null;

        // Initialize webinar
        document.addEventListener('DOMContentLoaded', function() {
            initializeWebinar();
        });

        function initializeWebinar() {
            // Connect to WebSocket
            connectWebSocket();
            
            // Load initial data
            loadChatMessages();
            loadQuestions();
            loadResources();
        }

        function connectWebSocket() {
            websocket = new WebSocket('ws://localhost:8080');
            
            websocket.onopen = function() {
                console.log('Connected to webinar WebSocket');
                // Join webinar room
                websocket.send(JSON.stringify({
                    type: 'join_webinar',
                    webinar_id: getWebinarId()
                }));
            };
            
            websocket.onmessage = function(event) {
                const data = JSON.parse(event.data);
                handleWebSocketMessage(data);
            };
            
            websocket.onclose = function() {
                console.log('Disconnected from webinar WebSocket');
            };
        }

        function handleWebSocketMessage(data) {
            switch(data.type) {
                case 'chat_message':
                    addChatMessage(data.message);
                    break;
                case 'question':
                    addQuestion(data.question);
                    break;
                case 'attendee_count':
                    updateAttendeeCount(data.count);
                    break;
                case 'presenter_control':
                    handlePresenterControl(data.control);
                    break;
            }
        }

        function toggleMute() {
            isMuted = !isMuted;
            const btn = document.getElementById('muteBtn');
            const icon = btn.querySelector('i');
            
            if (isMuted) {
                icon.className = 'bi bi-mic-mute';
                btn.classList.add('active');
            } else {
                icon.className = 'bi bi-mic';
                btn.classList.remove('active');
            }
            
            // Send mute state to server
            if (websocket) {
                websocket.send(JSON.stringify({
                    type: 'mute_toggle',
                    muted: isMuted
                }));
            }
        }

        function toggleVideo() {
            isVideoOn = !isVideoOn;
            const btn = document.getElementById('videoBtn');
            const icon = btn.querySelector('i');
            
            if (!isVideoOn) {
                icon.className = 'bi bi-camera-video-off';
                btn.classList.add('active');
            } else {
                icon.className = 'bi bi-camera-video';
                btn.classList.remove('active');
            }
            
            // Send video state to server
            if (websocket) {
                websocket.send(JSON.stringify({
                    type: 'video_toggle',
                    video_on: isVideoOn
                }));
            }
        }

        function toggleScreenShare() {
            isScreenSharing = !isScreenSharing;
            const btn = document.getElementById('shareBtn');
            const icon = btn.querySelector('i');
            
            if (isScreenSharing) {
                icon.className = 'bi bi-display-fill';
                btn.classList.add('active');
            } else {
                icon.className = 'bi bi-display';
                btn.classList.remove('active');
            }
        }

        function toggleHand() {
            isHandRaised = !isHandRaised;
            const btn = document.getElementById('handBtn');
            const icon = btn.querySelector('i');
            
            if (isHandRaised) {
                icon.className = 'bi bi-hand-index-fill';
                btn.classList.add('active');
            } else {
                icon.className = 'bi bi-hand-index';
                btn.classList.remove('active');
            }
            
            // Send hand raise state to server
            if (websocket) {
                websocket.send(JSON.stringify({
                    type: 'hand_raise',
                    raised: isHandRaised
                }));
            }
        }

        function sendMessage() {
            const input = document.getElementById('chatInput');
            const message = input.value.trim();
            
            if (message && websocket) {
                websocket.send(JSON.stringify({
                    type: 'chat_message',
                    message: message
                }));
                input.value = '';
            }
        }

        function submitQuestion() {
            const input = document.getElementById('questionInput');
            const question = input.value.trim();
            
            if (question && websocket) {
                websocket.send(JSON.stringify({
                    type: 'question',
                    question: question
                }));
                input.value = '';
            }
        }

        function addChatMessage(message) {
            const container = document.getElementById('chatMessages');
            const messageDiv = document.createElement('div');
            messageDiv.className = 'mb-2';
            messageDiv.innerHTML = `
                <strong>${message.author}:</strong> ${message.text}
                <small class="text-muted">${new Date(message.timestamp).toLocaleTimeString()}</small>
            `;
            container.appendChild(messageDiv);
            container.scrollTop = container.scrollHeight;
        }

        function addQuestion(question) {
            const container = document.getElementById('questionsList');
            const questionDiv = document.createElement('div');
            questionDiv.className = 'mb-2 p-2 border rounded';
            questionDiv.innerHTML = `
                <strong>${question.author}:</strong> ${question.text}
                <small class="text-muted d-block">${new Date(question.timestamp).toLocaleTimeString()}</small>
            `;
            container.appendChild(questionDiv);
        }

        function updateAttendeeCount(count) {
            document.getElementById('attendeeCount').textContent = `<?= htmlspecialchars($texts['attendees']) ?>: ${count}`;
        }

        function leaveWebinar() {
            if (confirm('<?= htmlspecialchars($texts['leaveWebinar']) ?>?')) {
                if (websocket) {
                    websocket.close();
                }
                window.location.href = '../index.php?lang=<?= urlencode($lang) ?>';
            }
        }

        function getWebinarId() {
            // Get webinar ID from URL or localStorage
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get('id') || 'default';
        }

        function loadChatMessages() {
            // Load chat messages from server
            console.log('Loading chat messages...');
        }

        function loadQuestions() {
            // Load questions from server
            console.log('Loading questions...');
        }

        function loadResources() {
            // Load resources from server
            console.log('Loading resources...');
        }

        function downloadResources() {
            // Download webinar resources
            console.log('Downloading resources...');
        }

        // Handle Enter key in chat input
        document.getElementById('chatInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });

        // Handle Enter key in question input
        document.getElementById('questionInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                submitQuestion();
            }
        });
    </script>
</body>
</html> 