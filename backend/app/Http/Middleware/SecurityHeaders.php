<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Security Headers
        $this->addSecurityHeaders($response);

        // CORS Headers (if needed)
        $this->addCorsHeaders($response);

        return $response;
    }

    /**
     * Add security headers to response
     */
    private function addSecurityHeaders(Response $response): void
    {
        // Content Security Policy
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://js.stripe.com https://www.google-analytics.com; " .
               "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
               "font-src 'self' https://fonts.gstatic.com; " .
               "img-src 'self' data: https: blob:; " .
               "media-src 'self' https:; " .
               "connect-src 'self' https://api.stripe.com https://www.google-analytics.com wss: ws:; " .
               "frame-src 'self' https://js.stripe.com; " .
               "object-src 'none'; " .
               "base-uri 'self'; " .
               "form-action 'self'; " .
               "frame-ancestors 'none';";

        $response->headers->set('Content-Security-Policy', $csp);

        // X-Frame-Options
        $response->headers->set('X-Frame-Options', 'DENY');

        // X-Content-Type-Options
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // X-XSS-Protection
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions Policy
        $permissionsPolicy = "geolocation=(), microphone=(), camera=(), payment=(), usb=(), magnetometer=(), gyroscope=(), accelerometer=()";
        $response->headers->set('Permissions-Policy', $permissionsPolicy);

        // Strict-Transport-Security (HSTS)
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');

        // Cache Control for API responses
        if ($this->isApiResponse($response)) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, private');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        // Remove server information
        $response->headers->remove('Server');
        $response->headers->remove('X-Powered-By');
    }

    /**
     * Add CORS headers if needed
     */
    private function addCorsHeaders(Response $response): void
    {
        // Only add CORS headers for API routes
        if ($this->isApiResponse($response)) {
            $response->headers->set('Access-Control-Allow-Origin', config('app.frontend_url', '*'));
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Access-Control-Max-Age', '86400');
        }
    }

    /**
     * Check if response is from API
     */
    private function isApiResponse(Response $response): bool
    {
        $contentType = $response->headers->get('Content-Type');
        return $contentType && str_contains($contentType, 'application/json');
    }
} 