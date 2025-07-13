<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use Illuminate\Http\JsonResponse;

class PageController extends Controller
{
    /**
     * List all pages with filtering and search
     */
    public function index(Request $request): JsonResponse
    {
        $query = Page::query();
        
        // Language filter
        if ($request->has('language')) {
            $query->byLanguage($request->language);
        }
        
        // Status filter
        if ($request->has('status')) {
            if ($request->status === 'published') {
                $query->published();
            } elseif ($request->status === 'draft') {
                $query->draft();
            }
        }
        
        // Search filter
        if ($request->has('search')) {
            $query->search($request->search);
        }
        
        // Include relationships
        $query->with(['creator', 'updater']);
        
        // Order by
        $query->orderBy($request->get('order_by', 'created_at'), $request->get('order_direction', 'desc'));
        
        // Pagination
        if ($request->has('per_page')) {
            $pages = $query->paginate($request->get('per_page', 15));
            
            return response()->json([
                'success' => true,
                'data' => $pages->items(),
                'pagination' => [
                    'current_page' => $pages->currentPage(),
                    'last_page' => $pages->lastPage(),
                    'per_page' => $pages->perPage(),
                    'total' => $pages->total()
                ]
            ]);
        }
        
        $pages = $query->get();
        
        return response()->json([
            'success' => true,
            'data' => $pages
        ]);
    }

    /**
     * List published pages for public access
     */
    public function published(Request $request): JsonResponse
    {
        $query = Page::query()->published();
        
        // Language filter
        if ($request->has('language')) {
            $query->byLanguage($request->language);
        }
        
        // Search filter
        if ($request->has('search')) {
            $query->search($request->search);
        }
        
        // Order by
        $query->orderBy($request->get('order_by', 'created_at'), $request->get('order_direction', 'desc'));
        
        $pages = $query->get();
        
        return response()->json([
            'success' => true,
            'data' => $pages
        ]);
    }

    /**
     * Show a single page
     */
    public function show($id): JsonResponse
    {
        $page = Page::with(['creator', 'updater'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $page
        ]);
    }

    /**
     * Show a page by slug and language
     */
    public function showBySlug(Request $request, $slug): JsonResponse
    {
        $language = $request->get('language', 'en');
        
        $page = Page::where('slug', $slug)
                   ->byLanguage($language)
                   ->published()
                   ->with(['creator', 'updater'])
                   ->firstOrFail();
        
        return response()->json([
            'success' => true,
            'data' => $page
        ]);
    }

    /**
     * Create a new page
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug',
            'language' => 'required|string|max:5|in:en,fr,es',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'is_published' => 'boolean',
        ]);
        
        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = $this->generateSlug($validated['title']);
        }
        
        // Set creator (in real app, this would come from authenticated user)
        $validated['created_by'] = 1; // Mock user ID
        
        $page = Page::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Page created successfully',
            'data' => $page->load(['creator', 'updater'])
        ], 201);
    }

    /**
     * Update a page
     */
    public function update(Request $request, $id): JsonResponse
    {
        $page = Page::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:pages,slug,' . $id,
            'language' => 'sometimes|required|string|max:5|in:en,fr,es',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'is_published' => 'boolean',
        ]);
        
        // Set updater (in real app, this would come from authenticated user)
        $validated['updated_by'] = 1; // Mock user ID
        
        $page->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Page updated successfully',
            'data' => $page->load(['creator', 'updater'])
        ]);
    }

    /**
     * Delete a page
     */
    public function destroy($id): JsonResponse
    {
        $page = Page::findOrFail($id);
        $page->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Page deleted successfully'
        ]);
    }

    /**
     * Get page statistics
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total' => Page::count(),
            'published' => Page::published()->count(),
            'draft' => Page::draft()->count(),
            'by_language' => [
                'en' => Page::byLanguage('en')->count(),
                'fr' => Page::byLanguage('fr')->count(),
                'es' => Page::byLanguage('es')->count(),
            ]
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Bulk update pages
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'page_ids' => 'required|array',
            'page_ids.*' => 'integer|exists:pages,id',
            'action' => 'required|string|in:publish,unpublish,delete',
        ]);
        
        $pages = Page::whereIn('id', $validated['page_ids']);
        
        switch ($validated['action']) {
            case 'publish':
                $pages->update(['is_published' => true, 'updated_by' => 1]);
                $message = 'Pages published successfully';
                break;
            case 'unpublish':
                $pages->update(['is_published' => false, 'updated_by' => 1]);
                $message = 'Pages unpublished successfully';
                break;
            case 'delete':
                $pages->delete();
                $message = 'Pages deleted successfully';
                break;
        }
        
        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Duplicate a page
     */
    public function duplicate($id): JsonResponse
    {
        $originalPage = Page::findOrFail($id);
        
        $newPage = $originalPage->replicate();
        $newPage->title = $originalPage->title . ' (Copy)';
        $newPage->slug = $this->generateSlug($newPage->title);
        $newPage->is_published = false;
        $newPage->created_by = 1; // Mock user ID
        $newPage->updated_by = null;
        $newPage->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Page duplicated successfully',
            'data' => $newPage->load(['creator', 'updater'])
        ], 201);
    }

    /**
     * Generate a unique slug from title
     */
    private function generateSlug($title, $id = null): string
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $originalSlug = $slug;
        $counter = 1;
        
        while (true) {
            $query = Page::where('slug', $slug);
            if ($id) {
                $query->where('id', '!=', $id);
            }
            
            if (!$query->exists()) {
                break;
            }
            
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
}
