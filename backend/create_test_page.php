<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Page;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $page = Page::create([
        'title' => 'Test Dynamic Page',
        'slug' => 'test-dynamic-page',
        'description' => 'This is a test page to verify the dynamic page system works correctly.',
        'content' => '# Welcome to Our Test Page

This is a **test page** created to demonstrate the dynamic page system.

## Features

- Dynamic content loading from API
- Multi-language support  
- SEO-friendly URLs
- Responsive design

## How It Works

The page content is fetched from the backend API and displayed using a dynamic template system.

*Thank you for testing our system!*',
        'meta_title' => 'Test Dynamic Page - TQRS',
        'meta_description' => 'A test page demonstrating the dynamic page system with multi-language support and SEO optimization.',
        'meta_keywords' => 'test, dynamic, page, TQRS, qualitative research',
        'is_published' => true,
        'language' => 'en'
    ]);
    
    echo "Page created successfully with ID: " . $page->id . "\n";
    echo "Slug: " . $page->slug . "\n";
    echo "Title: " . $page->title . "\n";
    
} catch (Exception $e) {
    echo "Error creating page: " . $e->getMessage() . "\n";
} 