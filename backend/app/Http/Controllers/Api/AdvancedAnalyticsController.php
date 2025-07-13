<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Webinar;
use App\Models\Blog;
use App\Models\Payment;
use App\Models\SupportDonation;
use App\Models\ResearchContribution;
use App\Models\NewsletterSubscription;
use Carbon\Carbon;

class AdvancedAnalyticsController extends Controller
{
    /**
     * Get comprehensive analytics overview
     */
    public function getOverview(Request $request): JsonResponse
    {
        try {
            $dateRange = $request->get('days', 30);
            $startDate = Carbon::now()->subDays($dateRange);
            
            // Cache key for performance
            $cacheKey = "analytics_overview_{$dateRange}";
            
            return Cache::remember($cacheKey, 300, function () use ($startDate, $dateRange) {
                $data = [
                    'period' => [
                        'start' => $startDate->format('Y-m-d'),
                        'end' => Carbon::now()->format('Y-m-d'),
                        'days' => $dateRange
                    ],
                    'users' => $this->getUserMetrics($startDate),
                    'webinars' => $this->getWebinarMetrics($startDate),
                    'content' => $this->getContentMetrics($startDate),
                    'payments' => $this->getPaymentMetrics($startDate),
                    'engagement' => $this->getEngagementMetrics($startDate),
                    'revenue' => $this->getRevenueMetrics($startDate),
                    'growth' => $this->getGrowthMetrics($startDate),
                    'performance' => $this->getPerformanceMetrics($startDate)
                ];
                
                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
            });
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load analytics overview',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user acquisition and behavior analytics
     */
    public function getUserAnalytics(Request $request): JsonResponse
    {
        try {
            $dateRange = $request->get('days', 30);
            $startDate = Carbon::now()->subDays($dateRange);
            
            $data = [
                'acquisition' => $this->getUserAcquisitionData($startDate),
                'behavior' => $this->getUserBehaviorData($startDate),
                'retention' => $this->getUserRetentionData($startDate),
                'demographics' => $this->getUserDemographicsData($startDate),
                'segments' => $this->getUserSegmentsData($startDate)
            ];
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load user analytics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get content performance analytics
     */
    public function getContentAnalytics(Request $request): JsonResponse
    {
        try {
            $dateRange = $request->get('days', 30);
            $startDate = Carbon::now()->subDays($dateRange);
            
            $data = [
                'blogs' => $this->getBlogPerformanceData($startDate),
                'webinars' => $this->getWebinarPerformanceData($startDate),
                'contributions' => $this->getContributionPerformanceData($startDate),
                'top_content' => $this->getTopContentData($startDate),
                'content_trends' => $this->getContentTrendsData($startDate)
            ];
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load content analytics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get financial analytics
     */
    public function getFinancialAnalytics(Request $request): JsonResponse
    {
        try {
            $dateRange = $request->get('days', 30);
            $startDate = Carbon::now()->subDays($dateRange);
            
            $data = [
                'revenue' => $this->getRevenueData($startDate),
                'payments' => $this->getPaymentData($startDate),
                'donations' => $this->getDonationData($startDate),
                'subscriptions' => $this->getSubscriptionData($startDate),
                'financial_metrics' => $this->getFinancialMetrics($startDate)
            ];
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load financial analytics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get real-time analytics
     */
    public function getRealTimeAnalytics(): JsonResponse
    {
        try {
            $data = [
                'active_users' => $this->getActiveUsersCount(),
                'current_webinars' => $this->getCurrentWebinarsData(),
                'recent_activity' => $this->getRecentActivityData(),
                'system_health' => $this->getSystemHealthData()
            ];
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'timestamp' => Carbon::now()->toISOString()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load real-time analytics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export analytics report
     */
    public function exportReport(Request $request): JsonResponse
    {
        try {
            $dateRange = $request->get('days', 30);
            $format = $request->get('format', 'json');
            $startDate = Carbon::now()->subDays($dateRange);
            
            $reportData = [
                'report_info' => [
                    'generated_at' => Carbon::now()->toISOString(),
                    'period' => [
                        'start' => $startDate->format('Y-m-d'),
                        'end' => Carbon::now()->format('Y-m-d'),
                        'days' => $dateRange
                    ],
                    'format' => $format
                ],
                'overview' => $this->getOverview($request)->getData()->data,
                'user_analytics' => $this->getUserAnalytics($request)->getData()->data,
                'content_analytics' => $this->getContentAnalytics($request)->getData()->data,
                'financial_analytics' => $this->getFinancialAnalytics($request)->getData()->data
            ];
            
            if ($format === 'csv') {
                return $this->generateCsvReport($reportData);
            }
            
            return response()->json([
                'success' => true,
                'data' => $reportData
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export report',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Helper methods for metrics calculation

    private function getUserMetrics(Carbon $startDate): array
    {
        $totalUsers = User::count();
        $newUsers = User::where('created_at', '>=', $startDate)->count();
        $activeUsers = User::where('last_login_at', '>=', Carbon::now()->subDays(7))->count();
        $verifiedUsers = User::where('email_verified_at', '!=', null)->count();
        
        $growthRate = $totalUsers > 0 ? (($newUsers / $totalUsers) * 100) : 0;
        
        return [
            'total' => $totalUsers,
            'new_this_period' => $newUsers,
            'active' => $activeUsers,
            'verified' => $verifiedUsers,
            'growth_rate' => round($growthRate, 2),
            'daily_signups' => $this->getDailySignups($startDate)
        ];
    }

    private function getWebinarMetrics(Carbon $startDate): array
    {
        $totalWebinars = Webinar::count();
        $upcomingWebinars = Webinar::where('start_time', '>', Carbon::now())->count();
        $completedWebinars = Webinar::where('end_time', '<', Carbon::now())->count();
        $totalRegistrations = DB::table('webinar_registrations')->count();
        
        return [
            'total' => $totalWebinars,
            'upcoming' => $upcomingWebinars,
            'completed' => $completedWebinars,
            'total_registrations' => $totalRegistrations,
            'avg_registrations_per_webinar' => $totalWebinars > 0 ? round($totalRegistrations / $totalWebinars, 2) : 0
        ];
    }

    private function getContentMetrics(Carbon $startDate): array
    {
        $totalBlogs = Blog::count();
        $publishedBlogs = Blog::where('status', 'published')->count();
        $totalContributions = ResearchContribution::count();
        $approvedContributions = ResearchContribution::where('status', 'approved')->count();
        
        return [
            'blogs' => [
                'total' => $totalBlogs,
                'published' => $publishedBlogs,
                'draft' => $totalBlogs - $publishedBlogs
            ],
            'contributions' => [
                'total' => $totalContributions,
                'approved' => $approvedContributions,
                'pending' => $totalContributions - $approvedContributions
            ]
        ];
    }

    private function getPaymentMetrics(Carbon $startDate): array
    {
        $totalPayments = Payment::count();
        $completedPayments = Payment::where('status', 'completed')->count();
        $totalAmount = Payment::where('status', 'completed')->sum('amount');
        $periodPayments = Payment::where('created_at', '>=', $startDate)->count();
        $periodAmount = Payment::where('created_at', '>=', $startDate)
            ->where('status', 'completed')
            ->sum('amount');
        
        return [
            'total' => $totalPayments,
            'completed' => $completedPayments,
            'total_amount' => $totalAmount,
            'period_payments' => $periodPayments,
            'period_amount' => $periodAmount,
            'success_rate' => $totalPayments > 0 ? round(($completedPayments / $totalPayments) * 100, 2) : 0
        ];
    }

    private function getEngagementMetrics(Carbon $startDate): array
    {
        $newsletterSubscribers = NewsletterSubscription::count();
        $activeSubscribers = NewsletterSubscription::where('last_email_sent_at', '>=', Carbon::now()->subDays(30))->count();
        
        return [
            'newsletter_subscribers' => $newsletterSubscribers,
            'active_subscribers' => $activeSubscribers,
            'engagement_rate' => $newsletterSubscribers > 0 ? round(($activeSubscribers / $newsletterSubscribers) * 100, 2) : 0
        ];
    }

    private function getRevenueMetrics(Carbon $startDate): array
    {
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $periodRevenue = Payment::where('created_at', '>=', $startDate)
            ->where('status', 'completed')
            ->sum('amount');
        
        $donationRevenue = SupportDonation::where('status', 'completed')->sum('amount');
        $webinarRevenue = Payment::where('status', 'completed')
            ->whereJsonContains('metadata->type', 'webinar_registration')
            ->sum('amount');
        
        return [
            'total_revenue' => $totalRevenue,
            'period_revenue' => $periodRevenue,
            'donation_revenue' => $donationRevenue,
            'webinar_revenue' => $webinarRevenue,
            'revenue_growth' => $this->calculateRevenueGrowth($startDate)
        ];
    }

    private function getGrowthMetrics(Carbon $startDate): array
    {
        $previousPeriod = Carbon::now()->subDays(60);
        $currentPeriodUsers = User::where('created_at', '>=', $startDate)->count();
        $previousPeriodUsers = User::whereBetween('created_at', [$previousPeriod, $startDate])->count();
        
        $userGrowth = $previousPeriodUsers > 0 ? 
            (($currentPeriodUsers - $previousPeriodUsers) / $previousPeriodUsers) * 100 : 0;
        
        return [
            'user_growth_rate' => round($userGrowth, 2),
            'content_growth_rate' => $this->calculateContentGrowth($startDate),
            'revenue_growth_rate' => $this->calculateRevenueGrowth($startDate)
        ];
    }

    private function getPerformanceMetrics(Carbon $startDate): array
    {
        return [
            'page_load_time' => $this->getAveragePageLoadTime(),
            'error_rate' => $this->getErrorRate(),
            'uptime' => $this->getUptimePercentage(),
            'database_performance' => $this->getDatabasePerformance()
        ];
    }

    // Additional helper methods

    private function getDailySignups(Carbon $startDate): array
    {
        return User::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();
    }

    private function calculateRevenueGrowth(Carbon $startDate): float
    {
        $previousPeriod = Carbon::now()->subDays(60);
        $currentRevenue = Payment::where('created_at', '>=', $startDate)
            ->where('status', 'completed')
            ->sum('amount');
        $previousRevenue = Payment::whereBetween('created_at', [$previousPeriod, $startDate])
            ->where('status', 'completed')
            ->sum('amount');
        
        return $previousRevenue > 0 ? (($currentRevenue - $previousRevenue) / $previousRevenue) * 100 : 0;
    }

    private function calculateContentGrowth(Carbon $startDate): float
    {
        $previousPeriod = Carbon::now()->subDays(60);
        $currentContent = Blog::where('created_at', '>=', $startDate)->count();
        $previousContent = Blog::whereBetween('created_at', [$previousPeriod, $startDate])->count();
        
        return $previousContent > 0 ? (($currentContent - $previousContent) / $previousContent) * 100 : 0;
    }

    private function getActiveUsersCount(): int
    {
        return User::where('last_login_at', '>=', Carbon::now()->subMinutes(30))->count();
    }

    private function getCurrentWebinarsData(): array
    {
        return Webinar::where('start_time', '<=', Carbon::now())
            ->where('end_time', '>=', Carbon::now())
            ->with('registrations')
            ->get()
            ->map(function ($webinar) {
                return [
                    'id' => $webinar->id,
                    'title' => $webinar->title,
                    'participants' => $webinar->registrations->count(),
                    'start_time' => $webinar->start_time,
                    'end_time' => $webinar->end_time
                ];
            })
            ->toArray();
    }

    private function getRecentActivityData(): array
    {
        $activities = [];
        
        // Recent user registrations
        $recentUsers = User::latest()->take(5)->get();
        foreach ($recentUsers as $user) {
            $activities[] = [
                'type' => 'user_registration',
                'description' => "New user registered: {$user->name}",
                'timestamp' => $user->created_at,
                'user_id' => $user->id
            ];
        }
        
        // Recent payments
        $recentPayments = Payment::latest()->take(5)->get();
        foreach ($recentPayments as $payment) {
            $activities[] = [
                'type' => 'payment',
                'description' => "Payment received: \${$payment->amount}",
                'timestamp' => $payment->created_at,
                'payment_id' => $payment->id
            ];
        }
        
        // Sort by timestamp
        usort($activities, function ($a, $b) {
            return $b['timestamp'] <=> $a['timestamp'];
        });
        
        return array_slice($activities, 0, 10);
    }

    private function getSystemHealthData(): array
    {
        return [
            'database_connections' => DB::connection()->getPdo() ? 'Connected' : 'Disconnected',
            'cache_status' => Cache::has('health_check') ? 'Working' : 'Issues',
            'memory_usage' => memory_get_usage(true),
            'peak_memory' => memory_get_peak_usage(true)
        ];
    }

    private function generateCsvReport(array $data): JsonResponse
    {
        // Implementation for CSV generation
        // This would create a CSV file and return download link
        return response()->json([
            'success' => true,
            'message' => 'CSV export not implemented yet',
            'data' => $data
        ]);
    }

    // Additional analytics methods for specific data types

    private function getUserAcquisitionData(Carbon $startDate): array
    {
        return [
            'sources' => $this->getUserAcquisitionSources($startDate),
            'conversion_funnel' => $this->getConversionFunnel($startDate),
            'cost_per_acquisition' => $this->getCostPerAcquisition($startDate)
        ];
    }

    private function getUserBehaviorData(Carbon $startDate): array
    {
        return [
            'session_duration' => $this->getAverageSessionDuration($startDate),
            'pages_per_session' => $this->getPagesPerSession($startDate),
            'bounce_rate' => $this->getBounceRate($startDate),
            'user_journey' => $this->getUserJourneyData($startDate)
        ];
    }

    private function getUserRetentionData(Carbon $startDate): array
    {
        return [
            'retention_rates' => $this->getRetentionRates($startDate),
            'churn_analysis' => $this->getChurnAnalysis($startDate),
            'lifetime_value' => $this->getCustomerLifetimeValue($startDate)
        ];
    }

    private function getUserDemographicsData(Carbon $startDate): array
    {
        return [
            'age_groups' => $this->getAgeGroupDistribution($startDate),
            'geographic_distribution' => $this->getGeographicDistribution($startDate),
            'device_types' => $this->getDeviceTypeDistribution($startDate)
        ];
    }

    private function getUserSegmentsData(Carbon $startDate): array
    {
        return [
            'active_users' => $this->getActiveUserSegments($startDate),
            'premium_users' => $this->getPremiumUserSegments($startDate),
            'engaged_users' => $this->getEngagedUserSegments($startDate)
        ];
    }

    // Placeholder methods for additional analytics
    private function getUserAcquisitionSources(Carbon $startDate): array { return []; }
    private function getConversionFunnel(Carbon $startDate): array { return []; }
    private function getCostPerAcquisition(Carbon $startDate): array { return []; }
    private function getAverageSessionDuration(Carbon $startDate): float { return 0.0; }
    private function getPagesPerSession(Carbon $startDate): float { return 0.0; }
    private function getBounceRate(Carbon $startDate): float { return 0.0; }
    private function getUserJourneyData(Carbon $startDate): array { return []; }
    private function getRetentionRates(Carbon $startDate): array { return []; }
    private function getChurnAnalysis(Carbon $startDate): array { return []; }
    private function getCustomerLifetimeValue(Carbon $startDate): float { return 0.0; }
    private function getAgeGroupDistribution(Carbon $startDate): array { return []; }
    private function getGeographicDistribution(Carbon $startDate): array { return []; }
    private function getDeviceTypeDistribution(Carbon $startDate): array { return []; }
    private function getActiveUserSegments(Carbon $startDate): array { return []; }
    private function getPremiumUserSegments(Carbon $startDate): array { return []; }
    private function getEngagedUserSegments(Carbon $startDate): array { return []; }
    private function getBlogPerformanceData(Carbon $startDate): array { return []; }
    private function getWebinarPerformanceData(Carbon $startDate): array { return []; }
    private function getContributionPerformanceData(Carbon $startDate): array { return []; }
    private function getTopContentData(Carbon $startDate): array { return []; }
    private function getContentTrendsData(Carbon $startDate): array { return []; }
    private function getRevenueData(Carbon $startDate): array { return []; }
    private function getPaymentData(Carbon $startDate): array { return []; }
    private function getDonationData(Carbon $startDate): array { return []; }
    private function getSubscriptionData(Carbon $startDate): array { return []; }
    private function getFinancialMetrics(Carbon $startDate): array { return []; }
    private function getAveragePageLoadTime(): float { return 0.0; }
    private function getErrorRate(): float { return 0.0; }
    private function getUptimePercentage(): float { return 99.9; }
    private function getDatabasePerformance(): array { return []; }
} 