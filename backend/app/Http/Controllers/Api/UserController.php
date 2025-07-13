<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = User::query();

            // Apply filters
            if ($request->has('role')) {
                $query->where('role', $request->role);
            }

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('organization', 'like', "%{$search}%");
                });
            }

            // Apply date filters
            if ($request->has('date_from')) {
                $query->where('created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->where('created_at', '<=', $request->date_to);
            }

            // Apply sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 25);
            $users = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $users->items(),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch users',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'nullable|string|in:user,admin,moderator,researcher',
                'status' => 'nullable|string|in:active,inactive,suspended',
                'organization' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'bio' => 'nullable|string',
                'is_admin' => 'boolean',
                'email_verified' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();
            
            // Hash password
            $data['password'] = Hash::make($data['password']);
            
            // Set default values
            $data['role'] = $data['role'] ?? 'user';
            $data['status'] = $data['status'] ?? 'active';
            $data['is_admin'] = $data['is_admin'] ?? false;
            
            // Handle email verification
            if ($data['email_verified'] ?? false) {
                $data['email_verified_at'] = now();
            }
            unset($data['email_verified']);

            $user = User::create($data);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data' => $user
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified user
     */
    public function show(User $user): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $user
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8|confirmed',
                'role' => 'nullable|string|in:user,admin,moderator,researcher',
                'status' => 'nullable|string|in:active,inactive,suspended',
                'organization' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'bio' => 'nullable|string',
                'is_admin' => 'boolean',
                'email_verified' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            // Handle password update
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            // Handle email verification
            if (isset($data['email_verified'])) {
                if ($data['email_verified']) {
                    $data['email_verified_at'] = now();
                } else {
                    $data['email_verified_at'] = null;
                }
                unset($data['email_verified']);
            }

            $user->update($data);

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $user
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            // Prevent admin from deleting themselves
            if ($user->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot delete your own account'
                ], 403);
            }

            // Delete associated data
            $user->contributions()->delete();
            $user->webinarRegistrations()->delete();
            $user->donations()->delete();
            
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user statistics
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = [
                'total' => User::count(),
                'active' => User::where('status', 'active')->count(),
                'inactive' => User::where('status', 'inactive')->count(),
                'suspended' => User::where('status', 'suspended')->count(),
                'admin' => User::where('is_admin', true)->count(),
                'verified' => User::whereNotNull('email_verified_at')->count(),
                'new_this_month' => User::whereMonth('created_at', now()->month)->count(),
                'new_this_year' => User::whereYear('created_at', now()->year)->count(),
                'by_role' => [
                    'user' => User::where('role', 'user')->count(),
                    'admin' => User::where('role', 'admin')->count(),
                    'moderator' => User::where('role', 'moderator')->count(),
                    'researcher' => User::where('role', 'researcher')->count(),
                ]
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
                'action' => 'required|in:delete,activate,deactivate,suspend,change_role',
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:users,id',
                'role' => 'required_if:action,change_role|string|in:user,admin,moderator,researcher'
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

            // Prevent admin from deleting themselves
            if ($action === 'delete' && in_array(auth()->id(), $ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot delete your own account'
                ], 403);
            }

            switch ($action) {
                case 'delete':
                    $users = User::whereIn('id', $ids)->get();
                    foreach ($users as $user) {
                        $user->contributions()->delete();
                        $user->webinarRegistrations()->delete();
                        $user->donations()->delete();
                    }
                    $count = User::whereIn('id', $ids)->delete();
                    break;

                case 'activate':
                    $count = User::whereIn('id', $ids)->update(['status' => 'active']);
                    break;

                case 'deactivate':
                    $count = User::whereIn('id', $ids)->update(['status' => 'inactive']);
                    break;

                case 'suspend':
                    $count = User::whereIn('id', $ids)->update(['status' => 'suspended']);
                    break;

                case 'change_role':
                    $count = User::whereIn('id', $ids)->update(['role' => $request->role]);
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

    /**
     * Get user profile
     */
    public function profile(): JsonResponse
    {
        try {
            $user = auth()->user();
            
            return response()->json([
                'success' => true,
                'data' => $user
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'organization' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'bio' => 'nullable|string',
                'current_password' => 'nullable|string',
                'password' => 'nullable|string|min:8|confirmed'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            // Handle password change
            if (isset($data['password'])) {
                if (!isset($data['current_password']) || !Hash::check($data['current_password'], $user->password)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Current password is incorrect'
                    ], 422);
                }
                
                $data['password'] = Hash::make($data['password']);
                unset($data['current_password']);
            } else {
                unset($data['password'], $data['current_password']);
            }

            $user->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => $user
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 