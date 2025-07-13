# TQRS WebSocket API Documentation

The TQRS WebSocket API provides real-time communication capabilities for live webinars, chat functionality, and user presence tracking.

## Overview

The WebSocket server enables real-time features including:
- **Live Webinar Management**: Join/leave webinar rooms, control webinar state
- **Real-time Chat**: Send and receive messages in webinar rooms
- **User Presence**: Track who's online and typing
- **Admin Controls**: Webinar start/pause/end controls for administrators
- **Notifications**: Real-time notifications for users

## Server Setup

### Starting the WebSocket Server

```bash
# Start the WebSocket server on default port 8090
php artisan websocket:serve

# Start on custom port
php artisan websocket:serve --port=9000

# Start on specific host
php artisan websocket:serve --host=0.0.0.0 --port=8090
```

### Dependencies

The WebSocket server requires the `cboden/ratchet` package:

```bash
composer require cboden/ratchet
```

## Connection

### WebSocket URL
```
ws://localhost:8090
```

### Connection Flow

1. **Establish Connection**: Connect to the WebSocket server
2. **Authenticate**: Send authentication token
3. **Join Rooms**: Join specific webinar rooms
4. **Send/Receive Messages**: Communicate in real-time

## Message Types

### Authentication

#### Authenticate User
```json
{
  "type": "auth",
  "token": "your_laravel_sanctum_token"
}
```

**Response (Success):**
```json
{
  "type": "auth_success",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "user"
  }
}
```

**Response (Error):**
```json
{
  "type": "error",
  "message": "Authentication failed: Invalid token"
}
```

### Webinar Management

#### Join Webinar Room
```json
{
  "type": "join_webinar",
  "webinar_id": 123
}
```

**Response:**
```json
{
  "type": "joined_webinar",
  "webinar_id": 123,
  "participants": 5,
  "webinar": {
    "id": 123,
    "title": "Introduction to Qualitative Research",
    "status": "live"
  }
}
```

#### Leave Webinar Room
```json
{
  "type": "leave_webinar",
  "webinar_id": 123
}
```

### Chat Messages

#### Send Chat Message
```json
{
  "type": "chat_message",
  "webinar_id": 123,
  "message": "Hello everyone!"
}
```

**Broadcast to Room:**
```json
{
  "type": "chat_message",
  "webinar_id": 123,
  "user": {
    "id": 1,
    "name": "John Doe",
    "role": "user"
  },
  "message": "Hello everyone!",
  "timestamp": "2025-01-13T10:30:00Z"
}
```

### Typing Indicators

#### Send Typing Status
```json
{
  "type": "typing",
  "webinar_id": 123,
  "is_typing": true
}
```

**Broadcast to Room:**
```json
{
  "type": "typing",
  "webinar_id": 123,
  "user": {
    "id": 1,
    "name": "John Doe"
  },
  "is_typing": true
}
```

### Webinar Actions (Admin Only)

#### Control Webinar
```json
{
  "type": "webinar_action",
  "webinar_id": 123,
  "action": "start"
}
```

**Available Actions:**
- `start` - Start the webinar
- `pause` - Pause the webinar
- `resume` - Resume the webinar
- `end` - End the webinar

**Broadcast to Room:**
```json
{
  "type": "webinar_action",
  "webinar_id": 123,
  "action": "start",
  "admin": {
    "id": 2,
    "name": "Admin User"
  },
  "timestamp": "2025-01-13T10:30:00Z"
}
```

### User Presence

#### User Joined
```json
{
  "type": "user_joined",
  "user": {
    "id": 1,
    "name": "John Doe"
  },
  "webinar_id": 123
}
```

#### User Left
```json
{
  "type": "user_left",
  "user": {
    "id": 1,
    "name": "John Doe"
  },
  "webinar_id": 123
}
```

### Connection Health

#### Ping
```json
{
  "type": "ping"
}
```

**Response:**
```json
{
  "type": "pong"
}
```

### Notifications

#### Receive Notification
```json
{
  "type": "notification",
  "data": {
    "title": "New Webinar",
    "message": "A new webinar has been scheduled",
    "type": "webinar_scheduled"
  }
}
```

## Error Handling

### Error Response Format
```json
{
  "type": "error",
  "message": "Error description"
}
```

### Common Error Messages

- `"Authentication token required"` - Missing auth token
- `"Invalid authentication token"` - Invalid or expired token
- `"Authentication required"` - Not authenticated for action
- `"Webinar ID required"` - Missing webinar ID
- `"Webinar not found or not accessible"` - Invalid webinar
- `"Admin privileges required"` - Insufficient permissions

## Client Implementation

### JavaScript Client

```javascript
// Initialize WebSocket client
const ws = new TQRSWebSocket();

// Connect and authenticate
ws.connect('your_laravel_sanctum_token');

// Join webinar
ws.joinWebinar(123);

// Send chat message
ws.sendChatMessage('Hello everyone!');

// Send typing indicator
ws.sendTyping(true);

// Admin: Control webinar
ws.sendWebinarAction('start');

// Listen for events
document.addEventListener('websocket:chat_message', (e) => {
    console.log('Chat message:', e.detail);
});

document.addEventListener('websocket:user_joined', (e) => {
    console.log('User joined:', e.detail);
});
```

### Event Listeners

The WebSocket client triggers custom events for different message types:

- `websocket:auth_success` - Authentication successful
- `websocket:joined_webinar` - Joined webinar room
- `websocket:user_joined` - User joined the room
- `websocket:user_left` - User left the room
- `websocket:chat_message` - New chat message
- `websocket:webinar_action` - Webinar action performed
- `websocket:typing` - Typing indicator
- `websocket:notification` - New notification
- `websocket:error` - Error occurred
- `websocket:pong` - Ping response

## Security

### Authentication
- All actions require valid Laravel Sanctum token
- Tokens are verified against the database
- Expired tokens are automatically rejected

### Authorization
- Webinar actions require admin role
- Users can only join public webinars
- Chat messages are rate-limited

### Rate Limiting
- Chat messages: 10 per minute per user
- Typing indicators: 30 per minute per user
- Ping messages: 60 per minute per connection

## Performance

### Connection Limits
- Maximum 1000 concurrent connections
- Maximum 100 participants per webinar
- Connection timeout: 5 minutes of inactivity

### Message Persistence
- Chat messages stored in cache for 7 days
- Maximum 100 messages per webinar room
- Automatic cleanup of old messages

## Monitoring

### Server Status
```bash
# Check WebSocket server status
php artisan websocket:status

# View connection logs
tail -f storage/logs/websocket.log
```

### Metrics
- Active connections count
- Messages per second
- Error rate
- Participant count per webinar

## Troubleshooting

### Common Issues

1. **Connection Refused**
   - Ensure WebSocket server is running
   - Check port availability
   - Verify firewall settings

2. **Authentication Failed**
   - Verify token is valid and not expired
   - Check user exists in database
   - Ensure Sanctum is properly configured

3. **Cannot Join Webinar**
   - Verify webinar exists and is public
   - Check user permissions
   - Ensure webinar is not full

4. **Messages Not Received**
   - Check WebSocket connection status
   - Verify room membership
   - Check for JavaScript errors

### Debug Mode

Enable debug logging in `.env`:
```
WEBSOCKET_DEBUG=true
```

## Testing

### Test Script
Run the comprehensive test script:
```bash
php test_websocket.php
```

### Manual Testing
Use browser developer tools or WebSocket testing tools like:
- Browser WebSocket API
- Postman WebSocket support
- wscat command-line tool

## Integration Examples

### Frontend Integration
```html
<!-- Include WebSocket client -->
<script src="/assets/js/websocket.js"></script>

<!-- Live webinar interface -->
<div id="webinarInterface">
    <div id="videoContainer"></div>
    <div id="chatContainer"></div>
    <div id="participantsList"></div>
</div>

<script>
// Initialize live webinar
const webinar = new LiveWebinar();
</script>
```

### Admin Panel Integration
```javascript
// Admin controls
if (user.role === 'admin') {
    document.getElementById('webinarControls').style.display = 'block';
    
    // Start webinar
    document.getElementById('startBtn').onclick = () => {
        ws.sendWebinarAction('start');
    };
}
```

## Best Practices

1. **Connection Management**
   - Implement automatic reconnection
   - Handle connection errors gracefully
   - Monitor connection health

2. **Message Handling**
   - Validate message format
   - Implement rate limiting
   - Handle large message volumes

3. **Security**
   - Always authenticate users
   - Validate permissions
   - Sanitize user input

4. **Performance**
   - Limit message frequency
   - Implement message queuing
   - Monitor resource usage

5. **User Experience**
   - Show connection status
   - Provide typing indicators
   - Handle offline scenarios

---

**Built for The Qualitative Research Series (TQRS)** 