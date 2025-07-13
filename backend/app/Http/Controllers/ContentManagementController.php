<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Models\Webinar;
use App\Models\Blog;
use App\Models\Page;
use App\Models\Contribution;
use App\Models\MediaLibrary;
use App\Models\Category;
use App\Models\Tag;

class ContentManagementController extends Controller
{
    /**
     * Get content overview dashboard
     */
    public function getOverview(): JsonResponse
    {
        $this->authorize('manage-content');

        $overview = Cache::remember('content_overview', 300, function () {
            return [
                'total_webinars' => Webinar::count(),
                'published_webinars' => Webinar::where('status', 'published')->count(),
                'draft_webinars' => Webinar::where('status', 'draft')->count(),
                'total_blogs' => Blog::count(),
                'published_blogs' => Blog::where('status', 'published')->count(),
                'draft_blogs' => Blog::where('status', 'draft')->count(),
                'total_pages' => Page::count(),
                'published_pages' => Page::where('status', 'published')->count(),
                'total_contributions' => Contribution::count(),
                'pending_contributions' => Contribution::where('status', 'pending')->count(),
                'total_media' => MediaLibrary::count(),
                'recent_content' => $this->getRecentContent(),
                'content_performance' => $this->getContentPerformance(),
                'categories_distribution' => $this->getCategoriesDistribution()
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $overview
        ]);
    }

    /**
     * Bulk content operations
     */
    public function bulkAction(Request $request): JsonResponse
    {
        $this->authorize('manage-content');

        $request->validate([
            'action' => 'required|string|in:publish,unpublish,delete,duplicate,move_category,add_tags',
            'content_type' => 'required|string|in:webinars,blogs,pages,contributions',
            'content_ids' => 'required|array',
            'content_ids.*' => 'integer|exists:' . $request->input('content_type') . ',id',
            'category_id' => 'nullable|integer|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'string'
        ]);

        $action = $request->input('action');
        $contentType = $request->input('content_type');
        $contentIds = $request->input('content_ids');
        $categoryId = $request->input('category_id');
        $tags = $request->input('tags');

        $modelClass = $this->getModelClass($contentType);
        $model = new $modelClass;

        DB::beginTransaction();

        try {
            switch ($action) {
                case 'publish':
                    $model::whereIn('id', $contentIds)->update(['status' => 'published']);
                    break;

                case 'unpublish':
                    $model::whereIn('id', $contentIds)->update(['status' => 'draft']);
                    break;

                case 'delete':
                    $model::whereIn('id', $contentIds)->delete();
                    break;

                case 'duplicate':
                    foreach ($contentIds as $id) {
                        $this->duplicateContent($modelClass, $id);
                    }
                    break;

                case 'move_category':
                    if ($categoryId) {
                        $model::whereIn('id', $contentIds)->update(['category_id' => $categoryId]);
                    }
                    break;

                case 'add_tags':
                    if ($tags) {
                        foreach ($contentIds as $id) {
                            $content = $model::find($id);
                            if ($content) {
                                $existingTags = $content->tags ? explode(',', $content->tags) : [];
                                $newTags = array_merge($existingTags, $tags);
                                $content->update(['tags' => implode(',', array_unique($newTags))]);
                            }
                        }
                    }
                    break;
            }

            DB::commit();

            // Clear cache
            Cache::forget('content_overview');

            return response()->json([
                'success' => true,
                'message' => ucfirst($action) . ' action completed successfully',
                'affected_count' => count($contentIds)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error performing bulk action: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get content with advanced filters
     */
    public function getContent(Request $request): JsonResponse
    {
        $this->authorize('manage-content');

        $request->validate([
            'type' => 'required|string|in:webinars,blogs,pages,contributions',
            'status' => 'nullable|string',
            'category_id' => 'nullable|integer',
            'author_id' => 'nullable|integer',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'search' => 'nullable|string',
            'sort_by' => 'nullable|string|in:created_at,updated_at,title,views,status',
            'sort_order' => 'nullable|string|in:asc,desc',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        $type = $request->input('type');
        $modelClass = $this->getModelClass($type);
        $model = new $modelClass;

        $query = $model::query();

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->has('author_id')) {
            $query->where('author_id', $request->input('author_id'));
        }

        if ($request->has('date_from')) {
            $query->where('created_at', '>=', $request->input('date_from'));
        }

        if ($request->has('date_to')) {
            $query->where('created_at', '<=', $request->input('date_to'));
        }

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%");
            });
        }

        // Apply sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->input('per_page', 20);
        $content = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'content' => $content->items(),
                'pagination' => [
                    'current_page' => $content->currentPage(),
                    'per_page' => $content->perPage(),
                    'total' => $content->total(),
                    'last_page' => $content->lastPage()
                ]
            ]
        ]);
    }

    /**
     * Create new content
     */
    public function createContent(Request $request): JsonResponse
    {
        $this->authorize('manage-content');

        $request->validate([
            'type' => 'required|string|in:webinar,blog,page,contribution',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'category_id' => 'nullable|integer|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
            'status' => 'nullable|string|in:draft,published,scheduled',
            'scheduled_at' => 'nullable|date|after:now',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'featured_image' => 'nullable|string',
            'author_id' => 'nullable|integer|exists:users,id'
        ]);

        $type = $request->input('type');
        $modelClass = $this->getModelClass($type . 's'); // Pluralize for model class
        $model = new $modelClass;

        $data = $request->except(['type', 'tags']);
        
        if ($request->has('tags')) {
            $data['tags'] = implode(',', $request->input('tags'));
        }

        if (!$request->has('author_id')) {
            $data['author_id'] = auth()->id();
        }

        $content = $model::create($data);

        // Handle media attachments
        if ($request->has('featured_image')) {
            $this->attachMedia($content, $request->input('featured_image'), 'featured_image');
        }

        return response()->json([
            'success' => true,
            'message' => ucfirst($type) . ' created successfully',
            'data' => $content
        ]);
    }

    /**
     * Update content
     */
    public function updateContent(Request $request, int $id): JsonResponse
    {
        $this->authorize('manage-content');

        $request->validate([
            'type' => 'required|string|in:webinar,blog,page,contribution',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'category_id' => 'nullable|integer|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
            'status' => 'nullable|string|in:draft,published,scheduled',
            'scheduled_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'featured_image' => 'nullable|string'
        ]);

        $type = $request->input('type');
        $modelClass = $this->getModelClass($type . 's');
        $content = $modelClass::findOrFail($id);

        $data = $request->except(['type', 'tags']);
        
        if ($request->has('tags')) {
            $data['tags'] = implode(',', $request->input('tags'));
        }

        $content->update($data);

        // Handle media attachments
        if ($request->has('featured_image')) {
            $this->attachMedia($content, $request->input('featured_image'), 'featured_image');
        }

        return response()->json([
            'success' => true,
            'message' => ucfirst($type) . ' updated successfully',
            'data' => $content
        ]);
    }

    /**
     * Delete content
     */
    public function deleteContent(Request $request, int $id): JsonResponse
    {
        $this->authorize('manage-content');

        $request->validate([
            'type' => 'required|string|in:webinar,blog,page,contribution'
        ]);

        $type = $request->input('type');
        $modelClass = $this->getModelClass($type . 's');
        $content = $modelClass::findOrFail($id);

        $content->delete();

        return response()->json([
            'success' => true,
            'message' => ucfirst($type) . ' deleted successfully'
        ]);
    }

    /**
     * Get content analytics
     */
    public function getContentAnalytics(Request $request): JsonResponse
    {
        $this->authorize('manage-content');

        $request->validate([
            'type' => 'nullable|string|in:webinars,blogs,pages,contributions',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date'
        ]);

        $type = $request->input('type');
        $dateFrom = $request->input('date_from', now()->subDays(30));
        $dateTo = $request->input('date_to', now());

        $analytics = [
            'content_creation_trend' => $this->getContentCreationTrend($type, $dateFrom, $dateTo),
            'top_performing_content' => $this->getTopPerformingContent($type),
            'category_performance' => $this->getCategoryPerformance($type),
            'author_performance' => $this->getAuthorPerformance($type),
            'engagement_metrics' => $this->getEngagementMetrics($type, $dateFrom, $dateTo)
        ];

        return response()->json([
            'success' => true,
            'data' => $analytics
        ]);
    }

    /**
     * Get media library
     */
    public function getMediaLibrary(Request $request): JsonResponse
    {
        $this->authorize('manage-content');

        $request->validate([
            'type' => 'nullable|string|in:image,video,document',
            'search' => 'nullable|string',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        $query = MediaLibrary::query();

        if ($request->has('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
        }

        $media = $query->orderBy('created_at', 'desc')
                      ->paginate($request->input('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $media
        ]);
    }

    /**
     * Upload media
     */
    public function uploadMedia(Request $request): JsonResponse
    {
        $this->authorize('manage-content');

        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string'
        ]);

        $file = $request->file('file');
        $name = $request->input('name', $file->getClientOriginalName());
        $description = $request->input('description');
        $category = $request->input('category');

        // Determine file type
        $type = $this->getFileType($file->getMimeType());

        // Store file
        $path = $file->store('media/' . $type, 'public');
        $url = Storage::url($path);

        $media = MediaLibrary::create([
            'name' => $name,
            'description' => $description,
            'category' => $category,
            'type' => $type,
            'path' => $path,
            'url' => $url,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'uploaded_by' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Media uploaded successfully',
            'data' => $media
        ]);
    }

    /**
     * Get model class for content type
     */
    private function getModelClass(string $type): string
    {
        $models = [
            'webinars' => Webinar::class,
            'blogs' => Blog::class,
            'pages' => Page::class,
            'contributions' => Contribution::class
        ];

        return $models[$type] ?? throw new \InvalidArgumentException("Invalid content type: {$type}");
    }

    /**
     * Duplicate content
     */
    private function duplicateContent(string $modelClass, int $id): void
    {
        $original = $modelClass::find($id);
        if (!$original) return;

        $duplicate = $original->replicate();
        $duplicate->title = $original->title . ' (Copy)';
        $duplicate->status = 'draft';
        $duplicate->created_at = now();
        $duplicate->updated_at = now();
        $duplicate->save();
    }

    /**
     * Attach media to content
     */
    private function attachMedia($content, string $mediaId, string $field): void
    {
        $media = MediaLibrary::find($mediaId);
        if ($media) {
            $content->update([$field => $media->url]);
        }
    }

    /**
     * Get file type from MIME type
     */
    private function getFileType(string $mimeType): string
    {
        if (str_starts_with($mimeType, 'image/')) return 'image';
        if (str_starts_with($mimeType, 'video/')) return 'video';
        return 'document';
    }

    /**
     * Get recent content
     */
    private function getRecentContent(): array
    {
        $recent = [];

        // Recent webinars
        $recent['webinars'] = Webinar::orderBy('created_at', 'desc')
            ->limit(5)
            ->get(['id', 'title', 'status', 'created_at']);

        // Recent blogs
        $recent['blogs'] = Blog::orderBy('created_at', 'desc')
            ->limit(5)
            ->get(['id', 'title', 'status', 'created_at']);

        // Recent pages
        $recent['pages'] = Page::orderBy('created_at', 'desc')
            ->limit(5)
            ->get(['id', 'title', 'status', 'created_at']);

        return $recent;
    }

    /**
     * Get content performance
     */
    private function getContentPerformance(): array
    {
        return [
            'top_webinars' => Webinar::orderBy('views', 'desc')->limit(5)->get(['id', 'title', 'views']),
            'top_blogs' => Blog::orderBy('views', 'desc')->limit(5)->get(['id', 'title', 'views']),
            'top_pages' => Page::orderBy('views', 'desc')->limit(5)->get(['id', 'title', 'views'])
        ];
    }

    /**
     * Get categories distribution
     */
    private function getCategoriesDistribution(): array
    {
        return [
            'webinar_categories' => Webinar::selectRaw('category, COUNT(*) as count')
                ->whereNotNull('category')
                ->groupBy('category')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
            'blog_categories' => Blog::selectRaw('category, COUNT(*) as count')
                ->whereNotNull('category')
                ->groupBy('category')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get()
        ];
    }

    /**
     * Get content creation trend
     */
    private function getContentCreationTrend(?string $type, string $dateFrom, string $dateTo): array
    {
        $trend = [];
        $modelClass = $type ? $this->getModelClass($type) : null;

        if ($modelClass) {
            $trend = $modelClass::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->toArray();
        } else {
            // All content types
            $trend = DB::table('webinars')
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->groupBy('date')
                ->union(
                    DB::table('blogs')
                        ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                        ->whereBetween('created_at', [$dateFrom, $dateTo])
                        ->groupBy('date')
                )
                ->orderBy('date')
                ->get()
                ->toArray();
        }

        return $trend;
    }

    /**
     * Get top performing content
     */
    private function getTopPerformingContent(?string $type): array
    {
        $top = [];

        if (!$type || $type === 'webinars') {
            $top['webinars'] = Webinar::orderBy('views', 'desc')->limit(10)->get(['id', 'title', 'views']);
        }

        if (!$type || $type === 'blogs') {
            $top['blogs'] = Blog::orderBy('views', 'desc')->limit(10)->get(['id', 'title', 'views']);
        }

        if (!$type || $type === 'pages') {
            $top['pages'] = Page::orderBy('views', 'desc')->limit(10)->get(['id', 'title', 'views']);
        }

        return $top;
    }

    /**
     * Get category performance
     */
    private function getCategoryPerformance(?string $type): array
    {
        $performance = [];

        if (!$type || $type === 'webinars') {
            $performance['webinar_categories'] = Webinar::selectRaw('category, AVG(views) as avg_views, COUNT(*) as count')
                ->whereNotNull('category')
                ->groupBy('category')
                ->orderBy('avg_views', 'desc')
                ->limit(10)
                ->get();
        }

        if (!$type || $type === 'blogs') {
            $performance['blog_categories'] = Blog::selectRaw('category, AVG(views) as avg_views, COUNT(*) as count')
                ->whereNotNull('category')
                ->groupBy('category')
                ->orderBy('avg_views', 'desc')
                ->limit(10)
                ->get();
        }

        return $performance;
    }

    /**
     * Get author performance
     */
    private function getAuthorPerformance(?string $type): array
    {
        $performance = [];

        if (!$type || $type === 'webinars') {
            $performance['webinar_authors'] = Webinar::selectRaw('speaker_name, COUNT(*) as count, AVG(views) as avg_views')
                ->whereNotNull('speaker_name')
                ->groupBy('speaker_name')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get();
        }

        if (!$type || $type === 'blogs') {
            $performance['blog_authors'] = Blog::selectRaw('author, COUNT(*) as count, AVG(views) as avg_views')
                ->whereNotNull('author')
                ->groupBy('author')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get();
        }

        return $performance;
    }

    /**
     * Get engagement metrics
     */
    private function getEngagementMetrics(?string $type, string $dateFrom, string $dateTo): array
    {
        $metrics = [];

        if (!$type || $type === 'webinars') {
            $metrics['webinar_engagement'] = [
                'total_views' => Webinar::whereBetween('created_at', [$dateFrom, $dateTo])->sum('views'),
                'avg_views' => Webinar::whereBetween('created_at', [$dateFrom, $dateTo])->avg('views'),
                'total_registrations' => DB::table('webinar_user')->whereBetween('created_at', [$dateFrom, $dateTo])->count()
            ];
        }

        if (!$type || $type === 'blogs') {
            $metrics['blog_engagement'] = [
                'total_views' => Blog::whereBetween('created_at', [$dateFrom, $dateTo])->sum('views'),
                'avg_views' => Blog::whereBetween('created_at', [$dateFrom, $dateTo])->avg('views'),
                'total_comments' => DB::table('blog_comments')->whereBetween('created_at', [$dateFrom, $dateTo])->count()
            ];
        }

        return $metrics;
    }
} 