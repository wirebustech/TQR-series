<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\WebSocket\WebSocketServer as TQRSWebSocketServer;

class StartWebSocketServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'websocket:serve {--port=8090 : Port to run the WebSocket server on} {--host=0.0.0.0 : Host to bind the server to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start the WebSocket server for real-time features';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $port = $this->option('port');
        $host = $this->option('host');

        $this->info("Starting TQRS WebSocket server on {$host}:{$port}...");
        $this->info("Press Ctrl+C to stop the server.");

        try {
            $server = IoServer::factory(
                new HttpServer(
                    new WsServer(
                        new TQRSWebSocketServer()
                    )
                ),
                $port,
                $host
            );

            $this->info("WebSocket server is running on ws://{$host}:{$port}");
            $this->info("Ready to handle real-time connections for:");
            $this->line("  • Live webinars and chat");
            $this->line("  • Real-time notifications");
            $this->line("  • User presence tracking");
            $this->line("  • Admin controls");

            $server->run();
        } catch (\Exception $e) {
            $this->error("Failed to start WebSocket server: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
} 