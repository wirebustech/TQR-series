# Sitemap API Documentation

The TQRS Sitemap API provides comprehensive sitemap generation and management capabilities for SEO optimization.

## Overview

The sitemap system supports multiple sitemap types:
- **Main Sitemap**: Standard XML sitemap with all website URLs
- **Image Sitemap**: Dedicated sitemap for images with metadata
- **News Sitemap**: Sitemap for recent blog posts and news content
- **Sitemap Index**: For large sites with multiple sitemap files

## API Endpoints

### Public Endpoints

#### GET /api/sitemap/status
Get the status of all sitemap files.

**Response:**
```json
{
  "success": true,
  "sitemaps": {
    "main": {
      "exists": true,
      "filename": "sitemap.xml",
      "total_urls": 25,
      "file_size": 2048,
      "last_modified": "2025-01-13 10:30:00",
      "file_path": "/path/to/sitemap.xml",
      "hours_old": 2.5
    },
    "images": {
      "exists": false,
      "filename": "sitemap-images.xml",
      "message": "Sitemap not found"
    },
    "news": {
      "exists": true,
      "filename": "sitemap-news.xml",
      "total_urls": 5,
      "file_size": 512,
      "last_modified": "2025-01-13 09:15:00",
      "file_path": "/path/to/sitemap-news.xml",
      "hours_old": 3.25
    }
  },
  "summary": {
    "total_sitemaps": 2,
    "total_urls": 30,
    "total_size": 2560,
    "last_updated": "2025-01-13 10:30:00"
  }
}
```

#### GET /api/sitemap/stats
Get detailed statistics about all sitemaps.

**Response:**
```json
{
  "success": true,
  "stats": {
    "total_sitemaps": 2,
    "total_urls": 30,
    "total_size": 2560,
    "oldest_sitemap": {
      "type": "news",
      "timestamp": 1705140900,
      "date": "2025-01-13 09:15:00"
    },
    "newest_sitemap": {
      "type": "main",
      "timestamp": 1705144200,
      "date": "2025-01-13 10:30:00"
    },
    "sitemap_types": {
      "main": {
        "urls": 25,
        "size": 2048,
        "last_modified": "2025-01-13 10:30:00",
        "age_hours": 2.5
      },
      "news": {
        "urls": 5,
        "size": 512,
        "last_modified": "2025-01-13 09:15:00",
        "age_hours": 3.25
      }
    }
  }
}
```

### Protected Endpoints (Require Authentication)

#### POST /api/sitemap/generate
Generate sitemap(s) via API.

**Request Body:**
```json
{
  "type": "main|images|news|all",
  "force": false
}
```

**Parameters:**
- `type` (string): Type of sitemap to generate
  - `main`: Standard sitemap with all URLs
  - `images`: Image sitemap with image metadata
  - `news`: News sitemap for recent content
  - `all`: Generate all sitemap types
- `force` (boolean): Force regeneration even if recent (default: false)

**Response:**
```json
{
  "success": true,
  "message": "Sitemap(s) generated successfully",
  "results": {
    "main": {
      "type": "main",
      "filename": "sitemap.xml",
      "file_path": "/path/to/sitemap.xml",
      "total_urls": 25,
      "file_size": 2048,
      "output": "Main sitemap generated successfully with 25 URLs"
    }
  },
  "generated_at": "2025-01-13T10:30:00.000000Z"
}
```

#### POST /api/sitemap/validate
Validate sitemap XML structure and content.

**Request Body:**
```json
{
  "type": "main|images|news"
}
```

**Response:**
```json
{
  "success": true,
  "type": "main",
  "filename": "sitemap.xml",
  "validation": {
    "valid": true,
    "errors": [],
    "warnings": ["Sitemap contains more than 50,000 URLs (consider splitting)"],
    "url_count": 25000,
    "file_size": 1048576
  }
}
```

## Sitemap Types

### Main Sitemap (sitemap.xml)
Standard XML sitemap containing:
- Static pages (home, about, contact, etc.)
- Blog posts (published only)
- Webinars (public only)
- Dynamic pages (published only)

**Example URL entry:**
```xml
<url>
  <loc>https://example.com/blog/getting-started</loc>
  <lastmod>2025-01-13T08:55:13.000000Z</lastmod>
  <changefreq>weekly</changefreq>
  <priority>0.7</priority>
</url>
```

### Image Sitemap (sitemap-images.xml)
Dedicated sitemap for images with metadata:
- Images from media library
- Featured images from blog posts
- Image titles and captions

**Example URL entry:**
```xml
<url>
  <loc>https://example.com/blog/research-methods</loc>
  <image:image>
    <image:loc>https://example.com/images/research.jpg</image:loc>
    <image:title>Research Methods Overview</image:title>
    <image:caption>Visual guide to qualitative research methods</image:caption>
  </image:image>
</url>
```

### News Sitemap (sitemap-news.xml)
Sitemap for recent news content:
- Blog posts from the last 2 days
- Publication metadata
- News-specific tags

**Example URL entry:**
```xml
<url>
  <loc>https://example.com/blog/latest-research</loc>
  <news:news>
    <news:publication>
      <news:name>The Qualitative Research Series</news:name>
      <news:language>en</news:language>
    </news:publication>
    <news:publication_date>2025-01-13T08:55:13.000000Z</news:publication_date>
    <news:title>Latest Research Findings</news:title>
    <news:keywords>research, findings, qualitative</news:keywords>
  </news:news>
</url>
```

## Command Line Usage

### Generate Sitemaps
```bash
# Generate main sitemap
php artisan sitemap:generate

# Generate image sitemap
php artisan sitemap:generate --type=images

# Generate news sitemap
php artisan sitemap:generate --type=news

# Generate all sitemap types
php artisan sitemap:generate --type=all
```

### Sitemap Index
For sites with more than 50,000 URLs, the system automatically creates a sitemap index:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <sitemap>
    <loc>https://example.com/sitemap-1.xml</loc>
    <lastmod>2025-01-13T10:30:00.000000Z</lastmod>
  </sitemap>
  <sitemap>
    <loc>https://example.com/sitemap-2.xml</loc>
    <lastmod>2025-01-13T10:30:00.000000Z</lastmod>
  </sitemap>
</sitemapindex>
```

## Configuration

### Static Pages
Static pages are defined in the `GenerateSitemap` command:

```php
$staticPages = [
    '/' => ['priority' => '1.0', 'changefreq' => 'daily'],
    '/blogs' => ['priority' => '0.8', 'changefreq' => 'daily'],
    '/webinars' => ['priority' => '0.8', 'changefreq' => 'daily'],
    '/about' => ['priority' => '0.6', 'changefreq' => 'monthly'],
    '/contact' => ['priority' => '0.5', 'changefreq' => 'monthly'],
    '/privacy' => ['priority' => '0.3', 'changefreq' => 'yearly'],
    '/terms' => ['priority' => '0.3', 'changefreq' => 'yearly'],
    '/research' => ['priority' => '0.7', 'changefreq' => 'weekly'],
    '/contributions' => ['priority' => '0.6', 'changefreq' => 'weekly'],
];
```

### Content Types
- **Blogs**: Only published blogs are included
- **Webinars**: Only public webinars are included
- **Pages**: Only published dynamic pages are included
- **Images**: Only public images from media library and blog featured images

## Error Handling

### Common Error Responses

**Validation Error (422):**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "type": ["The type field must be one of: main, images, news, all."]
  }
}
```

**Authentication Error (401):**
```json
{
  "message": "Unauthenticated."
}
```

**File Not Found (404):**
```json
{
  "success": false,
  "message": "Sitemap sitemap-images.xml not found"
}
```

**Server Error (500):**
```json
{
  "success": false,
  "message": "Error generating sitemap: File write error",
  "trace": "Stack trace (only in debug mode)"
}
```

## Best Practices

### 1. Regular Generation
- Generate sitemaps regularly (daily or weekly)
- Use the `force` parameter sparingly
- Monitor sitemap age and regenerate when needed

### 2. Validation
- Always validate sitemaps after generation
- Check for XML structure errors
- Monitor URL count and file size

### 3. Performance
- For large sites, use sitemap indexing
- Consider splitting sitemaps by content type
- Monitor generation time and optimize if needed

### 4. SEO
- Submit sitemaps to search engines
- Include all important pages
- Use appropriate priorities and change frequencies
- Keep sitemaps up to date

## Testing

Use the provided test script to verify functionality:

```bash
php test_sitemap_api.php
```

The test script covers:
- Status and statistics endpoints
- Sitemap generation for all types
- Validation functionality
- Error handling
- Force generation

## Monitoring

Monitor sitemap health through:
- Regular status checks
- Statistics tracking
- Validation results
- Error logging

## Troubleshooting

### Common Issues

1. **Sitemap not generating**: Check file permissions and disk space
2. **Invalid XML**: Validate sitemap structure
3. **Missing URLs**: Verify content is published/public
4. **Large file size**: Consider splitting into multiple sitemaps
5. **Authentication errors**: Verify API token and permissions

### Debug Mode
Enable debug mode to see detailed error traces:
```php
APP_DEBUG=true
```

## Integration

### Search Engine Submission
Submit sitemaps to major search engines:
- Google Search Console
- Bing Webmaster Tools
- Yandex Webmaster

### Automated Generation
Set up automated sitemap generation:
```bash
# Add to crontab for daily generation
0 2 * * * cd /path/to/project && php artisan sitemap:generate
```

### Webhook Integration
Configure webhooks to regenerate sitemaps when content changes:
- Blog post published/unpublished
- Webinar status changed
- Page content updated 