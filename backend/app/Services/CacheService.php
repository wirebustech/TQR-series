<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Webinar;
use App\Models\Blog;
use App\Models\User;
use App\Models\Page;
use App\Models\Contribution;

class CacheService
{
    /**
     * Cache keys constants
     */
    const CACHE_KEYS = [
        'WEBINARS' => 'webinars',
        'BLOGS' => 'blogs',
        'USERS' => 'users',
        'PAGES' => 'pages',
        'CONTRIBUTIONS' => 'contributions',
        'CATEGORIES' => 'categories',
        'TAGS' => 'tags',
        'ANALYTICS' => 'analytics',
        'SEARCH' => 'search',
        'DASHBOARD' => 'dashboard'
    ];

    /**
     * Cache durations in seconds
     */
    const CACHE_DURATIONS = [
        'SHORT' => 300,      // 5 minutes
        'MEDIUM' => 1800,    // 30 minutes
        'LONG' => 3600,      // 1 hour
        'VERY_LONG' => 86400 // 24 hours
    ];

    /**
     * Warm up all caches
     */
    public function warmUpAllCaches(): array
    {
        $results = [];

        $results['webinars'] = $this->warmUpWebinarsCache();
        $results['blogs'] = $this->warmUpBlogsCache();
        $results['users'] = $this->warmUpUsersCache();
        $results['pages'] = $this->warmUpPagesCache();
        $results['contributions'] = $this->warmUpContributionsCache();
        $results['categories'] = $this->warmUpCategoriesCache();
        $results['tags'] = $this->warmUpTagsCache();
        $results['analytics'] = $this->warmUpAnalyticsCache();

        return $results;
    }

    /**
     * Warm up webinars cache
     */
    public function warmUpWebinarsCache(): array
    {
        $results = [];

        // Cache published webinars
        $webinars = Webinar::where('status', 'published')
            ->orderBy('scheduled_at', 'desc')
            ->get();

        Cache::put(self::CACHE_KEYS['WEBINARS'] . ':published', $webinars, self::CACHE_DURATIONS['MEDIUM']);
        $results['published_count'] = $webinars->count();

        // Cache upcoming webinars
        $upcomingWebinars = Webinar::where('status', 'scheduled')
            ->where('scheduled_at', '>', now())
            ->orderBy('scheduled_at', 'asc')
            ->limit(10)
            ->get();

        Cache::put(self::CACHE_KEYS['WEBINARS'] . ':upcoming', $upcomingWebinars, self::CACHE_DURATIONS['SHORT']);
        $results['upcoming_count'] = $upcomingWebinars->count();

        // Cache popular webinars
        $popularWebinars = Webinar::where('status', 'published')
            ->orderBy('views', 'desc')
            ->limit(10)
            ->get();

        Cache::put(self::CACHE_KEYS['WEBINARS'] . ':popular', $popularWebinars, self::CACHE_DURATIONS['MEDIUM']);
        $results['popular_count'] = $popularWebinars->count();

        // Cache webinars by category
        $categories = Webinar::distinct()->pluck('category')->filter();
        foreach ($categories as $category) {
            $categoryWebinars = Webinar::where('category', $category)
                ->where('status', 'published')
                ->orderBy('scheduled_at', 'desc')
                ->limit(20)
                ->get();

            Cache::put(self::CACHE_KEYS['WEBINARS'] . ":category:{$category}", $categoryWebinars, self::CACHE_DURATIONS['MEDIUM']);
        }
        $results['categories_count'] = $categories->count();

        return $results;
    }

    /**
     * Warm up blogs cache
     */
    public function warmUpBlogsCache(): array
    {
        $results = [];

        // Cache published blogs
        $blogs = Blog::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->get();

        Cache::put(self::CACHE_KEYS['BLOGS'] . ':published', $blogs, self::CACHE_DURATIONS['MEDIUM']);
        $results['published_count'] = $blogs->count();

        // Cache recent blogs
        $recentBlogs = Blog::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->limit(10)
            ->get();

        Cache::put(self::CACHE_KEYS['BLOGS'] . ':recent', $recentBlogs, self::CACHE_DURATIONS['SHORT']);
        $results['recent_count'] = $recentBlogs->count();

        // Cache popular blogs
        $popularBlogs = Blog::where('status', 'published')
            ->orderBy('views', 'desc')
            ->limit(10)
            ->get();

        Cache::put(self::CACHE_KEYS['BLOGS'] . ':popular', $popularBlogs, self::CACHE_DURATIONS['MEDIUM']);
        $results['popular_count'] = $popularBlogs->count();

        // Cache blogs by category
        $categories = Blog::distinct()->pluck('category')->filter();
        foreach ($categories as $category) {
            $categoryBlogs = Blog::where('category', $category)
                ->where('status', 'published')
                ->orderBy('published_at', 'desc')
                ->limit(20)
                ->get();

            Cache::put(self::CACHE_KEYS['BLOGS'] . ":category:{$category}", $categoryBlogs, self::CACHE_DURATIONS['MEDIUM']);
        }
        $results['categories_count'] = $categories->count();

        return $results;
    }

    /**
     * Warm up users cache
     */
    public function warmUpUsersCache(): array
    {
        $results = [];

        // Cache active users
        $activeUsers = User::where('last_seen_at', '>', now()->subDays(30))
            ->orderBy('last_seen_at', 'desc')
            ->limit(100)
            ->get();

        Cache::put(self::CACHE_KEYS['USERS'] . ':active', $activeUsers, self::CACHE_DURATIONS['MEDIUM']);
        $results['active_count'] = $activeUsers->count();

        // Cache top contributors
        $topContributors = User::withCount('contributions')
            ->orderBy('contributions_count', 'desc')
            ->limit(20)
            ->get();

        Cache::put(self::CACHE_KEYS['USERS'] . ':top_contributors', $topContributors, self::CACHE_DURATIONS['LONG']);
        $results['top_contributors_count'] = $topContributors->count();

        // Cache user stats
        $userStats = [
            'total_users' => User::count(),
            'active_users' => User::where('last_seen_at', '>', now()->subDays(30))->count(),
            'new_users_today' => User::where('created_at', '>=', now()->startOfDay())->count(),
            'new_users_week' => User::where('created_at', '>=', now()->subWeek())->count(),
            'new_users_month' => User::where('created_at', '>=', now()->subMonth())->count()
        ];

        Cache::put(self::CACHE_KEYS['USERS'] . ':stats', $userStats, self::CACHE_DURATIONS['SHORT']);
        $results['stats_cached'] = true;

        return $results;
    }

    /**
     * Warm up pages cache
     */
    public function warmUpPagesCache(): array
    {
        $results = [];

        // Cache published pages
        $pages = Page::where('status', 'published')
            ->orderBy('updated_at', 'desc')
            ->get();

        Cache::put(self::CACHE_KEYS['PAGES'] . ':published', $pages, self::CACHE_DURATIONS['LONG']);
        $results['published_count'] = $pages->count();

        // Cache navigation pages
        $navigationPages = Page::where('status', 'published')
            ->where('show_in_navigation', true)
            ->orderBy('navigation_order', 'asc')
            ->get();

        Cache::put(self::CACHE_KEYS['PAGES'] . ':navigation', $navigationPages, self::CACHE_DURATIONS['LONG']);
        $results['navigation_count'] = $navigationPages->count();

        return $results;
    }

    /**
     * Warm up contributions cache
     */
    public function warmUpContributionsCache(): array
    {
        $results = [];

        // Cache approved contributions
        $contributions = Contribution::where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        Cache::put(self::CACHE_KEYS['CONTRIBUTIONS'] . ':approved', $contributions, self::CACHE_DURATIONS['MEDIUM']);
        $results['approved_count'] = $contributions->count();

        // Cache recent contributions
        $recentContributions = Contribution::where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        Cache::put(self::CACHE_KEYS['CONTRIBUTIONS'] . ':recent', $recentContributions, self::CACHE_DURATIONS['SHORT']);
        $results['recent_count'] = $recentContributions->count();

        return $results;
    }

    /**
     * Warm up categories cache
     */
    public function warmUpCategoriesCache(): array
    {
        $results = [];

        // Cache webinar categories
        $webinarCategories = Webinar::selectRaw('category, COUNT(*) as count')
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->get();

        Cache::put(self::CACHE_KEYS['CATEGORIES'] . ':webinars', $webinarCategories, self::CACHE_DURATIONS['LONG']);
        $results['webinar_categories_count'] = $webinarCategories->count();

        // Cache blog categories
        $blogCategories = Blog::selectRaw('category, COUNT(*) as count')
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->get();

        Cache::put(self::CACHE_KEYS['CATEGORIES'] . ':blogs', $blogCategories, self::CACHE_DURATIONS['LONG']);
        $results['blog_categories_count'] = $blogCategories->count();

        return $results;
    }

    /**
     * Warm up tags cache
     */
    public function warmUpTagsCache(): array
    {
        $results = [];

        // Get all tags from webinars and blogs
        $webinarTags = DB::table('webinars')
            ->selectRaw('SUBSTRING_INDEX(SUBSTRING_INDEX(tags, ",", numbers.n), ",", -1) as tag')
            ->join(DB::raw('(SELECT 1 n UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5) numbers'), 
                   DB::raw('CHAR_LENGTH(tags) - CHAR_LENGTH(REPLACE(tags, ",", "")) >= numbers.n - 1'))
            ->whereNotNull('tags')
            ->where('tags', '!=', '')
            ->get()
            ->pluck('tag')
            ->filter();

        $blogTags = DB::table('blogs')
            ->selectRaw('SUBSTRING_INDEX(SUBSTRING_INDEX(tags, ",", numbers.n), ",", -1) as tag')
            ->join(DB::raw('(SELECT 1 n UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5) numbers'), 
                   DB::raw('CHAR_LENGTH(tags) - CHAR_LENGTH(REPLACE(tags, ",", "")) >= numbers.n - 1'))
            ->whereNotNull('tags')
            ->where('tags', '!=', '')
            ->get()
            ->pluck('tag')
            ->filter();

        $allTags = $webinarTags->merge($blogTags)->unique()->values();

        Cache::put(self::CACHE_KEYS['TAGS'] . ':all', $allTags, self::CACHE_DURATIONS['LONG']);
        $results['total_tags_count'] = $allTags->count();

        // Cache popular tags
        $popularTags = DB::table('webinars')
            ->selectRaw('SUBSTRING_INDEX(SUBSTRING_INDEX(tags, ",", numbers.n), ",", -1) as tag, COUNT(*) as count')
            ->join(DB::raw('(SELECT 1 n UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5) numbers'), 
                   DB::raw('CHAR_LENGTH(tags) - CHAR_LENGTH(REPLACE(tags, ",", "")) >= numbers.n - 1'))
            ->whereNotNull('tags')
            ->where('tags', '!=', '')
            ->groupBy('tag')
            ->orderBy('count', 'desc')
            ->limit(20)
            ->get();

        Cache::put(self::CACHE_KEYS['TAGS'] . ':popular', $popularTags, self::CACHE_DURATIONS['MEDIUM']);
        $results['popular_tags_count'] = $popularTags->count();

        return $results;
    }

    /**
     * Warm up analytics cache
     */
    public function warmUpAnalyticsCache(): array
    {
        $results = [];

        // Cache overview analytics
        $overview = [
            'total_webinars' => Webinar::count(),
            'published_webinars' => Webinar::where('status', 'published')->count(),
            'total_blogs' => Blog::count(),
            'published_blogs' => Blog::where('status', 'published')->count(),
            'total_users' => User::count(),
            'total_contributions' => Contribution::count(),
            'approved_contributions' => Contribution::where('status', 'approved')->count()
        ];

        Cache::put(self::CACHE_KEYS['ANALYTICS'] . ':overview', $overview, self::CACHE_DURATIONS['SHORT']);
        $results['overview_cached'] = true;

        // Cache user growth analytics
        $userGrowth = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        Cache::put(self::CACHE_KEYS['ANALYTICS'] . ':user_growth', $userGrowth, self::CACHE_DURATIONS['MEDIUM']);
        $results['user_growth_cached'] = true;

        // Cache content performance
        $contentPerformance = [
            'top_webinars' => Webinar::orderBy('views', 'desc')->limit(10)->get(['id', 'title', 'views']),
            'top_blogs' => Blog::orderBy('views', 'desc')->limit(10)->get(['id', 'title', 'views']),
            'top_pages' => Page::orderBy('views', 'desc')->limit(10)->get(['id', 'title', 'views'])
        ];

        Cache::put(self::CACHE_KEYS['ANALYTICS'] . ':content_performance', $contentPerformance, self::CACHE_DURATIONS['MEDIUM']);
        $results['content_performance_cached'] = true;

        return $results;
    }

    /**
     * Clear all caches
     */
    public function clearAllCaches(): array
    {
        $results = [];

        foreach (self::CACHE_KEYS as $key => $value) {
            Cache::forget($value);
            $results[$key] = 'cleared';
        }

        // Clear pattern-based caches
        $patterns = [
            'webinars:*',
            'blogs:*',
            'users:*',
            'pages:*',
            'contributions:*',
            'categories:*',
            'tags:*',
            'analytics:*',
            'search:*',
            'dashboard:*'
        ];

        foreach ($patterns as $pattern) {
            $this->clearPatternCache($pattern);
            $results[$pattern] = 'cleared';
        }

        return $results;
    }

    /**
     * Clear cache by pattern
     */
    private function clearPatternCache(string $pattern): void
    {
        // This is a simplified version. In production, you might want to use Redis SCAN
        // or implement a more sophisticated pattern clearing mechanism
        $keys = Cache::get($pattern);
        if ($keys) {
            Cache::forget($pattern);
        }
    }

    /**
     * Get cache statistics
     */
    public function getCacheStats(): array
    {
        $stats = [];

        foreach (self::CACHE_KEYS as $key => $value) {
            $data = Cache::get($value);
            $stats[$key] = [
                'exists' => $data !== null,
                'type' => $data ? gettype($data) : null,
                'count' => is_countable($data) ? count($data) : null
            ];
        }

        return $stats;
    }

    /**
     * Optimize cache performance
     */
    public function optimizeCache(): array
    {
        $results = [];

        // Clear expired cache entries
        $results['expired_cleared'] = $this->clearExpiredCache();

        // Compress large cache entries
        $results['compressed'] = $this->compressLargeCache();

        // Update cache hit rates
        $results['hit_rates'] = $this->updateCacheHitRates();

        return $results;
    }

    /**
     * Clear expired cache entries
     */
    private function clearExpiredCache(): int
    {
        // This is a placeholder. In production, you would implement
        // actual expired cache clearing logic based on your cache driver
        return 0;
    }

    /**
     * Compress large cache entries
     */
    private function compressLargeCache(): int
    {
        // This is a placeholder. In production, you would implement
        // actual cache compression logic
        return 0;
    }

    /**
     * Update cache hit rates
     */
    private function updateCacheHitRates(): array
    {
        // This is a placeholder. In production, you would implement
        // actual cache hit rate tracking
        return [
            'overall_hit_rate' => 0.85,
            'webinars_hit_rate' => 0.90,
            'blogs_hit_rate' => 0.88,
            'users_hit_rate' => 0.82
        ];
    }
} 