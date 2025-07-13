<?php

namespace App\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use App\Models\User;
use App\Models\Webinar;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class WebSocketServer implements MessageComponentInterface
{
    protected $clients;
    protected $rooms;
    protected $userConnections;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->rooms = [];
        $this->userConnections = [];
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        Log::info("New WebSocket connection! ({$conn->resourceId})");
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);
        
        if (!$data || !isset($data['type'])) {
            return;
        }

        switch ($data['type']) {
            case 'auth':
                $this->handleAuth($from, $data);
                break;
            case 'join_webinar':
                $this->handleJoinWebinar($from, $data);
                break;
            case 'leave_webinar':
                $this->handleLeaveWebinar($from, $data);
                break;
            case 'chat_message':
                $this->handleChatMessage($from, $data);
                break;
            case 'webinar_action':
                $this->handleWebinarAction($from, $data);
                break;
            case 'typing':
                $this->handleTyping($from, $data);
                break;
            case 'ping':
                $this->handlePing($from);
                break;
            default:
                Log::warning("Unknown message type: {$data['type']}");
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        $this->removeUserFromRooms($conn);
        Log::info("Connection {$conn->resourceId} has disconnected");
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        Log::error("An error has occurred: {$e->getMessage()}");
        $conn->close();
    }

    protected function handleAuth($conn, $data)
    {
        if (!isset($data['token'])) {
            $this->sendError($conn, 'Authentication token required');
            return;
        }

        try {
            // Verify the token and get user
            $user = $this->verifyToken($data['token']);
            if (!$user) {
                $this->sendError($conn, 'Invalid authentication token');
                return;
            }

            // Store user connection
            $this->userConnections[$conn->resourceId] = [
                'user_id' => $user->id,
                'user' => $user,
                'rooms' => []
            ];

            $this->sendMessage($conn, [
                'type' => 'auth_success',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role
                ]
            ]);

            Log::info("User {$user->name} authenticated on WebSocket");
        } catch (\Exception $e) {
            $this->sendError($conn, 'Authentication failed: ' . $e->getMessage());
        }
    }

    protected function handleJoinWebinar($conn, $data)
    {
        if (!isset($data['webinar_id'])) {
            $this->sendError($conn, 'Webinar ID required');
            return;
        }

        $userConnection = $this->userConnections[$conn->resourceId] ?? null;
        if (!$userConnection) {
            $this->sendError($conn, 'Authentication required');
            return;
        }

        $webinarId = $data['webinar_id'];
        $roomKey = "webinar_{$webinarId}";

        // Verify webinar exists and is active
        $webinar = Webinar::find($webinarId);
        if (!$webinar || !$webinar->is_public) {
            $this->sendError($conn, 'Webinar not found or not accessible');
            return;
        }

        // Join the room
        if (!isset($this->rooms[$roomKey])) {
            $this->rooms[$roomKey] = [];
        }

        $this->rooms[$roomKey][$conn->resourceId] = $conn;
        $this->userConnections[$conn->resourceId]['rooms'][] = $roomKey;

        // Notify others in the room
        $this->broadcastToRoom($roomKey, [
            'type' => 'user_joined',
            'user' => [
                'id' => $userConnection['user']->id,
                'name' => $userConnection['user']->name
            ],
            'webinar_id' => $webinarId
        ], $conn);

        // Send room info to the joining user
        $this->sendMessage($conn, [
            'type' => 'joined_webinar',
            'webinar_id' => $webinarId,
            'participants' => count($this->rooms[$roomKey]),
            'webinar' => [
                'id' => $webinar->id,
                'title' => $webinar->title,
                'status' => $webinar->status
            ]
        ]);

        Log::info("User {$userConnection['user']->name} joined webinar {$webinarId}");
    }

    protected function handleLeaveWebinar($conn, $data)
    {
        if (!isset($data['webinar_id'])) {
            return;
        }

        $userConnection = $this->userConnections[$conn->resourceId] ?? null;
        if (!$userConnection) {
            return;
        }

        $webinarId = $data['webinar_id'];
        $roomKey = "webinar_{$webinarId}";

        $this->removeFromRoom($conn, $roomKey);

        // Notify others in the room
        $this->broadcastToRoom($roomKey, [
            'type' => 'user_left',
            'user' => [
                'id' => $userConnection['user']->id,
                'name' => $userConnection['user']->name
            ],
            'webinar_id' => $webinarId
        ], $conn);

        Log::info("User {$userConnection['user']->name} left webinar {$webinarId}");
    }

    protected function handleChatMessage($conn, $data)
    {
        if (!isset($data['webinar_id']) || !isset($data['message'])) {
            $this->sendError($conn, 'Webinar ID and message required');
            return;
        }

        $userConnection = $this->userConnections[$conn->resourceId] ?? null;
        if (!$userConnection) {
            $this->sendError($conn, 'Authentication required');
            return;
        }

        $webinarId = $data['webinar_id'];
        $roomKey = "webinar_{$webinarId}";
        $message = trim($data['message']);

        if (empty($message)) {
            return;
        }

        // Store message in cache for persistence
        $this->storeChatMessage($webinarId, $userConnection['user'], $message);

        // Broadcast to room
        $this->broadcastToRoom($roomKey, [
            'type' => 'chat_message',
            'webinar_id' => $webinarId,
            'user' => [
                'id' => $userConnection['user']->id,
                'name' => $userConnection['user']->name,
                'role' => $userConnection['user']->role
            ],
            'message' => $message,
            'timestamp' => now()->toISOString()
        ]);

        Log::info("Chat message in webinar {$webinarId} from {$userConnection['user']->name}");
    }

    protected function handleWebinarAction($conn, $data)
    {
        if (!isset($data['webinar_id']) || !isset($data['action'])) {
            return;
        }

        $userConnection = $this->userConnections[$conn->resourceId] ?? null;
        if (!$userConnection || $userConnection['user']->role !== 'admin') {
            $this->sendError($conn, 'Admin privileges required');
            return;
        }

        $webinarId = $data['webinar_id'];
        $roomKey = "webinar_{$webinarId}";
        $action = $data['action'];

        // Handle different webinar actions
        switch ($action) {
            case 'start':
            case 'pause':
            case 'resume':
            case 'end':
                $this->broadcastToRoom($roomKey, [
                    'type' => 'webinar_action',
                    'webinar_id' => $webinarId,
                    'action' => $action,
                    'admin' => [
                        'id' => $userConnection['user']->id,
                        'name' => $userConnection['user']->name
                    ],
                    'timestamp' => now()->toISOString()
                ]);
                break;
        }

        Log::info("Webinar action '{$action}' triggered by admin {$userConnection['user']->name}");
    }

    protected function handleTyping($conn, $data)
    {
        if (!isset($data['webinar_id'])) {
            return;
        }

        $userConnection = $this->userConnections[$conn->resourceId] ?? null;
        if (!$userConnection) {
            return;
        }

        $webinarId = $data['webinar_id'];
        $roomKey = "webinar_{$webinarId}";

        $this->broadcastToRoom($roomKey, [
            'type' => 'typing',
            'webinar_id' => $webinarId,
            'user' => [
                'id' => $userConnection['user']->id,
                'name' => $userConnection['user']->name
            ],
            'is_typing' => $data['is_typing'] ?? true
        ], $conn);
    }

    protected function handlePing($conn)
    {
        $this->sendMessage($conn, ['type' => 'pong']);
    }

    protected function verifyToken($token)
    {
        // Verify Laravel Sanctum token
        $user = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
        if (!$user) {
            return null;
        }

        return $user->tokenable;
    }

    protected function broadcastToRoom($roomKey, $message, $exclude = null)
    {
        if (!isset($this->rooms[$roomKey])) {
            return;
        }

        foreach ($this->rooms[$roomKey] as $conn) {
            if ($conn !== $exclude) {
                $this->sendMessage($conn, $message);
            }
        }
    }

    protected function sendMessage($conn, $data)
    {
        $conn->send(json_encode($data));
    }

    protected function sendError($conn, $message)
    {
        $this->sendMessage($conn, [
            'type' => 'error',
            'message' => $message
        ]);
    }

    protected function removeFromRoom($conn, $roomKey)
    {
        if (isset($this->rooms[$roomKey][$conn->resourceId])) {
            unset($this->rooms[$roomKey][$conn->resourceId]);
        }

        if (isset($this->userConnections[$conn->resourceId])) {
            $this->userConnections[$conn->resourceId]['rooms'] = array_filter(
                $this->userConnections[$conn->resourceId]['rooms'],
                function($room) use ($roomKey) {
                    return $room !== $roomKey;
                }
            );
        }
    }

    protected function removeUserFromRooms($conn)
    {
        if (!isset($this->userConnections[$conn->resourceId])) {
            return;
        }

        $rooms = $this->userConnections[$conn->resourceId]['rooms'];
        foreach ($rooms as $roomKey) {
            $this->removeFromRoom($conn, $roomKey);
        }

        unset($this->userConnections[$conn->resourceId]);
    }

    protected function storeChatMessage($webinarId, $user, $message)
    {
        $key = "webinar_chat_{$webinarId}";
        $messages = Cache::get($key, []);
        
        $messages[] = [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'message' => $message,
            'timestamp' => now()->toISOString()
        ];

        // Keep only last 100 messages
        if (count($messages) > 100) {
            $messages = array_slice($messages, -100);
        }

        Cache::put($key, $messages, now()->addDays(7));
    }

    public function getRoomParticipants($webinarId)
    {
        $roomKey = "webinar_{$webinarId}";
        if (!isset($this->rooms[$roomKey])) {
            return [];
        }

        $participants = [];
        foreach ($this->rooms[$roomKey] as $connId => $conn) {
            if (isset($this->userConnections[$connId])) {
                $participants[] = $this->userConnections[$connId]['user'];
            }
        }

        return $participants;
    }

    public function broadcastNotification($userId, $notification)
    {
        foreach ($this->userConnections as $connId => $connection) {
            if ($connection['user_id'] == $userId) {
                $this->sendMessage($this->clients->current(), [
                    'type' => 'notification',
                    'data' => $notification
                ]);
            }
        }
    }
} 