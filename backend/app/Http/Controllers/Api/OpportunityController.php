<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Opportunity;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OpportunityController extends Controller
{
    /**
     * Display a listing of opportunities (public)
     */
    public function index(Request $request): JsonResponse
    {
        $query = Opportunity::active()->orderBy('created_at', 'desc');
        
        // Filter by type if provided
        if ($request->has('type')) {
            $query->byType($request->type);
        }
        
        // Paginate results
        $opportunities = $query->paginate($request->get('per_page', 10));
        
        return response()->json([
            'success' => true,
            'data' => $opportunities->items(),
            'pagination' => [
                'current_page' => $opportunities->currentPage(),
                'last_page' => $opportunities->lastPage(),
                'per_page' => $opportunities->perPage(),
                'total' => $opportunities->total()
            ]
        ]);
    }

    /**
     * Store a newly created opportunity (admin only)
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'url' => 'nullable|url',
            'type' => 'required|string|in:Research,Collaboration,Funding,Conference,Publication',
            'is_active' => 'boolean'
        ]);

        $opportunity = Opportunity::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Opportunity created successfully',
            'data' => $opportunity
        ], 201);
    }

    /**
     * Display the specified opportunity (public)
     */
    public function show(Opportunity $opportunity): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $opportunity
        ]);
    }

    /**
     * Update the specified opportunity (admin only)
     */
    public function update(Request $request, Opportunity $opportunity): JsonResponse
    {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'nullable|string',
            'url' => 'nullable|url',
            'type' => 'sometimes|required|string|in:Research,Collaboration,Funding,Conference,Publication',
            'is_active' => 'boolean'
        ]);

        $opportunity->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Opportunity updated successfully',
            'data' => $opportunity
        ]);
    }

    /**
     * Remove the specified opportunity (admin only)
     */
    public function destroy(Opportunity $opportunity): JsonResponse
    {
        $opportunity->delete();

        return response()->json([
            'success' => true,
            'message' => 'Opportunity deleted successfully'
        ]);
    }

    /**
     * Get opportunities statistics
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total' => Opportunity::count(),
            'active' => Opportunity::active()->count(),
            'by_type' => Opportunity::selectRaw('type, count(*) as count')
                ->groupBy('type')
                ->get()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get latest opportunities for news reel
     */
    public function latest(): JsonResponse
    {
        $opportunities = Opportunity::active()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $opportunities
        ]);
    }
}
