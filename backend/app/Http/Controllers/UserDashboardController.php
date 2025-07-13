<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Webinar;
use App\Models\Blog;
use App\Models\Contribution;
use App\Models\Payment;
use App\Models\NewsletterSubscription;

class UserDashboardController extends Controller
{
    /**
     * Get comprehensive user dashboard data
     */
    public function getDashboard(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        // Cache dashboard data for performance
        $cacheKey = "user_dashboard_{$user->id}";
        
        return Cache::remember($cacheKey, 300, function () use ($user) {
            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $this->getUserProfile($user),
                    'stats' => $this->getUserStats($user),
                    'recent_activity' => $this->getRecentActivity($user),
                    'upcoming_webinars' => $this->getUpcomingWebinars($user),
                    'recommendations' => $this->getRecommendations($user),
                    'notifications' => $this->getNotifications($user),
                    'quick_actions' => $this->getQuickActions($user)
                ]
            ]);
        });
    }

    /**
     * Get user profile with enhanced data
     */
    private function getUserProfile(User $user): array
    {
        $profile = $user->toArray();
        
        // Add computed fields
        $profile['member_since'] = $user->created_at->diffForHumans();
        $profile['last_seen'] = $user->last_seen_at ? $user->last_seen_at->diffForHumans() : 'Never';
        $profile['reputation_level'] = $this->getReputationLevel($user->reputation_score ?? 0);
        $profile['completion_rate'] = $this->getCompletionRate($user);
        $profile['streak_days'] = $this->getStreakDays($user);
        
        return $profile;
    }

    /**
     * Get comprehensive user statistics
     */
    private function getUserStats(User $user): array
    {
        $stats = [
            'webinars_watched' => $user->webinars()->count(),
            'blogs_read' => $user->blogs()->count(),
            'contributions_made' => $user->contributions()->count(),
            'total_payments' => $user->payments()->sum('amount'),
            'favorite_categories' => $this->getFavoriteCategories($user),
            'learning_progress' => $this->getLearningProgress($user),
            'engagement_score' => $this->getEngagementScore($user),
            'monthly_activity' => $this->getMonthlyActivity($user)
        ];

        return $stats;
    }

    /**
     * Get user's recent activity
     */
    private function getRecentActivity(User $user): array
    {
        $activities = [];

        // Recent webinar registrations
        $webinarRegistrations = $user->webinars()
            ->orderBy('pivot_created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($webinar) {
                return [
                    'type' => 'webinar_registration',
                    'title' => $webinar->title,
                    'date' => $webinar->pivot->created_at,
                    'icon' => 'video-camera',
                    'url' => "/webinars/{$webinar->id}"
                ];
            });

        // Recent blog reads
        $blogReads = $user->blogs()
            ->orderBy('pivot_created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($blog) {
                return [
                    'type' => 'blog_read',
                    'title' => $blog->title,
                    'date' => $blog->pivot->created_at,
                    'icon' => 'document-text',
                    'url' => "/blog/{$blog->id}"
                ];
            });

        // Recent contributions
        $contributions = $user->contributions()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($contribution) {
                return [
                    'type' => 'contribution',
                    'title' => $contribution->title,
                    'date' => $contribution->created_at,
                    'icon' => 'light-bulb',
                    'url' => "/contributions/{$contribution->id}"
                ];
            });

        // Recent payments
        $payments = $user->payments()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($payment) {
                return [
                    'type' => 'payment',
                    'title' => "Payment of \${$payment->amount}",
                    'date' => $payment->created_at,
                    'icon' => 'credit-card',
                    'url' => "/payments/{$payment->id}"
                ];
            });

        $activities = array_merge(
            $webinarRegistrations->toArray(),
            $blogReads->toArray(),
            $contributions->toArray(),
            $payments->toArray()
        );

        // Sort by date
        usort($activities, function ($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        return array_slice($activities, 0, 10);
    }

    /**
     * Get upcoming webinars for user
     */
    private function getUpcomingWebinars(User $user): array
    {
        $upcomingWebinars = Webinar::where('scheduled_at', '>', now())
            ->where('status', 'scheduled')
            ->orderBy('scheduled_at', 'asc')
            ->limit(5)
            ->get()
            ->map(function ($webinar) use ($user) {
                $isRegistered = $user->webinars()->where('webinar_id', $webinar->id)->exists();
                
                return [
                    'id' => $webinar->id,
                    'title' => $webinar->title,
                    'description' => $webinar->description,
                    'scheduled_at' => $webinar->scheduled_at,
                    'speaker_name' => $webinar->speaker_name,
                    'category' => $webinar->category,
                    'is_registered' => $isRegistered,
                    'registration_url' => $isRegistered ? "/webinars/{$webinar->id}" : "/webinars/{$webinar->id}/register"
                ];
            });

        return $upcomingWebinars->toArray();
    }

    /**
     * Get personalized recommendations
     */
    private function getRecommendations(User $user): array
    {
        $recommendations = [];

        // Get user's favorite categories
        $favoriteCategories = $this->getFavoriteCategories($user);

        // Recommend webinars based on favorite categories
        if (!empty($favoriteCategories)) {
            $topCategory = array_keys($favoriteCategories)[0];
            
            $recommendedWebinars = Webinar::where('category', $topCategory)
                ->where('scheduled_at', '>', now())
                ->where('status', 'scheduled')
                ->orderBy('views', 'desc')
                ->limit(3)
                ->get()
                ->map(function ($webinar) {
                    return [
                        'type' => 'webinar',
                        'id' => $webinar->id,
                        'title' => $webinar->title,
                        'description' => $webinar->description,
                        'category' => $webinar->category,
                        'reason' => "Based on your interest in {$webinar->category}",
                        'url' => "/webinars/{$webinar->id}"
                    ];
                });

            $recommendations = array_merge($recommendations, $recommendedWebinars->toArray());
        }

        // Recommend popular blogs
        $popularBlogs = Blog::where('status', 'published')
            ->orderBy('views', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($blog) {
                return [
                    'type' => 'blog',
                    'id' => $blog->id,
                    'title' => $blog->title,
                    'description' => $blog->excerpt,
                    'author' => $blog->author,
                    'reason' => 'Popular among our community',
                    'url' => "/blog/{$blog->id}"
                ];
            });

        $recommendations = array_merge($recommendations, $popularBlogs->toArray());

        return $recommendations;
    }

    /**
     * Get user notifications
     */
    private function getNotifications(User $user): array
    {
        $notifications = [];

        // Check for upcoming webinars
        $upcomingWebinars = $user->webinars()
            ->where('scheduled_at', '>', now())
            ->where('scheduled_at', '<', now()->addDays(7))
            ->get();

        foreach ($upcomingWebinars as $webinar) {
            $notifications[] = [
                'type' => 'webinar_reminder',
                'title' => "Upcoming Webinar: {$webinar->title}",
                'message' => "Your webinar starts in " . $webinar->scheduled_at->diffForHumans(),
                'date' => $webinar->scheduled_at,
                'url' => "/webinars/{$webinar->id}"
            ];
        }

        // Check for new content in favorite categories
        $favoriteCategories = $this->getFavoriteCategories($user);
        if (!empty($favoriteCategories)) {
            $topCategory = array_keys($favoriteCategories)[0];
            
            $newContent = Blog::where('category', $topCategory)
                ->where('created_at', '>', now()->subDays(7))
                ->limit(3)
                ->get();

            foreach ($newContent as $blog) {
                $notifications[] = [
                    'type' => 'new_content',
                    'title' => "New {$topCategory} Content",
                    'message' => "New blog post: {$blog->title}",
                    'date' => $blog->created_at,
                    'url' => "/blog/{$blog->id}"
                ];
            }
        }

        return $notifications;
    }

    /**
     * Get quick actions for user
     */
    private function getQuickActions(User $user): array
    {
        $actions = [
            [
                'title' => 'Browse Webinars',
                'description' => 'Find upcoming webinars',
                'icon' => 'video-camera',
                'url' => '/webinars',
                'color' => 'blue'
            ],
            [
                'title' => 'Read Blog',
                'description' => 'Explore latest articles',
                'icon' => 'document-text',
                'url' => '/blog',
                'color' => 'green'
            ],
            [
                'title' => 'Make Contribution',
                'description' => 'Share your research',
                'icon' => 'light-bulb',
                'url' => '/contributions/create',
                'color' => 'yellow'
            ],
            [
                'title' => 'Update Profile',
                'description' => 'Manage your account',
                'icon' => 'user',
                'url' => '/profile',
                'color' => 'purple'
            ]
        ];

        // Add premium actions for premium users
        if ($user->subscription_status === 'active') {
            $actions[] = [
                'title' => 'Premium Content',
                'description' => 'Access exclusive content',
                'icon' => 'star',
                'url' => '/premium',
                'color' => 'gold'
            ];
        }

        return $actions;
    }

    /**
     * Get user's favorite categories
     */
    private function getFavoriteCategories(User $user): array
    {
        $categories = [];

        // Get categories from watched webinars
        $webinarCategories = $user->webinars()
            ->selectRaw('category, COUNT(*) as count')
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->pluck('count', 'category')
            ->toArray();

        // Get categories from read blogs
        $blogCategories = $user->blogs()
            ->selectRaw('category, COUNT(*) as count')
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->pluck('count', 'category')
            ->toArray();

        // Merge and sort categories
        foreach ($webinarCategories as $category => $count) {
            $categories[$category] = ($categories[$category] ?? 0) + $count;
        }

        foreach ($blogCategories as $category => $count) {
            $categories[$category] = ($categories[$category] ?? 0) + $count;
        }

        arsort($categories);
        return array_slice($categories, 0, 5, true);
    }

    /**
     * Get learning progress
     */
    private function getLearningProgress(User $user): array
    {
        $totalWebinars = Webinar::where('status', 'completed')->count();
        $watchedWebinars = $user->webinars()->count();
        $progress = $totalWebinars > 0 ? ($watchedWebinars / $totalWebinars) * 100 : 0;

        return [
            'total_webinars' => $totalWebinars,
            'watched_webinars' => $watchedWebinars,
            'progress_percentage' => round($progress, 1),
            'next_milestone' => $this->getNextMilestone($watchedWebinars)
        ];
    }

    /**
     * Get engagement score
     */
    private function getEngagementScore(User $user): int
    {
        $score = 0;

        // Base score for registration
        $score += 10;

        // Points for webinars watched
        $score += $user->webinars()->count() * 5;

        // Points for blogs read
        $score += $user->blogs()->count() * 3;

        // Points for contributions
        $score += $user->contributions()->count() * 10;

        // Points for payments
        $score += $user->payments()->count() * 15;

        // Points for streak days
        $score += $this->getStreakDays($user) * 2;

        return min($score, 100); // Cap at 100
    }

    /**
     * Get monthly activity
     */
    private function getMonthlyActivity(User $user): array
    {
        $activity = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->format('M Y');

            $webinarCount = $user->webinars()
                ->whereYear('pivot_created_at', $date->year)
                ->whereMonth('pivot_created_at', $date->month)
                ->count();

            $blogCount = $user->blogs()
                ->whereYear('pivot_created_at', $date->year)
                ->whereMonth('pivot_created_at', $date->month)
                ->count();

            $contributionCount = $user->contributions()
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $activity[$month] = [
                'webinars' => $webinarCount,
                'blogs' => $blogCount,
                'contributions' => $contributionCount,
                'total' => $webinarCount + $blogCount + $contributionCount
            ];
        }

        return $activity;
    }

    /**
     * Get reputation level
     */
    private function getReputationLevel(int $score): string
    {
        if ($score >= 90) return 'Expert';
        if ($score >= 70) return 'Advanced';
        if ($score >= 50) return 'Intermediate';
        if ($score >= 30) return 'Beginner';
        return 'Newcomer';
    }

    /**
     * Get completion rate
     */
    private function getCompletionRate(User $user): float
    {
        $startedWebinars = $user->webinars()->count();
        $completedWebinars = $user->webinars()->where('status', 'completed')->count();
        
        return $startedWebinars > 0 ? round(($completedWebinars / $startedWebinars) * 100, 1) : 0;
    }

    /**
     * Get streak days
     */
    private function getStreakDays(User $user): int
    {
        // This would need to be implemented based on your activity tracking
        // For now, return a placeholder
        return rand(1, 30);
    }

    /**
     * Get next milestone
     */
    private function getNextMilestone(int $current): string
    {
        $milestones = [5, 10, 25, 50, 100];
        
        foreach ($milestones as $milestone) {
            if ($current < $milestone) {
                return "Watch {$milestone} webinars";
            }
        }
        
        return "You've reached all milestones!";
    }

    /**
     * Update user's last seen timestamp
     */
    public function updateLastSeen(): JsonResponse
    {
        $user = auth()->user();
        $user->update(['last_seen_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Last seen updated'
        ]);
    }

    /**
     * Get user's learning path
     */
    public function getLearningPath(): JsonResponse
    {
        $user = auth()->user();
        
        $learningPath = [
            'current_level' => $this->getReputationLevel($user->reputation_score ?? 0),
            'next_level' => $this->getNextLevel($user->reputation_score ?? 0),
            'progress_to_next' => $this->getProgressToNextLevel($user->reputation_score ?? 0),
            'recommended_actions' => $this->getRecommendedActions($user)
        ];

        return response()->json([
            'success' => true,
            'data' => $learningPath
        ]);
    }

    /**
     * Get next level
     */
    private function getNextLevel(int $currentScore): string
    {
        if ($currentScore < 30) return 'Beginner';
        if ($currentScore < 50) return 'Intermediate';
        if ($currentScore < 70) return 'Advanced';
        if ($currentScore < 90) return 'Expert';
        return 'Master';
    }

    /**
     * Get progress to next level
     */
    private function getProgressToNextLevel(int $currentScore): int
    {
        if ($currentScore < 30) return round(($currentScore / 30) * 100);
        if ($currentScore < 50) return round((($currentScore - 30) / 20) * 100);
        if ($currentScore < 70) return round((($currentScore - 50) / 20) * 100);
        if ($currentScore < 90) return round((($currentScore - 70) / 20) * 100);
        return 100;
    }

    /**
     * Get recommended actions for level progression
     */
    private function getRecommendedActions(User $user): array
    {
        $actions = [];
        $score = $user->reputation_score ?? 0;

        if ($score < 30) {
            $actions = [
                'Watch your first webinar',
                'Complete your profile',
                'Read a blog post'
            ];
        } elseif ($score < 50) {
            $actions = [
                'Watch 5 more webinars',
                'Make your first contribution',
                'Join a discussion'
            ];
        } elseif ($score < 70) {
            $actions = [
                'Watch 10 more webinars',
                'Make 3 contributions',
                'Share your expertise'
            ];
        } elseif ($score < 90) {
            $actions = [
                'Become a webinar speaker',
                'Mentor other users',
                'Create premium content'
            ];
        } else {
            $actions = [
                'Maintain your expert status',
                'Continue sharing knowledge',
                'Help grow the community'
            ];
        }

        return $actions;
    }
} 