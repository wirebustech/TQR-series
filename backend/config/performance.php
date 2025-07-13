<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Performance Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains performance-related configuration settings for the
    | TQRS application, including caching, optimization, and monitoring.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Query Caching
    |--------------------------------------------------------------------------
    |
    | Enable query caching to improve database performance. This setting
    | controls whether database queries should be cached automatically.
    |
    */

    'query_cache' => [
        'enabled' => env('QUERY_CACHE_ENABLED', true),
        'ttl' => env('QUERY_CACHE_TTL', 3600), // Cache for 1 hour by default
        'prefix' => env('QUERY_CACHE_PREFIX', 'tqrs_query_'),
        'tags' => [
            'users' => 'users',
            'pages' => 'pages',
            'blogs' => 'blogs',
            'webinars' => 'webinars',
            'opportunities' => 'opportunities',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | API Response Caching
    |--------------------------------------------------------------------------
    |
    | Configuration for caching API responses to improve performance
    | for frequently accessed endpoints.
    |
    */

    'api_cache' => [
        'enabled' => env('API_CACHE_ENABLED', true),
        'default_ttl' => env('API_CACHE_TTL', 1800), // 30 minutes
        'public_endpoints' => [
            'articles' => 3600,        // 1 hour
            'webinars' => 1800,        // 30 minutes
            'opportunities' => 900,     // 15 minutes
            'pages' => 7200,           // 2 hours
            'health' => 300,           // 5 minutes
        ],
        'authenticated_endpoints' => [
            'analytics' => 600,        // 10 minutes
            'dashboard' => 300,        // 5 minutes
            'user_profile' => 1800,    // 30 minutes
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Optimization
    |--------------------------------------------------------------------------
    |
    | Settings for database performance optimization including connection
    | pooling, query optimization, and indexing strategies.
    |
    */

    'database' => [
        'connection_pooling' => env('DB_CONNECTION_POOLING', true),
        'max_connections' => env('DB_MAX_CONNECTIONS', 100),
        'query_timeout' => env('DB_QUERY_TIMEOUT', 30),
        'slow_query_threshold' => env('DB_SLOW_QUERY_THRESHOLD', 1000), // milliseconds
        'log_slow_queries' => env('DB_LOG_SLOW_QUERIES', true),
        'eager_loading' => [
            'enabled' => true,
            'default_relations' => [
                'users' => ['profile'],
                'pages' => ['creator', 'updater'],
                'blogs' => ['author', 'category', 'tags'],
                'webinars' => ['presenter'],
            ],
        ],
        'pagination' => [
            'max_per_page' => env('DB_MAX_PER_PAGE', 100),
            'default_per_page' => env('DB_DEFAULT_PER_PAGE', 15),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Optimization
    |--------------------------------------------------------------------------
    |
    | Configuration for optimizing file uploads including compression,
    | resizing, and storage strategies.
    |
    */

    'file_upload' => [
        'max_file_size' => env('MAX_FILE_SIZE', 10485760), // 10MB
        'allowed_extensions' => [
            'images' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
            'documents' => ['pdf', 'doc', 'docx', 'txt'],
            'media' => ['mp4', 'mov', 'avi', 'mkv'],
        ],
        'image_optimization' => [
            'enabled' => env('IMAGE_OPTIMIZATION_ENABLED', true),
            'quality' => env('IMAGE_QUALITY', 85),
            'max_width' => env('IMAGE_MAX_WIDTH', 1920),
            'max_height' => env('IMAGE_MAX_HEIGHT', 1080),
            'thumbnails' => [
                'small' => ['width' => 150, 'height' => 150],
                'medium' => ['width' => 300, 'height' => 300],
                'large' => ['width' => 600, 'height' => 600],
            ],
        ],
        'compression' => [
            'enabled' => env('FILE_COMPRESSION_ENABLED', true),
            'level' => env('FILE_COMPRESSION_LEVEL', 6),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Optimization
    |--------------------------------------------------------------------------
    |
    | Configuration for session handling and optimization to improve
    | performance and reduce server load.
    |
    */

    'session' => [
        'driver' => env('SESSION_DRIVER', 'redis'),
        'lifetime' => env('SESSION_LIFETIME', 120), // minutes
        'gc_probability' => env('SESSION_GC_PROBABILITY', 1),
        'gc_divisor' => env('SESSION_GC_DIVISOR', 100),
        'cookie_secure' => env('SESSION_SECURE_COOKIE', true),
        'cookie_httponly' => env('SESSION_HTTPONLY_COOKIE', true),
        'cookie_samesite' => env('SESSION_SAME_SITE', 'lax'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configuration for API rate limiting to prevent abuse and ensure
    | fair usage of system resources.
    |
    */

    'rate_limiting' => [
        'enabled' => env('RATE_LIMITING_ENABLED', true),
        'default_limit' => env('RATE_LIMIT_DEFAULT', 60), // requests per minute
        'api_limits' => [
            'public' => [
                'limit' => env('RATE_LIMIT_PUBLIC', 100),
                'window' => 60, // seconds
            ],
            'authenticated' => [
                'limit' => env('RATE_LIMIT_AUTHENTICATED', 200),
                'window' => 60, // seconds
            ],
            'admin' => [
                'limit' => env('RATE_LIMIT_ADMIN', 500),
                'window' => 60, // seconds
            ],
        ],
        'throttle_by' => 'ip', // 'ip' or 'user'
    ],

    /*
    |--------------------------------------------------------------------------
    | Memory Management
    |--------------------------------------------------------------------------
    |
    | Configuration for memory usage optimization and garbage collection
    | to prevent memory leaks and improve performance.
    |
    */

    'memory' => [
        'limit' => env('MEMORY_LIMIT', '256M'),
        'gc_enabled' => env('MEMORY_GC_ENABLED', true),
        'gc_probability' => env('MEMORY_GC_PROBABILITY', 1),
        'gc_divisor' => env('MEMORY_GC_DIVISOR', 100),
        'max_execution_time' => env('MAX_EXECUTION_TIME', 30),
    ],

    /*
    |--------------------------------------------------------------------------
    | CDN Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Content Delivery Network integration to improve
    | static asset delivery performance.
    |
    */

    'cdn' => [
        'enabled' => env('CDN_ENABLED', false),
        'url' => env('CDN_URL', ''),
        'assets' => [
            'css' => env('CDN_CSS_PATH', '/css'),
            'js' => env('CDN_JS_PATH', '/js'),
            'images' => env('CDN_IMAGES_PATH', '/images'),
            'media' => env('CDN_MEDIA_PATH', '/media'),
        ],
        'cache_control' => [
            'max_age' => env('CDN_MAX_AGE', 86400), // 24 hours
            'public' => env('CDN_PUBLIC_CACHE', true),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Compression
    |--------------------------------------------------------------------------
    |
    | Configuration for response compression to reduce bandwidth usage
    | and improve page load times.
    |
    */

    'compression' => [
        'enabled' => env('COMPRESSION_ENABLED', true),
        'level' => env('COMPRESSION_LEVEL', 6),
        'min_length' => env('COMPRESSION_MIN_LENGTH', 1024),
        'mime_types' => [
            'text/html',
            'text/css',
            'text/javascript',
            'text/xml',
            'text/plain',
            'application/javascript',
            'application/xml',
            'application/json',
            'application/rss+xml',
            'application/atom+xml',
            'image/svg+xml',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Monitoring and Logging
    |--------------------------------------------------------------------------
    |
    | Configuration for performance monitoring and logging to track
    | application performance and identify bottlenecks.
    |
    */

    'monitoring' => [
        'enabled' => env('PERFORMANCE_MONITORING_ENABLED', true),
        'slow_request_threshold' => env('SLOW_REQUEST_THRESHOLD', 2000), // milliseconds
        'log_slow_requests' => env('LOG_SLOW_REQUESTS', true),
        'track_memory_usage' => env('TRACK_MEMORY_USAGE', true),
        'track_query_count' => env('TRACK_QUERY_COUNT', true),
        'performance_log_channel' => env('PERFORMANCE_LOG_CHANNEL', 'performance'),
    ],

    /*
    |--------------------------------------------------------------------------
    | WebSocket Performance
    |--------------------------------------------------------------------------
    |
    | Configuration for WebSocket connection optimization and performance
    | monitoring.
    |
    */

    'websocket' => [
        'max_connections' => env('WS_MAX_CONNECTIONS', 1000),
        'ping_interval' => env('WS_PING_INTERVAL', 30), // seconds
        'ping_timeout' => env('WS_PING_TIMEOUT', 10), // seconds
        'message_max_size' => env('WS_MESSAGE_MAX_SIZE', 1048576), // 1MB
        'connection_timeout' => env('WS_CONNECTION_TIMEOUT', 30), // seconds
        'heartbeat_enabled' => env('WS_HEARTBEAT_ENABLED', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue Performance
    |--------------------------------------------------------------------------
    |
    | Configuration for queue processing optimization to handle background
    | jobs efficiently.
    |
    */

    'queue' => [
        'max_jobs_per_batch' => env('QUEUE_MAX_JOBS_PER_BATCH', 100),
        'batch_timeout' => env('QUEUE_BATCH_TIMEOUT', 300), // seconds
        'retry_after' => env('QUEUE_RETRY_AFTER', 90), // seconds
        'max_tries' => env('QUEUE_MAX_TRIES', 3),
        'memory_limit' => env('QUEUE_MEMORY_LIMIT', '128M'),
        'sleep_on_empty' => env('QUEUE_SLEEP_ON_EMPTY', 3), // seconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Search Performance
    |--------------------------------------------------------------------------
    |
    | Configuration for search functionality optimization including
    | full-text search and indexing strategies.
    |
    */

    'search' => [
        'enabled' => env('SEARCH_ENABLED', true),
        'engine' => env('SEARCH_ENGINE', 'mysql'), // 'mysql', 'elasticsearch', 'algolia'
        'index_on_save' => env('SEARCH_INDEX_ON_SAVE', true),
        'min_search_length' => env('SEARCH_MIN_LENGTH', 3),
        'max_results' => env('SEARCH_MAX_RESULTS', 100),
        'highlight_enabled' => env('SEARCH_HIGHLIGHT_ENABLED', true),
        'cache_results' => env('SEARCH_CACHE_RESULTS', true),
        'cache_ttl' => env('SEARCH_CACHE_TTL', 1800), // 30 minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | Asset Optimization
    |--------------------------------------------------------------------------
    |
    | Configuration for frontend asset optimization including minification,
    | concatenation, and caching strategies.
    |
    */

    'assets' => [
        'minification' => [
            'enabled' => env('ASSET_MINIFICATION_ENABLED', true),
            'css' => env('CSS_MINIFICATION_ENABLED', true),
            'js' => env('JS_MINIFICATION_ENABLED', true),
        ],
        'concatenation' => [
            'enabled' => env('ASSET_CONCATENATION_ENABLED', true),
            'css' => env('CSS_CONCATENATION_ENABLED', true),
            'js' => env('JS_CONCATENATION_ENABLED', true),
        ],
        'versioning' => [
            'enabled' => env('ASSET_VERSIONING_ENABLED', true),
            'strategy' => env('ASSET_VERSION_STRATEGY', 'hash'), // 'hash' or 'timestamp'
        ],
        'lazy_loading' => [
            'enabled' => env('LAZY_LOADING_ENABLED', true),
            'images' => env('LAZY_LOAD_IMAGES', true),
            'videos' => env('LAZY_LOAD_VIDEOS', true),
        ],
    ],

]; 