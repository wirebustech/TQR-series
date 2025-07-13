// TQRS WebSocket Client
class TQRSWebSocket {
    constructor() {
        this.ws = null;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
        this.reconnectDelay = 1000;
        this.authenticated = false;
        this.user = null;
        this.currentWebinar = null;
        this.messageHandlers = new Map();
        this.connectionStatus = 'disconnected';
        
        // WebSocket server URL
        this.wsUrl = 'ws://localhost:8090';
        
        // Initialize message handlers
        this.initializeMessageHandlers();
    }

    initializeMessageHandlers() {
        this.messageHandlers.set('auth_success', this.handleAuthSuccess.bind(this));
        this.messageHandlers.set('joined_webinar', this.handleJoinedWebinar.bind(this));
        this.messageHandlers.set('user_joined', this.handleUserJoined.bind(this));
        this.messageHandlers.set('user_left', this.handleUserLeft.bind(this));
        this.messageHandlers.set('chat_message', this.handleChatMessage.bind(this));
        this.messageHandlers.set('webinar_action', this.handleWebinarAction.bind(this));
        this.messageHandlers.set('typing', this.handleTyping.bind(this));
        this.messageHandlers.set('notification', this.handleNotification.bind(this));
        this.messageHandlers.set('error', this.handleError.bind(this));
        this.messageHandlers.set('pong', this.handlePong.bind(this));
    }

    connect(token = null) {
        if (this.ws && this.ws.readyState === WebSocket.OPEN) {
            console.log('WebSocket already connected');
            return;
        }

        try {
            this.ws = new WebSocket(this.wsUrl);
            this.connectionStatus = 'connecting';

            this.ws.onopen = () => {
                console.log('WebSocket connected');
                this.connectionStatus = 'connected';
                this.reconnectAttempts = 0;
                
                // Authenticate if token provided
                if (token) {
                    this.authenticate(token);
                }
            };

            this.ws.onmessage = (event) => {
                this.handleMessage(event.data);
            };

            this.ws.onclose = (event) => {
                console.log('WebSocket disconnected:', event.code, event.reason);
                this.connectionStatus = 'disconnected';
                this.authenticated = false;
                
                // Attempt to reconnect
                if (this.reconnectAttempts < this.maxReconnectAttempts) {
                    this.scheduleReconnect();
                }
            };

            this.ws.onerror = (error) => {
                console.error('WebSocket error:', error);
                this.connectionStatus = 'error';
            };

        } catch (error) {
            console.error('Failed to create WebSocket connection:', error);
            this.connectionStatus = 'error';
        }
    }

    scheduleReconnect() {
        this.reconnectAttempts++;
        const delay = this.reconnectDelay * Math.pow(2, this.reconnectAttempts - 1);
        
        console.log(`Attempting to reconnect in ${delay}ms (attempt ${this.reconnectAttempts}/${this.maxReconnectAttempts})`);
        
        setTimeout(() => {
            if (this.connectionStatus === 'disconnected') {
                this.connect();
            }
        }, delay);
    }

    authenticate(token) {
        if (!this.ws || this.ws.readyState !== WebSocket.OPEN) {
            console.error('WebSocket not connected');
            return;
        }

        this.send({
            type: 'auth',
            token: token
        });
    }

    joinWebinar(webinarId) {
        if (!this.authenticated) {
            console.error('Not authenticated');
            return;
        }

        this.currentWebinar = webinarId;
        this.send({
            type: 'join_webinar',
            webinar_id: webinarId
        });
    }

    leaveWebinar() {
        if (!this.currentWebinar) {
            return;
        }

        this.send({
            type: 'leave_webinar',
            webinar_id: this.currentWebinar
        });

        this.currentWebinar = null;
    }

    sendChatMessage(message) {
        if (!this.currentWebinar || !this.authenticated) {
            console.error('Cannot send message: not in webinar or not authenticated');
            return;
        }

        this.send({
            type: 'chat_message',
            webinar_id: this.currentWebinar,
            message: message
        });
    }

    sendTyping(isTyping = true) {
        if (!this.currentWebinar || !this.authenticated) {
            return;
        }

        this.send({
            type: 'typing',
            webinar_id: this.currentWebinar,
            is_typing: isTyping
        });
    }

    sendWebinarAction(action) {
        if (!this.currentWebinar || !this.authenticated) {
            console.error('Cannot send webinar action: not in webinar or not authenticated');
            return;
        }

        this.send({
            type: 'webinar_action',
            webinar_id: this.currentWebinar,
            action: action
        });
    }

    ping() {
        this.send({ type: 'ping' });
    }

    send(data) {
        if (!this.ws || this.ws.readyState !== WebSocket.OPEN) {
            console.error('WebSocket not connected');
            return;
        }

        try {
            this.ws.send(JSON.stringify(data));
        } catch (error) {
            console.error('Failed to send message:', error);
        }
    }

    handleMessage(data) {
        try {
            const message = JSON.parse(data);
            const handler = this.messageHandlers.get(message.type);
            
            if (handler) {
                handler(message);
            } else {
                console.warn('Unknown message type:', message.type);
            }
        } catch (error) {
            console.error('Failed to parse message:', error);
        }
    }

    handleAuthSuccess(message) {
        this.authenticated = true;
        this.user = message.user;
        console.log('WebSocket authenticated:', this.user.name);
        
        // Start ping interval
        this.startPingInterval();
        
        // Trigger custom event
        this.triggerEvent('auth_success', message);
    }

    handleJoinedWebinar(message) {
        console.log(`Joined webinar ${message.webinar_id} with ${message.participants} participants`);
        this.triggerEvent('joined_webinar', message);
    }

    handleUserJoined(message) {
        console.log(`User ${message.user.name} joined the webinar`);
        this.triggerEvent('user_joined', message);
    }

    handleUserLeft(message) {
        console.log(`User ${message.user.name} left the webinar`);
        this.triggerEvent('user_left', message);
    }

    handleChatMessage(message) {
        console.log(`Chat message from ${message.user.name}: ${message.message}`);
        this.triggerEvent('chat_message', message);
    }

    handleWebinarAction(message) {
        console.log(`Webinar action: ${message.action} by ${message.admin.name}`);
        this.triggerEvent('webinar_action', message);
    }

    handleTyping(message) {
        this.triggerEvent('typing', message);
    }

    handleNotification(message) {
        console.log('Notification received:', message.data);
        this.triggerEvent('notification', message);
    }

    handleError(message) {
        console.error('WebSocket error:', message.message);
        this.triggerEvent('error', message);
    }

    handlePong(message) {
        // Pong received, connection is alive
        this.triggerEvent('pong', message);
    }

    startPingInterval() {
        // Send ping every 30 seconds to keep connection alive
        this.pingInterval = setInterval(() => {
            this.ping();
        }, 30000);
    }

    stopPingInterval() {
        if (this.pingInterval) {
            clearInterval(this.pingInterval);
            this.pingInterval = null;
        }
    }

    triggerEvent(eventName, data) {
        const event = new CustomEvent(`websocket:${eventName}`, {
            detail: data
        });
        document.dispatchEvent(event);
    }

    disconnect() {
        this.stopPingInterval();
        
        if (this.currentWebinar) {
            this.leaveWebinar();
        }
        
        if (this.ws) {
            this.ws.close();
        }
        
        this.authenticated = false;
        this.user = null;
        this.connectionStatus = 'disconnected';
    }

    getConnectionStatus() {
        return this.connectionStatus;
    }

    isAuthenticated() {
        return this.authenticated;
    }

    getCurrentUser() {
        return this.user;
    }

    getCurrentWebinar() {
        return this.currentWebinar;
    }
}

// Global WebSocket instance
window.tqrsWebSocket = new TQRSWebSocket();

// Auto-connect if user is logged in
document.addEventListener('DOMContentLoaded', function() {
    const token = localStorage.getItem('access_token');
    if (token) {
        window.tqrsWebSocket.connect(token);
    }
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = TQRSWebSocket;
} 