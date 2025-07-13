<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Webinar;
use App\Models\Blog;
use App\Models\User;
use App\Models\Page;
use App\Models\Contribution;

class SearchController extends Controller
{
    /**
     * Advanced search across all content types
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:2|max:100',
            'type' => 'nullable|string|in:all,webinars,blogs,users,pages,contributions',
            'category' => 'nullable|string',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'sort' => 'nullable|string|in:relevance,date,title,popularity',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:50'
        ]);

        $query = $request->input('query');
        $type = $request->input('type', 'all');
        $category = $request->input('category');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $sort = $request->input('sort', 'relevance');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 12);

        // Cache search results for performance
        $cacheKey = "search_{$query}_{$type}_{$category}_{$dateFrom}_{$dateTo}_{$sort}_{$page}_{$perPage}";
        
        return Cache::remember($cacheKey, 300, function () use ($query, $type, $category, $dateFrom, $dateTo, $sort, $page, $perPage) {
            $results = [];
            $totalResults = 0;

            // Search webinars
            if ($type === 'all' || $type === 'webinars') {
                $webinarResults = $this->searchWebinars($query, $category, $dateFrom, $dateTo, $sort);
                $results['webinars'] = $webinarResults;
                $totalResults += $webinarResults['total'];
            }

            // Search blogs
            if ($type === 'all' || $type === 'blogs') {
                $blogResults = $this->searchBlogs($query, $category, $dateFrom, $dateTo, $sort);
                $results['blogs'] = $blogResults;
                $totalResults += $blogResults['total'];
            }

            // Search users
            if ($type === 'all' || $type === 'users') {
                $userResults = $this->searchUsers($query, $sort);
                $results['users'] = $userResults;
                $totalResults += $userResults['total'];
            }

            // Search pages
            if ($type === 'all' || $type === 'pages') {
                $pageResults = $this->searchPages($query, $sort);
                $results['pages'] = $pageResults;
                $totalResults += $pageResults['total'];
            }

            // Search contributions
            if ($type === 'all' || $type === 'contributions') {
                $contributionResults = $this->searchContributions($query, $category, $dateFrom, $dateTo, $sort);
                $results['contributions'] = $contributionResults;
                $totalResults += $contributionResults['total'];
            }

            // Get search suggestions
            $suggestions = $this->getSearchSuggestions($query);

            return response()->json([
                'success' => true,
                'data' => [
                    'results' => $results,
                    'total_results' => $totalResults,
                    'suggestions' => $suggestions,
                    'filters' => [
                        'query' => $query,
                        'type' => $type,
                        'category' => $category,
                        'date_from' => $dateFrom,
                        'date_to' => $dateTo,
                        'sort' => $sort
                    ],
                    'pagination' => [
                        'current_page' => $page,
                        'per_page' => $perPage,
                        'total_pages' => ceil($totalResults / $perPage)
                    ]
                ]
            ]);
        });
    }

    /**
     * Search webinars with advanced filters
     */
    private function searchWebinars(string $query, ?string $category, ?string $dateFrom, ?string $dateTo, string $sort): array
    {
        $webinars = Webinar::where(function ($q) use ($query) {
            $q->where('title', 'LIKE', "%{$query}%")
              ->orWhere('description', 'LIKE', "%{$query}%")
              ->orWhere('tags', 'LIKE', "%{$query}%")
              ->orWhere('speaker_name', 'LIKE', "%{$query}%");
        });

        if ($category) {
            $webinars->where('category', $category);
        }

        if ($dateFrom) {
            $webinars->where('scheduled_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $webinars->where('scheduled_at', '<=', $dateTo);
        }

        // Apply sorting
        switch ($sort) {
            case 'date':
                $webinars->orderBy('scheduled_at', 'desc');
                break;
            case 'title':
                $webinars->orderBy('title', 'asc');
                break;
            case 'popularity':
                $webinars->orderBy('views', 'desc');
                break;
            default: // relevance
                $webinars->orderByRaw("
                    CASE 
                        WHEN title LIKE ? THEN 3
                        WHEN description LIKE ? THEN 2
                        WHEN tags LIKE ? THEN 1
                        ELSE 0
                    END DESC
                ", ["%{$query}%", "%{$query}%", "%{$query}%"]);
        }

        $results = $webinars->paginate(12);
        
        return [
            'data' => $results->items(),
            'total' => $results->total(),
            'current_page' => $results->currentPage(),
            'per_page' => $results->perPage()
        ];
    }

    /**
     * Search blogs with advanced filters
     */
    private function searchBlogs(string $query, ?string $category, ?string $dateFrom, ?string $dateTo, string $sort): array
    {
        $blogs = Blog::where(function ($q) use ($query) {
            $q->where('title', 'LIKE', "%{$query}%")
              ->orWhere('content', 'LIKE', "%{$query}%")
              ->orWhere('tags', 'LIKE', "%{$query}%")
              ->orWhere('author', 'LIKE', "%{$query}%");
        });

        if ($category) {
            $blogs->where('category', $category);
        }

        if ($dateFrom) {
            $blogs->where('published_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $blogs->where('published_at', '<=', $dateTo);
        }

        // Apply sorting
        switch ($sort) {
            case 'date':
                $blogs->orderBy('published_at', 'desc');
                break;
            case 'title':
                $blogs->orderBy('title', 'asc');
                break;
            case 'popularity':
                $blogs->orderBy('views', 'desc');
                break;
            default: // relevance
                $blogs->orderByRaw("
                    CASE 
                        WHEN title LIKE ? THEN 3
                        WHEN content LIKE ? THEN 2
                        WHEN tags LIKE ? THEN 1
                        ELSE 0
                    END DESC
                ", ["%{$query}%", "%{$query}%", "%{$query}%"]);
        }

        $results = $blogs->paginate(12);
        
        return [
            'data' => $results->items(),
            'total' => $results->total(),
            'current_page' => $results->currentPage(),
            'per_page' => $results->perPage()
        ];
    }

    /**
     * Search users
     */
    private function searchUsers(string $query, string $sort): array
    {
        $users = User::where(function ($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('email', 'LIKE', "%{$query}%")
              ->orWhere('bio', 'LIKE', "%{$query}%");
        });

        // Apply sorting
        switch ($sort) {
            case 'date':
                $users->orderBy('created_at', 'desc');
                break;
            case 'name':
                $users->orderBy('name', 'asc');
                break;
            case 'popularity':
                $users->orderBy('reputation_score', 'desc');
                break;
            default: // relevance
                $users->orderByRaw("
                    CASE 
                        WHEN name LIKE ? THEN 3
                        WHEN bio LIKE ? THEN 2
                        WHEN email LIKE ? THEN 1
                        ELSE 0
                    END DESC
                ", ["%{$query}%", "%{$query}%", "%{$query}%"]);
        }

        $results = $users->paginate(12);
        
        return [
            'data' => $results->items(),
            'total' => $results->total(),
            'current_page' => $results->currentPage(),
            'per_page' => $results->perPage()
        ];
    }

    /**
     * Search pages
     */
    private function searchPages(string $query, string $sort): array
    {
        $pages = Page::where(function ($q) use ($query) {
            $q->where('title', 'LIKE', "%{$query}%")
              ->orWhere('content', 'LIKE', "%{$query}%")
              ->orWhere('meta_description', 'LIKE', "%{$query}%");
        });

        // Apply sorting
        switch ($sort) {
            case 'date':
                $pages->orderBy('updated_at', 'desc');
                break;
            case 'title':
                $pages->orderBy('title', 'asc');
                break;
            case 'popularity':
                $pages->orderBy('views', 'desc');
                break;
            default: // relevance
                $pages->orderByRaw("
                    CASE 
                        WHEN title LIKE ? THEN 3
                        WHEN content LIKE ? THEN 2
                        WHEN meta_description LIKE ? THEN 1
                        ELSE 0
                    END DESC
                ", ["%{$query}%", "%{$query}%", "%{$query}%"]);
        }

        $results = $pages->paginate(12);
        
        return [
            'data' => $results->items(),
            'total' => $results->total(),
            'current_page' => $results->currentPage(),
            'per_page' => $results->perPage()
        ];
    }

    /**
     * Search contributions
     */
    private function searchContributions(string $query, ?string $category, ?string $dateFrom, ?string $dateTo, string $sort): array
    {
        $contributions = Contribution::where(function ($q) use ($query) {
            $q->where('title', 'LIKE', "%{$query}%")
              ->orWhere('content', 'LIKE', "%{$query}%")
              ->orWhere('tags', 'LIKE', "%{$query}%");
        });

        if ($category) {
            $contributions->where('category', $category);
        }

        if ($dateFrom) {
            $contributions->where('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $contributions->where('created_at', '<=', $dateTo);
        }

        // Apply sorting
        switch ($sort) {
            case 'date':
                $contributions->orderBy('created_at', 'desc');
                break;
            case 'title':
                $contributions->orderBy('title', 'asc');
                break;
            case 'popularity':
                $contributions->orderBy('likes', 'desc');
                break;
            default: // relevance
                $contributions->orderByRaw("
                    CASE 
                        WHEN title LIKE ? THEN 3
                        WHEN content LIKE ? THEN 2
                        WHEN tags LIKE ? THEN 1
                        ELSE 0
                    END DESC
                ", ["%{$query}%", "%{$query}%", "%{$query}%"]);
        }

        $results = $contributions->paginate(12);
        
        return [
            'data' => $results->items(),
            'total' => $results->total(),
            'current_page' => $results->currentPage(),
            'per_page' => $results->perPage()
        ];
    }

    /**
     * Get search suggestions based on popular searches and content
     */
    private function getSearchSuggestions(string $query): array
    {
        $suggestions = [];

        // Get popular search terms from cache
        $popularSearches = Cache::get('popular_searches', []);

        // Get suggestions from existing content
        $webinarTitles = Webinar::where('title', 'LIKE', "%{$query}%")
            ->limit(5)
            ->pluck('title')
            ->toArray();

        $blogTitles = Blog::where('title', 'LIKE', "%{$query}%")
            ->limit(5)
            ->pluck('title')
            ->toArray();

        $tags = DB::table('webinars')
            ->selectRaw('SUBSTRING_INDEX(SUBSTRING_INDEX(tags, ",", numbers.n), ",", -1) as tag')
            ->join(DB::raw('(SELECT 1 n UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5) numbers'), 
                   DB::raw('CHAR_LENGTH(tags) - CHAR_LENGTH(REPLACE(tags, ",", "")) >= numbers.n - 1'))
            ->where('tags', 'LIKE', "%{$query}%")
            ->limit(5)
            ->pluck('tag')
            ->toArray();

        $suggestions = array_merge($popularSearches, $webinarTitles, $blogTitles, $tags);
        $suggestions = array_unique($suggestions);
        $suggestions = array_slice($suggestions, 0, 10);

        return $suggestions;
    }

    /**
     * Get search filters and categories
     */
    public function getFilters(): JsonResponse
    {
        $filters = Cache::remember('search_filters', 3600, function () {
            // Get categories from webinars
            $webinarCategories = Webinar::distinct()
                ->whereNotNull('category')
                ->pluck('category')
                ->toArray();

            // Get categories from blogs
            $blogCategories = Blog::distinct()
                ->whereNotNull('category')
                ->pluck('category')
                ->toArray();

            // Get categories from contributions
            $contributionCategories = Contribution::distinct()
                ->whereNotNull('category')
                ->pluck('category')
                ->toArray();

            // Get popular tags
            $popularTags = DB::table('webinars')
                ->selectRaw('SUBSTRING_INDEX(SUBSTRING_INDEX(tags, ",", numbers.n), ",", -1) as tag, COUNT(*) as count')
                ->join(DB::raw('(SELECT 1 n UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5) numbers'), 
                       DB::raw('CHAR_LENGTH(tags) - CHAR_LENGTH(REPLACE(tags, ",", "")) >= numbers.n - 1'))
                ->whereNotNull('tags')
                ->where('tags', '!=', '')
                ->groupBy('tag')
                ->orderBy('count', 'desc')
                ->limit(20)
                ->pluck('tag')
                ->toArray();

            return [
                'categories' => [
                    'webinars' => $webinarCategories,
                    'blogs' => $blogCategories,
                    'contributions' => $contributionCategories
                ],
                'popular_tags' => $popularTags,
                'sort_options' => [
                    'relevance' => 'Most Relevant',
                    'date' => 'Newest First',
                    'title' => 'Alphabetical',
                    'popularity' => 'Most Popular'
                ]
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $filters
        ]);
    }

    /**
     * Track search query for analytics
     */
    public function trackSearch(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:2|max:100',
            'results_count' => 'nullable|integer',
            'clicked_result' => 'nullable|string'
        ]);

        $query = $request->input('query');
        $resultsCount = $request->input('results_count', 0);
        $clickedResult = $request->input('clicked_result');

        // Store search analytics
        DB::table('search_analytics')->insert([
            'query' => $query,
            'results_count' => $resultsCount,
            'clicked_result' => $clickedResult,
            'user_id' => auth()->id(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now()
        ]);

        // Update popular searches cache
        $this->updatePopularSearches($query);

        return response()->json([
            'success' => true,
            'message' => 'Search tracked successfully'
        ]);
    }

    /**
     * Update popular searches cache
     */
    private function updatePopularSearches(string $query): void
    {
        $popularSearches = Cache::get('popular_searches', []);
        
        if (!in_array($query, $popularSearches)) {
            $popularSearches[] = $query;
            $popularSearches = array_slice($popularSearches, -20); // Keep last 20
            Cache::put('popular_searches', $popularSearches, 86400); // 24 hours
        }
    }

    /**
     * Get search analytics for admin
     */
    public function getAnalytics(Request $request): JsonResponse
    {
        $this->authorize('view-analytics');

        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'limit' => 'nullable|integer|min:1|max:100'
        ]);

        $dateFrom = $request->input('date_from', now()->subDays(30));
        $dateTo = $request->input('date_to', now());
        $limit = $request->input('limit', 20);

        $analytics = DB::table('search_analytics')
            ->selectRaw('query, COUNT(*) as search_count, AVG(results_count) as avg_results')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy('query')
            ->orderBy('search_count', 'desc')
            ->limit($limit)
            ->get();

        $totalSearches = DB::table('search_analytics')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->count();

        $uniqueQueries = DB::table('search_analytics')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->distinct('query')
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'analytics' => $analytics,
                'summary' => [
                    'total_searches' => $totalSearches,
                    'unique_queries' => $uniqueQueries,
                    'avg_results_per_search' => $analytics->avg('avg_results')
                ]
            ]
        ]);
    }
} 