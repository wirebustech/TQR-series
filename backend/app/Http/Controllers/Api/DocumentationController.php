<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    /**
     * Show API documentation
     */
    public function index()
    {
        return view('api.documentation');
    }

    /**
     * Get OpenAPI specification
     */
    public function specification()
    {
        $spec = [
            'openapi' => '3.0.0',
            'info' => [
                'title' => 'TQRS API',
                'description' => 'The Qualitative Research Series API - A comprehensive API for managing research content, webinars, users, and more.',
                'version' => '1.0.0',
                'contact' => [
                    'name' => 'TQRS Support',
                    'email' => 'support@tqrs.com',
                    'url' => 'https://tqrs.com'
                ],
                'license' => [
                    'name' => 'MIT',
                    'url' => 'https://opensource.org/licenses/MIT'
                ]
            ],
            'servers' => [
                [
                    'url' => url('/api/v1'),
                    'description' => 'Production server'
                ],
                [
                    'url' => 'http://localhost:8000/api/v1',
                    'description' => 'Development server'
                ]
            ],
            'paths' => [
                '/auth/login' => [
                    'post' => [
                        'tags' => ['Authentication'],
                        'summary' => 'User login',
                        'description' => 'Authenticate a user and return access token',
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'email' => [
                                                'type' => 'string',
                                                'format' => 'email',
                                                'example' => 'user@example.com'
                                            ],
                                            'password' => [
                                                'type' => 'string',
                                                'example' => 'password123'
                                            ]
                                        ],
                                        'required' => ['email', 'password']
                                    ]
                                ]
                            ]
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Login successful',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'success' => ['type' => 'boolean'],
                                                'token' => ['type' => 'string'],
                                                'user' => [
                                                    '$ref' => '#/components/schemas/User'
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            '401' => [
                                'description' => 'Invalid credentials'
                            ]
                        ]
                    ]
                ],
                '/users' => [
                    'get' => [
                        'tags' => ['Users'],
                        'summary' => 'Get all users',
                        'security' => [['bearerAuth' => []]],
                        'parameters' => [
                            [
                                'name' => 'page',
                                'in' => 'query',
                                'description' => 'Page number',
                                'required' => false,
                                'schema' => ['type' => 'integer', 'default' => 1]
                            ],
                            [
                                'name' => 'per_page',
                                'in' => 'query',
                                'description' => 'Items per page',
                                'required' => false,
                                'schema' => ['type' => 'integer', 'default' => 15]
                            ],
                            [
                                'name' => 'search',
                                'in' => 'query',
                                'description' => 'Search term',
                                'required' => false,
                                'schema' => ['type' => 'string']
                            ]
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'List of users',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'data' => [
                                                    'type' => 'array',
                                                    'items' => ['$ref' => '#/components/schemas/User']
                                                ],
                                                'meta' => ['$ref' => '#/components/schemas/PaginationMeta']
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'post' => [
                        'tags' => ['Users'],
                        'summary' => 'Create a new user',
                        'security' => [['bearerAuth' => []]],
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'name' => ['type' => 'string'],
                                            'email' => ['type' => 'string', 'format' => 'email'],
                                            'password' => ['type' => 'string'],
                                            'role' => ['type' => 'string', 'enum' => ['user', 'researcher', 'moderator', 'admin']],
                                            'organization' => ['type' => 'string']
                                        ],
                                        'required' => ['name', 'email', 'password']
                                    ]
                                ]
                            ]
                        ],
                        'responses' => [
                            '201' => [
                                'description' => 'User created successfully',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/User']
                                    ]
                                ]
                            ],
                            '422' => [
                                'description' => 'Validation error'
                            ]
                        ]
                    ]
                ],
                '/webinars' => [
                    'get' => [
                        'tags' => ['Webinars'],
                        'summary' => 'Get all webinars',
                        'parameters' => [
                            [
                                'name' => 'status',
                                'in' => 'query',
                                'description' => 'Filter by status',
                                'required' => false,
                                'schema' => [
                                    'type' => 'string',
                                    'enum' => ['draft', 'published', 'completed']
                                ]
                            ]
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'List of webinars',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'data' => [
                                                    'type' => 'array',
                                                    'items' => ['$ref' => '#/components/schemas/Webinar']
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                '/newsletter/subscribe' => [
                    'post' => [
                        'tags' => ['Newsletter'],
                        'summary' => 'Subscribe to newsletter',
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'email' => ['type' => 'string', 'format' => 'email'],
                                            'first_name' => ['type' => 'string'],
                                            'last_name' => ['type' => 'string']
                                        ],
                                        'required' => ['email']
                                    ]
                                ]
                            ]
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Subscription successful',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'success' => ['type' => 'boolean'],
                                                'message' => ['type' => 'string']
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                '/donations' => [
                    'post' => [
                        'tags' => ['Donations'],
                        'summary' => 'Submit a donation',
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'amount' => ['type' => 'number'],
                                            'donor_name' => ['type' => 'string'],
                                            'donor_email' => ['type' => 'string', 'format' => 'email'],
                                            'message' => ['type' => 'string'],
                                            'is_anonymous' => ['type' => 'boolean']
                                        ],
                                        'required' => ['amount']
                                    ]
                                ]
                            ]
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Donation submitted successfully',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'success' => ['type' => 'boolean'],
                                                'message' => ['type' => 'string']
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'components' => [
                'securitySchemes' => [
                    'bearerAuth' => [
                        'type' => 'http',
                        'scheme' => 'bearer',
                        'bearerFormat' => 'JWT'
                    ]
                ],
                'schemas' => [
                    'User' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'name' => ['type' => 'string'],
                            'email' => ['type' => 'string', 'format' => 'email'],
                            'role' => ['type' => 'string'],
                            'organization' => ['type' => 'string'],
                            'status' => ['type' => 'string'],
                            'email_verified_at' => ['type' => 'string', 'format' => 'date-time'],
                            'created_at' => ['type' => 'string', 'format' => 'date-time'],
                            'updated_at' => ['type' => 'string', 'format' => 'date-time']
                        ]
                    ],
                    'Webinar' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'title' => ['type' => 'string'],
                            'description' => ['type' => 'string'],
                            'scheduled_at' => ['type' => 'string', 'format' => 'date-time'],
                            'duration' => ['type' => 'integer'],
                            'max_attendees' => ['type' => 'integer'],
                            'status' => ['type' => 'string'],
                            'platform' => ['type' => 'string'],
                            'meeting_url' => ['type' => 'string'],
                            'tags' => ['type' => 'string'],
                            'created_at' => ['type' => 'string', 'format' => 'date-time'],
                            'updated_at' => ['type' => 'string', 'format' => 'date-time']
                        ]
                    ],
                    'Blog' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'title' => ['type' => 'string'],
                            'slug' => ['type' => 'string'],
                            'excerpt' => ['type' => 'string'],
                            'content' => ['type' => 'string'],
                            'is_published' => ['type' => 'boolean'],
                            'published_at' => ['type' => 'string', 'format' => 'date-time'],
                            'created_at' => ['type' => 'string', 'format' => 'date-time'],
                            'updated_at' => ['type' => 'string', 'format' => 'date-time']
                        ]
                    ],
                    'PaginationMeta' => [
                        'type' => 'object',
                        'properties' => [
                            'current_page' => ['type' => 'integer'],
                            'last_page' => ['type' => 'integer'],
                            'per_page' => ['type' => 'integer'],
                            'total' => ['type' => 'integer']
                        ]
                    ]
                ]
            ],
            'tags' => [
                [
                    'name' => 'Authentication',
                    'description' => 'User authentication endpoints'
                ],
                [
                    'name' => 'Users',
                    'description' => 'User management endpoints'
                ],
                [
                    'name' => 'Webinars',
                    'description' => 'Webinar management endpoints'
                ],
                [
                    'name' => 'Blogs',
                    'description' => 'Blog post endpoints'
                ],
                [
                    'name' => 'Newsletter',
                    'description' => 'Newsletter subscription endpoints'
                ],
                [
                    'name' => 'Donations',
                    'description' => 'Donation management endpoints'
                ]
            ]
        ];

        return response()->json($spec);
    }
} 