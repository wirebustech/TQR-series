<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Blog;
use App\Models\Webinar;
use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test user for authentication
        $this->user = User::factory()->create([
            'role' => 'admin'
        ]);
    }

    /** @test */
    public function it_can_generate_sitemap_via_api()
    {
        // Create test data
        $blog = Blog::factory()->create([
            'is_published' => true,
            'slug' => 'test-blog'
        ]);
        
        $webinar = Webinar::factory()->create([
            'is_public' => true
        ]);
        
        $page = Page::factory()->create([
            'is_published' => true,
            'slug' => 'test-page'
        ]);

        $response = $this->actingAs($this->user)
            ->postJson('/api/sitemap/generate');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Sitemap generated successfully'
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'total_urls',
                'file_path',
                'generated_at'
            ]);

        // Check that sitemap file was created
        $this->assertTrue(File::exists(public_path('sitemap.xml')));
        
        // Check sitemap content
        $sitemapContent = File::get(public_path('sitemap.xml'));
        $this->assertStringContainsString('test-blog', $sitemapContent);
        $this->assertStringContainsString('test-page', $sitemapContent);
    }

    /** @test */
    public function it_can_get_sitemap_status()
    {
        // Generate sitemap first
        $this->actingAs($this->user)
            ->postJson('/api/sitemap/generate');

        $response = $this->getJson('/api/sitemap/status');

        $response->assertStatus(200)
            ->assertJson([
                'exists' => true
            ])
            ->assertJsonStructure([
                'exists',
                'total_urls',
                'file_size',
                'last_modified',
                'file_path'
            ]);
    }

    /** @test */
    public function it_returns_not_found_when_sitemap_does_not_exist()
    {
        // Remove sitemap if it exists
        if (File::exists(public_path('sitemap.xml'))) {
            File::delete(public_path('sitemap.xml'));
        }

        $response = $this->getJson('/api/sitemap/status');

        $response->assertStatus(200)
            ->assertJson([
                'exists' => false,
                'message' => 'Sitemap not found'
            ]);
    }

    /** @test */
    public function it_includes_only_published_content_in_sitemap()
    {
        // Create published and unpublished content
        $publishedBlog = Blog::factory()->create([
            'is_published' => true,
            'slug' => 'published-blog'
        ]);
        
        $unpublishedBlog = Blog::factory()->create([
            'is_published' => false,
            'slug' => 'unpublished-blog'
        ]);
        
        $publishedWebinar = Webinar::factory()->create([
            'is_public' => true
        ]);
        
        $unpublishedWebinar = Webinar::factory()->create([
            'is_public' => false
        ]);

        $this->actingAs($this->user)
            ->postJson('/api/sitemap/generate');

        $sitemapContent = File::get(public_path('sitemap.xml'));
        
        // Should include published content
        $this->assertStringContainsString('published-blog', $sitemapContent);
        
        // Should not include unpublished content
        $this->assertStringNotContainsString('unpublished-blog', $sitemapContent);
    }

    /** @test */
    public function it_requires_authentication_for_generation()
    {
        $response = $this->postJson('/api/sitemap/generate');

        $response->assertStatus(401);
    }

    /** @test */
    public function it_generates_valid_xml_sitemap()
    {
        $this->actingAs($this->user)
            ->postJson('/api/sitemap/generate');

        $sitemapContent = File::get(public_path('sitemap.xml'));
        
        // Check XML structure
        $this->assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $sitemapContent);
        $this->assertStringContainsString('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', $sitemapContent);
        $this->assertStringContainsString('</urlset>', $sitemapContent);
        
        // Check for required URL elements
        $this->assertStringContainsString('<loc>', $sitemapContent);
        $this->assertStringContainsString('<changefreq>', $sitemapContent);
        $this->assertStringContainsString('<priority>', $sitemapContent);
    }

    /** @test */
    public function it_includes_static_pages_in_sitemap()
    {
        $this->actingAs($this->user)
            ->postJson('/api/sitemap/generate');

        $sitemapContent = File::get(public_path('sitemap.xml'));
        
        // Check for static pages
        $this->assertStringContainsString('/blogs', $sitemapContent);
        $this->assertStringContainsString('/webinars', $sitemapContent);
        $this->assertStringContainsString('/about', $sitemapContent);
        $this->assertStringContainsString('/contact', $sitemapContent);
        $this->assertStringContainsString('/privacy', $sitemapContent);
    }

    /** @test */
    public function it_handles_errors_gracefully()
    {
        // Mock a scenario where file writing fails
        $this->mock(\Illuminate\Support\Facades\File::class, function ($mock) {
            $mock->shouldReceive('put')->andThrow(new \Exception('File write error'));
        });

        $response = $this->actingAs($this->user)
            ->postJson('/api/sitemap/generate');

        $response->assertStatus(500)
            ->assertJson([
                'success' => false
            ])
            ->assertJsonStructure([
                'success',
                'message'
            ]);
    }
} 