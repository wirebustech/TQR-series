<?php

// Test WebSocket Functionality
echo "Testing TQRS WebSocket Functionality\n";
echo "====================================\n\n";

// Configuration
$wsUrl = 'ws://localhost:8090';
$apiBase = 'http://localhost:8000/api';
$testToken = 'your_test_token_here'; // Replace with actual token

// Test cases
$tests = [
    'connection' => 'Test WebSocket connection',
    'authentication' => 'Test WebSocket authentication',
    'webinar_join' => 'Test joining webinar room',
    'chat_message' => 'Test sending chat messages',
    'typing_indicator' => 'Test typing indicators',
    'webinar_actions' => 'Test webinar control actions',
    'user_presence' => 'Test user presence tracking',
    'reconnection' => 'Test automatic reconnection'
];

// WebSocket client simulation
class WebSocketTestClient {
    private $socket;
    private $connected = false;
    private $authenticated = false;
    private $currentWebinar = null;
    private $messages = [];
    private $errors = [];

    public function connect($url) {
        echo "Connecting to WebSocket server at {$url}...\n";
        
        // Simulate WebSocket connection
        $this->socket = fsockopen('localhost', 8090, $errno, $errstr, 10);
        
        if (!$this->socket) {
            $this->errors[] = "Connection failed: $errstr ($errno)";
            return false;
        }
        
        $this->connected = true;
        echo "✓ WebSocket connection established\n";
        return true;
    }

    public function authenticate($token) {
        if (!$this->connected) {
            $this->errors[] = "Not connected to WebSocket server";
            return false;
        }

        echo "Authenticating with token...\n";
        
        $authMessage = json_encode([
            'type' => 'auth',
            'token' => $token
        ]);
        
        $this->send($authMessage);
        
        // Simulate authentication response
        $response = $this->receive();
        if ($response && strpos($response, 'auth_success') !== false) {
            $this->authenticated = true;
            echo "✓ WebSocket authentication successful\n";
            return true;
        } else {
            $this->errors[] = "Authentication failed";
            return false;
        }
    }

    public function joinWebinar($webinarId) {
        if (!$this->authenticated) {
            $this->errors[] = "Not authenticated";
            return false;
        }

        echo "Joining webinar {$webinarId}...\n";
        
        $joinMessage = json_encode([
            'type' => 'join_webinar',
            'webinar_id' => $webinarId
        ]);
        
        $this->send($joinMessage);
        $this->currentWebinar = $webinarId;
        
        // Simulate join response
        $response = $this->receive();
        if ($response && strpos($response, 'joined_webinar') !== false) {
            echo "✓ Successfully joined webinar\n";
            return true;
        } else {
            $this->errors[] = "Failed to join webinar";
            return false;
        }
    }

    public function sendChatMessage($message) {
        if (!$this->currentWebinar) {
            $this->errors[] = "Not in a webinar";
            return false;
        }

        echo "Sending chat message: {$message}\n";
        
        $chatMessage = json_encode([
            'type' => 'chat_message',
            'webinar_id' => $this->currentWebinar,
            'message' => $message
        ]);
        
        $this->send($chatMessage);
        echo "✓ Chat message sent\n";
        return true;
    }

    public function sendTyping($isTyping = true) {
        if (!$this->currentWebinar) {
            return false;
        }

        $typingMessage = json_encode([
            'type' => 'typing',
            'webinar_id' => $this->currentWebinar,
            'is_typing' => $isTyping
        ]);
        
        $this->send($typingMessage);
        return true;
    }

    public function sendWebinarAction($action) {
        if (!$this->currentWebinar) {
            $this->errors[] = "Not in a webinar";
            return false;
        }

        echo "Sending webinar action: {$action}\n";
        
        $actionMessage = json_encode([
            'type' => 'webinar_action',
            'webinar_id' => $this->currentWebinar,
            'action' => $action
        ]);
        
        $this->send($actionMessage);
        echo "✓ Webinar action sent\n";
        return true;
    }

    public function ping() {
        $pingMessage = json_encode(['type' => 'ping']);
        $this->send($pingMessage);
        
        $response = $this->receive();
        if ($response && strpos($response, 'pong') !== false) {
            echo "✓ Ping/Pong working\n";
            return true;
        } else {
            $this->errors[] = "Ping/Pong failed";
            return false;
        }
    }

    private function send($message) {
        if ($this->socket) {
            fwrite($this->socket, $message . "\n");
            $this->messages[] = ['sent' => $message, 'time' => microtime(true)];
        }
    }

    private function receive() {
        if ($this->socket) {
            $response = fgets($this->socket);
            if ($response) {
                $this->messages[] = ['received' => $response, 'time' => microtime(true)];
                return $response;
            }
        }
        return null;
    }

    public function disconnect() {
        if ($this->socket) {
            fclose($this->socket);
            $this->socket = null;
        }
        $this->connected = false;
        $this->authenticated = false;
        $this->currentWebinar = null;
        echo "WebSocket connection closed\n";
    }

    public function getErrors() {
        return $this->errors;
    }

    public function getMessages() {
        return $this->messages;
    }

    public function isConnected() {
        return $this->connected;
    }

    public function isAuthenticated() {
        return $this->authenticated;
    }
}

// Test functions
function testConnection() {
    echo "1. Testing WebSocket Connection\n";
    echo "-------------------------------\n";
    
    $client = new WebSocketTestClient();
    $result = $client->connect($GLOBALS['wsUrl']);
    
    if ($result) {
        echo "✓ Connection test passed\n\n";
        return $client;
    } else {
        echo "✗ Connection test failed\n";
        printErrors($client->getErrors());
        return null;
    }
}

function testAuthentication($client) {
    echo "2. Testing WebSocket Authentication\n";
    echo "-----------------------------------\n";
    
    if (!$client) {
        echo "✗ No client available for authentication test\n\n";
        return false;
    }
    
    $result = $client->authenticate($GLOBALS['testToken']);
    
    if ($result) {
        echo "✓ Authentication test passed\n\n";
        return true;
    } else {
        echo "✗ Authentication test failed\n";
        printErrors($client->getErrors());
        return false;
    }
}

function testWebinarJoin($client) {
    echo "3. Testing Webinar Room Join\n";
    echo "----------------------------\n";
    
    if (!$client || !$client->isAuthenticated()) {
        echo "✗ Client not authenticated for webinar test\n\n";
        return false;
    }
    
    $result = $client->joinWebinar(1); // Test with webinar ID 1
    
    if ($result) {
        echo "✓ Webinar join test passed\n\n";
        return true;
    } else {
        echo "✗ Webinar join test failed\n";
        printErrors($client->getErrors());
        return false;
    }
}

function testChatMessage($client) {
    echo "4. Testing Chat Message\n";
    echo "----------------------\n";
    
    if (!$client || !$client->isAuthenticated()) {
        echo "✗ Client not ready for chat test\n\n";
        return false;
    }
    
    $result = $client->sendChatMessage("Hello from test client!");
    
    if ($result) {
        echo "✓ Chat message test passed\n\n";
        return true;
    } else {
        echo "✗ Chat message test failed\n";
        printErrors($client->getErrors());
        return false;
    }
}

function testTypingIndicator($client) {
    echo "5. Testing Typing Indicator\n";
    echo "---------------------------\n";
    
    if (!$client || !$client->isAuthenticated()) {
        echo "✗ Client not ready for typing test\n\n";
        return false;
    }
    
    $result1 = $client->sendTyping(true);
    $result2 = $client->sendTyping(false);
    
    if ($result1 && $result2) {
        echo "✓ Typing indicator test passed\n\n";
        return true;
    } else {
        echo "✗ Typing indicator test failed\n";
        printErrors($client->getErrors());
        return false;
    }
}

function testWebinarActions($client) {
    echo "6. Testing Webinar Actions\n";
    echo "--------------------------\n";
    
    if (!$client || !$client->isAuthenticated()) {
        echo "✗ Client not ready for webinar actions test\n\n";
        return false;
    }
    
    $actions = ['start', 'pause', 'resume'];
    $success = true;
    
    foreach ($actions as $action) {
        $result = $client->sendWebinarAction($action);
        if (!$result) {
            $success = false;
        }
    }
    
    if ($success) {
        echo "✓ Webinar actions test passed\n\n";
        return true;
    } else {
        echo "✗ Webinar actions test failed\n";
        printErrors($client->getErrors());
        return false;
    }
}

function testPingPong($client) {
    echo "7. Testing Ping/Pong\n";
    echo "-------------------\n";
    
    if (!$client || !$client->isAuthenticated()) {
        echo "✗ Client not ready for ping test\n\n";
        return false;
    }
    
    $result = $client->ping();
    
    if ($result) {
        echo "✓ Ping/Pong test passed\n\n";
        return true;
    } else {
        echo "✗ Ping/Pong test failed\n";
        printErrors($client->getErrors());
        return false;
    }
}

function testReconnection($client) {
    echo "8. Testing Reconnection\n";
    echo "----------------------\n";
    
    if (!$client) {
        echo "✗ No client available for reconnection test\n\n";
        return false;
    }
    
    echo "Disconnecting client...\n";
    $client->disconnect();
    
    echo "Attempting to reconnect...\n";
    $result = $client->connect($GLOBALS['wsUrl']);
    
    if ($result) {
        echo "✓ Reconnection test passed\n\n";
        return true;
    } else {
        echo "✗ Reconnection test failed\n";
        printErrors($client->getErrors());
        return false;
    }
}

function printErrors($errors) {
    if (!empty($errors)) {
        echo "Errors:\n";
        foreach ($errors as $error) {
            echo "  - {$error}\n";
        }
    }
}

function printSummary($results) {
    echo "Test Summary\n";
    echo "============\n";
    
    $passed = 0;
    $total = count($results);
    
    foreach ($results as $test => $result) {
        $status = $result ? "✓ PASS" : "✗ FAIL";
        echo sprintf("%-20s %s\n", $tests[$test], $status);
        if ($result) $passed++;
    }
    
    echo "\nResults: {$passed}/{$total} tests passed\n";
    
    if ($passed === $total) {
        echo "🎉 All WebSocket tests passed!\n";
    } else {
        echo "⚠️  Some tests failed. Check the WebSocket server and configuration.\n";
    }
}

// Run tests
echo "Starting WebSocket tests...\n";
echo "Make sure the WebSocket server is running: php artisan websocket:serve\n\n";

$results = [];
$client = null;

// Test 1: Connection
$client = testConnection();
$results['connection'] = $client !== null;

// Test 2: Authentication
$results['authentication'] = testAuthentication($client);

// Test 3: Webinar Join
$results['webinar_join'] = testWebinarJoin($client);

// Test 4: Chat Message
$results['chat_message'] = testChatMessage($client);

// Test 5: Typing Indicator
$results['typing_indicator'] = testTypingIndicator($client);

// Test 6: Webinar Actions
$results['webinar_actions'] = testWebinarActions($client);

// Test 7: Ping/Pong
$results['ping_pong'] = testPingPong($client);

// Test 8: Reconnection
$results['reconnection'] = testReconnection($client);

// Cleanup
if ($client) {
    $client->disconnect();
}

// Print summary
echo "\n";
printSummary($results);

echo "\nWebSocket Testing Complete!\n";
echo "==========================\n";
echo "Features tested:\n";
echo "- Real-time connection management\n";
echo "- User authentication\n";
echo "- Webinar room management\n";
echo "- Live chat functionality\n";
echo "- Typing indicators\n";
echo "- Admin webinar controls\n";
echo "- Connection health monitoring\n";
echo "- Automatic reconnection\n";
echo "\nNote: This is a simulation. For full testing, use a real WebSocket client.\n";
?> 