<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuditLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        // Log request details
        $this->logRequest($request);
        
        $response = $next($request);
        
        // Log response details
        $this->logResponse($request, $response, $startTime);
        
        return $response;
    }
    
    /**
     * Log incoming request details
     */
    private function logRequest(Request $request): void
    {
        // Skip logging for health checks and non-sensitive endpoints
        if ($this->shouldSkipLogging($request)) {
            return;
        }
        
        $logData = [
            'type' => 'request',
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'path' => $request->path(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => auth()->id(),
            'referer' => $request->header('referer'),
            'content_type' => $request->header('content-type'),
            'timestamp' => now()->toISOString(),
        ];
        
        // Log sensitive actions with higher priority
        if ($this->isSensitiveAction($request)) {
            Log::channel('security')->warning('Sensitive API action', $logData);
        } else {
            Log::channel('audit')->info('API request', $logData);
        }
    }
    
    /**
     * Log response details
     */
    private function logResponse(Request $request, Response $response, float $startTime): void
    {
        if ($this->shouldSkipLogging($request)) {
            return;
        }
        
        $duration = round((microtime(true) - $startTime) * 1000, 2);
        
        $logData = [
            'type' => 'response',
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'status_code' => $response->getStatusCode(),
            'response_time_ms' => $duration,
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'timestamp' => now()->toISOString(),
        ];
        
        // Log errors with higher priority
        if ($response->getStatusCode() >= 400) {
            Log::channel('security')->error('API error response', $logData);
        } else {
            Log::channel('audit')->info('API response', $logData);
        }
    }
    
    /**
     * Determine if request should be skipped from logging
     */
    private function shouldSkipLogging(Request $request): bool
    {
        $skipPaths = [
            'api/health',
            'api/status',
            'sanctum/csrf-cookie',
        ];
        
        return in_array($request->path(), $skipPaths);
    }
    
    /**
     * Determine if this is a sensitive action that requires special logging
     */
    private function isSensitiveAction(Request $request): bool
    {
        $sensitiveActions = [
            'POST /api/login',
            'POST /api/register',
            'POST /api/logout',
            'PUT /api/users/',
            'DELETE /api/users/',
            'POST /api/media-library',
            'DELETE /api/media-library/',
        ];
        
        $action = $request->method() . ' ' . $request->path();
        
        foreach ($sensitiveActions as $sensitive) {
            if (str_starts_with($action, $sensitive)) {
                return true;
            }
        }
        
        return false;
    }
} 