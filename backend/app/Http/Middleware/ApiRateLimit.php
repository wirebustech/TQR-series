<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Cache\RateLimiting\Limit;
use Symfony\Component\HttpFoundation\Response;

class ApiRateLimit
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $type = 'default'): Response
    {
        $user = Auth::user();
        $key = $this->resolveRequestSignature($request, $user);
        
        // Define rate limits based on user type and endpoint
        $limits = $this->getRateLimits($type, $user);
        
        foreach ($limits as $limit) {
            if (RateLimiter::tooManyAttempts($key . ':' . $limit['name'], $limit['max_attempts'])) {
                $retryAfter = RateLimiter::availableIn($key . ':' . $limit['name']);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Rate limit exceeded. Please try again later.',
                    'retry_after' => $retryAfter,
                    'limit_type' => $limit['name']
                ], 429)->header('Retry-After', $retryAfter);
            }
            
            RateLimiter::hit($key . ':' . $limit['name'], $limit['decay_minutes'] * 60);
        }

        $response = $next($request);

        // Add rate limit headers
        $this->addRateLimitHeaders($response, $key, $limits);

        return $response;
    }

    /**
     * Resolve the request signature for rate limiting
     */
    private function resolveRequestSignature(Request $request, $user): string
    {
        $signature = $request->ip();
        
        if ($user) {
            $signature .= ':' . $user->id;
        }
        
        return sha1($signature);
    }

    /**
     * Get rate limits based on type and user
     */
    private function getRateLimits(string $type, $user): array
    {
        $limits = [];

        switch ($type) {
            case 'auth':
                // Authentication endpoints - stricter limits
                $limits[] = [
                    'name' => 'auth_attempts',
                    'max_attempts' => $user ? 100 : 5,
                    'decay_minutes' => 15
                ];
                break;

            case 'search':
                // Search endpoints - moderate limits
                $limits[] = [
                    'name' => 'search_requests',
                    'max_attempts' => $user ? 200 : 20,
                    'decay_minutes' => 10
                ];
                break;

            case 'upload':
                // Upload endpoints - stricter limits
                $limits[] = [
                    'name' => 'upload_requests',
                    'max_attempts' => $user ? 50 : 5,
                    'decay_minutes' => 60
                ];
                break;

            case 'payment':
                // Payment endpoints - very strict limits
                $limits[] = [
                    'name' => 'payment_requests',
                    'max_attempts' => $user ? 20 : 3,
                    'decay_minutes' => 30
                ];
                break;

            case 'admin':
                // Admin endpoints - higher limits for admin users
                if ($user && $user->hasRole('admin')) {
                    $limits[] = [
                        'name' => 'admin_requests',
                        'max_attempts' => 500,
                        'decay_minutes' => 5
                    ];
                } else {
                    $limits[] = [
                        'name' => 'admin_requests',
                        'max_attempts' => 10,
                        'decay_minutes' => 60
                    ];
                }
                break;

            case 'public':
                // Public endpoints - moderate limits
                $limits[] = [
                    'name' => 'public_requests',
                    'max_attempts' => $user ? 300 : 100,
                    'decay_minutes' => 5
                ];
                break;

            default:
                // Default limits
                $limits[] = [
                    'name' => 'api_requests',
                    'max_attempts' => $user ? 1000 : 100,
                    'decay_minutes' => 5
                ];
                break;
        }

        return $limits;
    }

    /**
     * Add rate limit headers to response
     */
    private function addRateLimitHeaders(Response $response, string $key, array $limits): void
    {
        foreach ($limits as $limit) {
            $remaining = RateLimiter::remaining($key . ':' . $limit['name'], $limit['max_attempts']);
            $resetTime = RateLimiter::availableIn($key . ':' . $limit['name']);
            
            $response->headers->set("X-RateLimit-{$limit['name']}-Limit", $limit['max_attempts']);
            $response->headers->set("X-RateLimit-{$limit['name']}-Remaining", $remaining);
            $response->headers->set("X-RateLimit-{$limit['name']}-Reset", time() + $resetTime);
        }
    }
} 