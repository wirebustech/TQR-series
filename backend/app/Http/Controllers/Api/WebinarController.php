<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Webinar;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WebinarController extends Controller
{
    /**
     * Display a listing of webinars
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Webinar::query();

            // Apply filters
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('tags', 'like', "%{$search}%");
                });
            }

            // Apply date filters
            if ($request->has('date_from')) {
                $query->where('scheduled_at', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->where('scheduled_at', '<=', $request->date_to);
            }

            // Apply sorting
            $sortBy = $request->get('sort_by', 'scheduled_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 25);
            $webinars = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $webinars->items(),
                'pagination' => [
                    'current_page' => $webinars->currentPage(),
                    'last_page' => $webinars->lastPage(),
                    'per_page' => $webinars->perPage(),
                    'total' => $webinars->total(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch webinars',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created webinar
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'scheduled_at' => 'required|date|after:now',
                'duration' => 'nullable|integer|min:15|max:480',
                'max_attendees' => 'nullable|integer|min:1',
                'platform' => 'nullable|string|max:100',
                'meeting_url' => 'nullable|url|max:500',
                'tags' => 'nullable|string|max:500',
                'status' => 'nullable|in:draft,published,live,completed',
                'requires_registration' => 'boolean',
                'is_public' => 'boolean',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = 'webinar_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('webinars', $imageName, 'public');
                $data['image'] = $imagePath;
            }

            // Set default values
            $data['duration'] = $data['duration'] ?? 60;
            $data['max_attendees'] = $data['max_attendees'] ?? 100;
            $data['platform'] = $data['platform'] ?? 'zoom';
            $data['status'] = $data['status'] ?? 'draft';
            $data['requires_registration'] = $data['requires_registration'] ?? true;
            $data['is_public'] = $data['is_public'] ?? true;

            $webinar = Webinar::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Webinar created successfully',
                'data' => $webinar
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create webinar',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified webinar
     */
    public function show(Webinar $webinar): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $webinar
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch webinar',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified webinar
     */
    public function update(Request $request, Webinar $webinar): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'scheduled_at' => 'sometimes|required|date',
                'duration' => 'nullable|integer|min:15|max:480',
                'max_attendees' => 'nullable|integer|min:1',
                'platform' => 'nullable|string|max:100',
                'meeting_url' => 'nullable|url|max:500',
                'tags' => 'nullable|string|max:500',
                'status' => 'nullable|in:draft,published,live,completed',
                'requires_registration' => 'boolean',
                'is_public' => 'boolean',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($webinar->image && Storage::disk('public')->exists($webinar->image)) {
                    Storage::disk('public')->delete($webinar->image);
                }

                $image = $request->file('image');
                $imageName = 'webinar_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('webinars', $imageName, 'public');
                $data['image'] = $imagePath;
            }

            $webinar->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Webinar updated successfully',
                'data' => $webinar
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update webinar',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified webinar
     */
    public function destroy(Webinar $webinar): JsonResponse
    {
        try {
            // Delete associated image if exists
            if ($webinar->image && Storage::disk('public')->exists($webinar->image)) {
                Storage::disk('public')->delete($webinar->image);
            }

            $webinar->delete();

            return response()->json([
                'success' => true,
                'message' => 'Webinar deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete webinar',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get webinar statistics
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = [
                'total' => Webinar::count(),
                'draft' => Webinar::where('status', 'draft')->count(),
                'published' => Webinar::where('status', 'published')->count(),
                'live' => Webinar::where('status', 'live')->count(),
                'completed' => Webinar::where('status', 'completed')->count(),
                'upcoming' => Webinar::where('scheduled_at', '>', now())->count(),
                'this_month' => Webinar::whereMonth('scheduled_at', now()->month)->count(),
                'this_year' => Webinar::whereYear('scheduled_at', now()->year)->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk operations
     */
    public function bulkAction(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'action' => 'required|in:delete,publish,unpublish,duplicate',
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:webinars,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $action = $request->action;
            $ids = $request->ids;
            $count = 0;

            switch ($action) {
                case 'delete':
                    $webinars = Webinar::whereIn('id', $ids)->get();
                    foreach ($webinars as $webinar) {
                        if ($webinar->image && Storage::disk('public')->exists($webinar->image)) {
                            Storage::disk('public')->delete($webinar->image);
                        }
                    }
                    $count = Webinar::whereIn('id', $ids)->delete();
                    break;

                case 'publish':
                    $count = Webinar::whereIn('id', $ids)->update(['status' => 'published']);
                    break;

                case 'unpublish':
                    $count = Webinar::whereIn('id', $ids)->update(['status' => 'draft']);
                    break;

                case 'duplicate':
                    $webinars = Webinar::whereIn('id', $ids)->get();
                    foreach ($webinars as $webinar) {
                        $newWebinar = $webinar->replicate();
                        $newWebinar->title = $webinar->title . ' (Copy)';
                        $newWebinar->status = 'draft';
                        $newWebinar->scheduled_at = now()->addDays(7);
                        $newWebinar->save();
                        $count++;
                    }
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => ucfirst($action) . ' completed successfully',
                'affected_count' => $count
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to perform bulk action',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 