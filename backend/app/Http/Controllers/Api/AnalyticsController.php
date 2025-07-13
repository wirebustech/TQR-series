<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Webinar;
use App\Models\ResearchContribution;
use App\Models\Blog;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class AnalyticsController extends Controller
{
    /**
     * Get user growth data over time
     */
    public function getUserGrowth(Request $request)
    {
        $days = $request->get('days', 30);
        $startDate = Carbon::now()->subDays($days);
        
        $userGrowth = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $values = [];
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $labels[] = Carbon::parse($date)->format('M j');
            
            $count = $userGrowth->where('date', $date)->first();
            $values[] = $count ? $count->count : 0;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'labels' => $labels,
                'values' => $values
            ]
        ]);
    }

    /**
     * Get user distribution by role
     */
    public function getUserDistribution()
    {
        $distribution = User::selectRaw('role, COUNT(*) as count')
            ->groupBy('role')
            ->get();

        $labels = ['Users', 'Researchers', 'Moderators', 'Admins'];
        $values = [0, 0, 0, 0];

        foreach ($distribution as $item) {
            switch ($item->role) {
                case 'user':
                    $values[0] = $item->count;
                    break;
                case 'researcher':
                    $values[1] = $item->count;
                    break;
                case 'moderator':
                    $values[2] = $item->count;
                    break;
                case 'admin':
                    $values[3] = $item->count;
                    break;
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'labels' => $labels,
                'values' => $values
            ]
        ]);
    }

    /**
     * Get webinar performance data
     */
    public function getWebinarPerformance(Request $request)
    {
        $days = $request->get('days', 30);
        $startDate = Carbon::now()->subDays($days);

        $webinars = Webinar::with('registrations')
            ->where('created_at', '>=', $startDate)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $labels = [];
        $registrations = [];
        $attendees = [];

        foreach ($webinars as $webinar) {
            $labels[] = $webinar->title;
            $registrations[] = $webinar->registrations->count();
            $attendees[] = $webinar->registrations->where('attended', true)->count();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'labels' => $labels,
                'registrations' => $registrations,
                'attendees' => $attendees
            ]
        ]);
    }

    /**
     * Get contribution status distribution
     */
    public function getContributionStatus()
    {
        $statuses = ResearchContribution::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        $labels = ['Pending', 'Approved', 'Rejected', 'Published'];
        $values = [0, 0, 0, 0];

        foreach ($statuses as $status) {
            switch ($status->status) {
                case 'pending':
                    $values[0] = $status->count;
                    break;
                case 'approved':
                    $values[1] = $status->count;
                    break;
                case 'rejected':
                    $values[2] = $status->count;
                    break;
                case 'published':
                    $values[3] = $status->count;
                    break;
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'labels' => $labels,
                'values' => $values
            ]
        ]);
    }

    /**
     * Get recent activity
     */
    public function getRecentActivity(Request $request)
    {
        $limit = $request->get('limit', 10);
        
        // Get recent user registrations
        $userActivity = User::select('name', 'email', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit($limit / 2)
            ->get()
            ->map(function ($user) {
                return [
                    'title' => 'New User Registration',
                    'description' => $user->name . ' joined the platform',
                    'created_at' => $user->created_at,
                    'type' => 'user'
                ];
            });

        // Get recent webinar activity
        $webinarActivity = Webinar::select('title', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit($limit / 2)
            ->get()
            ->map(function ($webinar) {
                return [
                    'title' => 'Webinar Created',
                    'description' => $webinar->title . ' was scheduled',
                    'created_at' => $webinar->created_at,
                    'type' => 'webinar'
                ];
            });

        // Get recent contribution activity
        $contributionActivity = ResearchContribution::select('title', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit($limit / 2)
            ->get()
            ->map(function ($contribution) {
                return [
                    'title' => 'Contribution Submitted',
                    'description' => $contribution->title . ' was submitted',
                    'created_at' => $contribution->created_at,
                    'type' => 'contribution'
                ];
            });

        // Combine and sort all activity
        $allActivity = $userActivity->concat($webinarActivity)->concat($contributionActivity);
        $allActivity = $allActivity->sortByDesc('created_at')->take($limit);

        return response()->json([
            'success' => true,
            'data' => $allActivity->values()
        ]);
    }

    /**
     * Get top performing content
     */
    public function getTopContent(Request $request)
    {
        $limit = $request->get('limit', 5);

        // Get top blogs (mock data for now)
        $topBlogs = Blog::select('title', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($blog, $index) {
                return [
                    'title' => $blog->title,
                    'type' => 'Blog',
                    'views' => rand(50, 300),
                    'engagement' => rand(60, 95),
                    'rank' => $index + 1
                ];
            });

        // Get top webinars (mock data for now)
        $topWebinars = Webinar::select('title', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($webinar, $index) {
                return [
                    'title' => $webinar->title,
                    'type' => 'Webinar',
                    'views' => rand(100, 500),
                    'engagement' => rand(70, 98),
                    'rank' => $index + 1
                ];
            });

        // Combine and sort by engagement
        $allContent = $topBlogs->concat($topWebinars);
        $allContent = $allContent->sortByDesc('engagement')->take($limit);

        return response()->json([
            'success' => true,
            'data' => $allContent->values()
        ]);
    }

    /**
     * Get comprehensive analytics overview
     */
    public function getOverview()
    {
        $now = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        // User statistics
        $totalUsers = User::count();
        $newUsersThisMonth = User::where('created_at', '>=', $lastMonth)->count();
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        $adminUsers = User::where('role', 'admin')->count();

        // Content statistics
        $totalBlogs = Blog::count();
        $publishedBlogs = Blog::where('status', 'published')->count();
        $totalWebinars = Webinar::count();
        $upcomingWebinars = Webinar::where('start_time', '>', $now)->count();
        $totalContributions = ResearchContribution::count();
        $approvedContributions = ResearchContribution::where('status', 'approved')->count();
        $totalPages = Page::count();

        // Calculate growth percentages (mock data for now)
        $userGrowth = rand(5, 25);
        $webinarGrowth = rand(10, 30);
        $contributionGrowth = rand(15, 40);
        $blogGrowth = rand(5, 20);

        return response()->json([
            'success' => true,
            'data' => [
                'users' => [
                    'total' => $totalUsers,
                    'new_this_month' => $newUsersThisMonth,
                    'verified' => $verifiedUsers,
                    'admin' => $adminUsers,
                    'growth' => $userGrowth
                ],
                'webinars' => [
                    'total' => $totalWebinars,
                    'upcoming' => $upcomingWebinars,
                    'growth' => $webinarGrowth
                ],
                'contributions' => [
                    'total' => $totalContributions,
                    'approved' => $approvedContributions,
                    'growth' => $contributionGrowth
                ],
                'blogs' => [
                    'total' => $totalBlogs,
                    'published' => $publishedBlogs,
                    'growth' => $blogGrowth
                ],
                'pages' => [
                    'total' => $totalPages
                ]
            ]
        ]);
    }

    /**
     * Export analytics report
     */
    public function exportReport(Request $request)
    {
        $days = $request->get('days', 30);
        
        // Generate report data
        $reportData = [
            'period' => $days . ' days',
            'generated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'overview' => $this->getOverviewData($days),
            'user_growth' => $this->getUserGrowthData($days),
            'content_stats' => $this->getContentStats($days),
            'activity_summary' => $this->getActivitySummary($days)
        ];

        // For now, return JSON. In production, you'd generate a PDF
        return response()->json([
            'success' => true,
            'data' => $reportData,
            'message' => 'Report data generated successfully'
        ]);
    }

    /**
     * Get overview data for report
     */
    private function getOverviewData($days)
    {
        $startDate = Carbon::now()->subDays($days);
        
        return [
            'total_users' => User::count(),
            'new_users_period' => User::where('created_at', '>=', $startDate)->count(),
            'total_webinars' => Webinar::count(),
            'new_webinars_period' => Webinar::where('created_at', '>=', $startDate)->count(),
            'total_contributions' => ResearchContribution::count(),
            'new_contributions_period' => ResearchContribution::where('created_at', '>=', $startDate)->count(),
            'total_blogs' => Blog::count(),
            'new_blogs_period' => Blog::where('created_at', '>=', $startDate)->count()
        ];
    }

    /**
     * Get user growth data for report
     */
    private function getUserGrowthData($days)
    {
        $startDate = Carbon::now()->subDays($days);
        
        return User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Get content statistics for report
     */
    private function getContentStats($days)
    {
        $startDate = Carbon::now()->subDays($days);
        
        return [
            'webinars' => Webinar::where('created_at', '>=', $startDate)->count(),
            'contributions' => ResearchContribution::where('created_at', '>=', $startDate)->count(),
            'blogs' => Blog::where('created_at', '>=', $startDate)->count(),
            'published_content' => Blog::where('status', 'published')
                ->where('created_at', '>=', $startDate)
                ->count()
        ];
    }

    /**
     * Get activity summary for report
     */
    private function getActivitySummary($days)
    {
        $startDate = Carbon::now()->subDays($days);
        
        return [
            'user_registrations' => User::where('created_at', '>=', $startDate)->count(),
            'webinar_registrations' => DB::table('webinar_registrations')
                ->where('created_at', '>=', $startDate)
                ->count(),
            'contribution_submissions' => ResearchContribution::where('created_at', '>=', $startDate)->count(),
            'content_published' => Blog::where('status', 'published')
                ->where('created_at', '>=', $startDate)
                ->count()
        ];
    }
} 