#!/usr/bin/env node

/**
 * TQRS WebSocket Performance Testing Script
 * 
 * This script tests WebSocket connection performance, message throughput,
 * and connection stability under various load conditions.
 */

const WebSocket = require('ws');
const fs = require('fs');
const path = require('path');

class WebSocketPerformanceTest {
    constructor() {
        this.wsUrl = 'ws://localhost:8080';
        this.results = {
            timestamp: new Date().toISOString(),
            tests: {},
            summary: {}
        };
        this.testStartTime = Date.now();
    }

    /**
     * Run all WebSocket performance tests
     */
    async runAllTests() {
        this.log('Starting WebSocket Performance Tests');
        
        try {
            await this.testConnectionPerformance();
            await this.testMessageThroughput();
            await this.testConcurrentConnections();
            await this.testConnectionStability();
            await this.testLargeMessageHandling();
            await this.testReconnectionPerformance();
            
            this.generateReport();
            this.log('WebSocket performance testing completed successfully!');
        } catch (error) {
            this.log(`Error during testing: ${error.message}`);
            process.exit(1);
        }
    }

    /**
     * Test WebSocket connection performance
     */
    async testConnectionPerformance() {
        this.log('Testing WebSocket Connection Performance');
        
        const iterations = 50;
        const times = [];
        
        for (let i = 0; i < iterations; i++) {
            const start = Date.now();
            
            try {
                const ws = new WebSocket(this.wsUrl);
                
                await new Promise((resolve, reject) => {
                    ws.on('open', () => {
                        const end = Date.now();
                        times.push(end - start);
                        ws.close();
                        resolve();
                    });
                    
                    ws.on('error', reject);
                    
                    setTimeout(() => reject(new Error('Connection timeout')), 5000);
                });
                
                // Small delay between connections
                await this.sleep(50);
            } catch (error) {
                this.log(`Connection ${i + 1} failed: ${error.message}`);
            }
        }
        
        if (times.length > 0) {
            this.results.tests.connection = {
                iterations: iterations,
                successful: times.length,
                failed: iterations - times.length,
                avg_time: times.reduce((a, b) => a + b, 0) / times.length,
                min_time: Math.min(...times),
                max_time: Math.max(...times),
                success_rate: (times.length / iterations) * 100
            };
        }
    }

    /**
     * Test message throughput
     */
    async testMessageThroughput() {
        this.log('Testing Message Throughput');
        
        const messageCount = 1000;
        const messageSize = 1024; // 1KB
        const testMessage = 'x'.repeat(messageSize);
        
        return new Promise((resolve, reject) => {
            const ws = new WebSocket(this.wsUrl);
            let sentCount = 0;
            let receivedCount = 0;
            let startTime, endTime;
            
            ws.on('open', () => {
                startTime = Date.now();
                
                // Send messages
                for (let i = 0; i < messageCount; i++) {
                    ws.send(JSON.stringify({
                        type: 'performance_test',
                        id: i,
                        data: testMessage
                    }));
                    sentCount++;
                }
            });
            
            ws.on('message', (data) => {
                receivedCount++;
                
                if (receivedCount === messageCount) {
                    endTime = Date.now();
                    const duration = endTime - startTime;
                    
                    this.results.tests.throughput = {
                        message_count: messageCount,
                        message_size: messageSize,
                        sent_count: sentCount,
                        received_count: receivedCount,
                        duration: duration,
                        messages_per_second: (receivedCount / duration) * 1000,
                        bytes_per_second: (receivedCount * messageSize / duration) * 1000,
                        success_rate: (receivedCount / sentCount) * 100
                    };
                    
                    ws.close();
                    resolve();
                }
            });
            
            ws.on('error', reject);
            
            setTimeout(() => {
                this.results.tests.throughput = {
                    message_count: messageCount,
                    message_size: messageSize,
                    sent_count: sentCount,
                    received_count: receivedCount,
                    duration: Date.now() - (startTime || Date.now()),
                    timeout: true,
                    success_rate: sentCount > 0 ? (receivedCount / sentCount) * 100 : 0
                };
                
                ws.close();
                resolve();
            }, 30000); // 30 second timeout
        });
    }

    /**
     * Test concurrent connections
     */
    async testConcurrentConnections() {
        this.log('Testing Concurrent Connections');
        
        const concurrencyLevels = [10, 25, 50, 100];
        
        for (const level of concurrencyLevels) {
            this.log(`Testing ${level} concurrent connections`);
            
            const promises = [];
            const results = {
                target: level,
                successful: 0,
                failed: 0,
                avg_time: 0,
                times: []
            };
            
            for (let i = 0; i < level; i++) {
                const promise = this.createConcurrentConnection(i, results);
                promises.push(promise);
            }
            
            await Promise.allSettled(promises);
            
            if (results.times.length > 0) {
                results.avg_time = results.times.reduce((a, b) => a + b, 0) / results.times.length;
                results.min_time = Math.min(...results.times);
                results.max_time = Math.max(...results.times);
            }
            
            results.success_rate = (results.successful / level) * 100;
            
            this.results.tests.concurrent = this.results.tests.concurrent || {};
            this.results.tests.concurrent[level] = results;
        }
    }

    /**
     * Create a concurrent connection for testing
     */
    async createConcurrentConnection(id, results) {
        const start = Date.now();
        
        return new Promise((resolve) => {
            const ws = new WebSocket(this.wsUrl);
            
            ws.on('open', () => {
                const end = Date.now();
                results.successful++;
                results.times.push(end - start);
                
                // Send a test message
                ws.send(JSON.stringify({
                    type: 'concurrent_test',
                    id: id,
                    timestamp: Date.now()
                }));
                
                // Close after short delay
                setTimeout(() => {
                    ws.close();
                    resolve();
                }, 1000);
            });
            
            ws.on('error', () => {
                results.failed++;
                resolve();
            });
            
            setTimeout(() => {
                results.failed++;
                ws.close();
                resolve();
            }, 5000);
        });
    }

    /**
     * Test connection stability
     */
    async testConnectionStability() {
        this.log('Testing Connection Stability');
        
        const duration = 30000; // 30 seconds
        const messageInterval = 1000; // 1 second
        
        return new Promise((resolve) => {
            const ws = new WebSocket(this.wsUrl);
            let messagesSent = 0;
            let messagesReceived = 0;
            let disconnections = 0;
            const startTime = Date.now();
            
            ws.on('open', () => {
                const interval = setInterval(() => {
                    if (Date.now() - startTime >= duration) {
                        clearInterval(interval);
                        ws.close();
                        return;
                    }
                    
                    ws.send(JSON.stringify({
                        type: 'stability_test',
                        timestamp: Date.now(),
                        sequence: messagesSent
                    }));
                    messagesSent++;
                }, messageInterval);
            });
            
            ws.on('message', () => {
                messagesReceived++;
            });
            
            ws.on('close', () => {
                disconnections++;
                
                this.results.tests.stability = {
                    duration: duration,
                    messages_sent: messagesSent,
                    messages_received: messagesReceived,
                    disconnections: disconnections,
                    message_loss_rate: messagesSent > 0 ? ((messagesSent - messagesReceived) / messagesSent) * 100 : 0,
                    connection_stability: disconnections <= 1 ? 'Good' : 'Poor'
                };
                
                resolve();
            });
            
            ws.on('error', () => {
                disconnections++;
            });
        });
    }

    /**
     * Test large message handling
     */
    async testLargeMessageHandling() {
        this.log('Testing Large Message Handling');
        
        const messageSizes = [1024, 10240, 102400, 1048576]; // 1KB, 10KB, 100KB, 1MB
        
        for (const size of messageSizes) {
            this.log(`Testing ${size} byte messages`);
            
            const result = await this.testMessageSize(size);
            
            this.results.tests.large_messages = this.results.tests.large_messages || {};
            this.results.tests.large_messages[size] = result;
        }
    }

    /**
     * Test specific message size
     */
    async testMessageSize(size) {
        const message = 'x'.repeat(size);
        const iterations = 10;
        
        return new Promise((resolve) => {
            const ws = new WebSocket(this.wsUrl);
            let sent = 0;
            let received = 0;
            const times = [];
            
            ws.on('open', () => {
                const sendNext = () => {
                    if (sent >= iterations) {
                        setTimeout(() => {
                            ws.close();
                        }, 1000);
                        return;
                    }
                    
                    const start = Date.now();
                    ws.send(JSON.stringify({
                        type: 'large_message_test',
                        size: size,
                        data: message
                    }));
                    sent++;
                    
                    const checkResponse = (data) => {
                        const end = Date.now();
                        times.push(end - start);
                        received++;
                        
                        ws.removeListener('message', checkResponse);
                        sendNext();
                    };
                    
                    ws.on('message', checkResponse);
                };
                
                sendNext();
            });
            
            ws.on('close', () => {
                resolve({
                    size: size,
                    iterations: iterations,
                    sent: sent,
                    received: received,
                    avg_time: times.length > 0 ? times.reduce((a, b) => a + b, 0) / times.length : 0,
                    min_time: times.length > 0 ? Math.min(...times) : 0,
                    max_time: times.length > 0 ? Math.max(...times) : 0,
                    success_rate: sent > 0 ? (received / sent) * 100 : 0
                });
            });
            
            ws.on('error', () => {
                resolve({
                    size: size,
                    error: true,
                    sent: sent,
                    received: received
                });
            });
        });
    }

    /**
     * Test reconnection performance
     */
    async testReconnectionPerformance() {
        this.log('Testing Reconnection Performance');
        
        const reconnectionAttempts = 10;
        const results = {
            attempts: reconnectionAttempts,
            successful: 0,
            failed: 0,
            times: []
        };
        
        for (let i = 0; i < reconnectionAttempts; i++) {
            const start = Date.now();
            
            try {
                const ws = new WebSocket(this.wsUrl);
                
                await new Promise((resolve, reject) => {
                    ws.on('open', () => {
                        // Simulate connection loss
                        ws.close();
                        
                        // Reconnect
                        const reconnectStart = Date.now();
                        const newWs = new WebSocket(this.wsUrl);
                        
                        newWs.on('open', () => {
                            const end = Date.now();
                            results.times.push(end - reconnectStart);
                            results.successful++;
                            newWs.close();
                            resolve();
                        });
                        
                        newWs.on('error', () => {
                            results.failed++;
                            reject();
                        });
                    });
                    
                    ws.on('error', () => {
                        results.failed++;
                        reject();
                    });
                });
                
                await this.sleep(500);
            } catch (error) {
                // Error already counted in results
            }
        }
        
        if (results.times.length > 0) {
            results.avg_time = results.times.reduce((a, b) => a + b, 0) / results.times.length;
            results.min_time = Math.min(...results.times);
            results.max_time = Math.max(...results.times);
        }
        
        results.success_rate = (results.successful / reconnectionAttempts) * 100;
        
        this.results.tests.reconnection = results;
    }

    /**
     * Generate performance report
     */
    generateReport() {
        const totalTime = Date.now() - this.testStartTime;
        
        this.results.summary = {
            total_test_time: totalTime,
            recommendations: this.generateRecommendations()
        };
        
        const reportFile = path.join(__dirname, '..', 'performance-results', `websocket_performance_${new Date().toISOString().slice(0, 19).replace(/:/g, '-')}.json`);
        const reportDir = path.dirname(reportFile);
        
        if (!fs.existsSync(reportDir)) {
            fs.mkdirSync(reportDir, { recursive: true });
        }
        
        fs.writeFileSync(reportFile, JSON.stringify(this.results, null, 2));
        
        this.log(`WebSocket performance report generated: ${reportFile}`);
        this.printSummary();
    }

    /**
     * Generate recommendations
     */
    generateRecommendations() {
        const recommendations = [];
        
        // Connection performance
        if (this.results.tests.connection) {
            const conn = this.results.tests.connection;
            if (conn.avg_time > 1000) {
                recommendations.push(`Connection time is high (${conn.avg_time.toFixed(2)}ms). Consider optimizing server response time.`);
            }
            if (conn.success_rate < 95) {
                recommendations.push(`Connection success rate is low (${conn.success_rate.toFixed(1)}%). Check server stability.`);
            }
        }
        
        // Throughput
        if (this.results.tests.throughput) {
            const throughput = this.results.tests.throughput;
            if (throughput.messages_per_second < 100) {
                recommendations.push(`Message throughput is low (${throughput.messages_per_second.toFixed(1)} msg/s). Consider server optimization.`);
            }
        }
        
        // Concurrent connections
        if (this.results.tests.concurrent) {
            Object.entries(this.results.tests.concurrent).forEach(([level, data]) => {
                if (data.success_rate < 90) {
                    recommendations.push(`Concurrent connection success rate is low at ${level} connections (${data.success_rate.toFixed(1)}%). Consider scaling improvements.`);
                }
            });
        }
        
        // Stability
        if (this.results.tests.stability) {
            const stability = this.results.tests.stability;
            if (stability.message_loss_rate > 5) {
                recommendations.push(`Message loss rate is high (${stability.message_loss_rate.toFixed(1)}%). Check connection stability.`);
            }
        }
        
        return recommendations;
    }

    /**
     * Print summary to console
     */
    printSummary() {
        console.log('\n========================================');
        console.log('WEBSOCKET PERFORMANCE TEST SUMMARY');
        console.log('========================================');
        console.log(`Total Test Time: ${((Date.now() - this.testStartTime) / 1000).toFixed(2)} seconds\n`);
        
        // Connection performance
        if (this.results.tests.connection) {
            const conn = this.results.tests.connection;
            console.log('Connection Performance:');
            console.log(`  Success Rate: ${conn.success_rate.toFixed(1)}%`);
            console.log(`  Average Time: ${conn.avg_time.toFixed(2)}ms`);
            console.log(`  Min Time: ${conn.min_time}ms`);
            console.log(`  Max Time: ${conn.max_time}ms\n`);
        }
        
        // Throughput
        if (this.results.tests.throughput) {
            const throughput = this.results.tests.throughput;
            console.log('Message Throughput:');
            console.log(`  Messages/Second: ${throughput.messages_per_second.toFixed(1)}`);
            console.log(`  Bytes/Second: ${(throughput.bytes_per_second / 1024).toFixed(1)} KB/s`);
            console.log(`  Success Rate: ${throughput.success_rate.toFixed(1)}%\n`);
        }
        
        // Concurrent connections
        if (this.results.tests.concurrent) {
            console.log('Concurrent Connections:');
            Object.entries(this.results.tests.concurrent).forEach(([level, data]) => {
                console.log(`  ${level} connections: ${data.success_rate.toFixed(1)}% success rate`);
            });
            console.log();
        }
        
        // Recommendations
        if (this.results.summary.recommendations.length > 0) {
            console.log('Recommendations:');
            this.results.summary.recommendations.forEach((rec, i) => {
                console.log(`  ${i + 1}. ${rec}`);
            });
        }
        
        console.log('========================================\n');
    }

    /**
     * Log message with timestamp
     */
    log(message) {
        console.log(`[${new Date().toISOString()}] ${message}`);
    }

    /**
     * Sleep utility
     */
    sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }
}

// Check if WebSocket module is available
try {
    require('ws');
} catch (error) {
    console.log('WebSocket module not found. Please install it with: npm install ws');
    process.exit(1);
}

// Run the tests
const tester = new WebSocketPerformanceTest();
tester.runAllTests().catch(error => {
    console.error('Test failed:', error);
    process.exit(1);
}); 