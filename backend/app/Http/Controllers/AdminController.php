<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Webinar;
use App\Models\ResearchContribution;
use App\Models\Blog;
use App\Models\Page;

class AdminController extends Controller
{
    /**
     * Show admin login page
     */
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            if (!$user->isAdmin()) {
                Auth::logout();
                return back()->withErrors(['email' => 'Admin access required.']);
            }

            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login');
    }

    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        $this->requireAdmin();
        
        $stats = [
            'users' => User::count(),
            'webinars' => Webinar::count(),
            'contributions' => ResearchContribution::count(),
            'blogs' => Blog::count(),
            'pages' => Page::count()
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Show users management page
     */
    public function users()
    {
        $this->requireAdmin();
        
        $users = User::with('profile')->paginate(15);
        return view('admin.users', compact('users'));
    }

    /**
     * Show webinars management page
     */
    public function webinars()
    {
        $this->requireAdmin();
        
        $webinars = Webinar::with('registrations')->paginate(15);
        return view('admin.webinars', compact('webinars'));
    }

    /**
     * Show contributions management page
     */
    public function contributions()
    {
        $this->requireAdmin();
        
        $contributions = ResearchContribution::with('user')->paginate(15);
        return view('admin.contributions', compact('contributions'));
    }

    /**
     * Show analytics dashboard
     */
    public function analytics()
    {
        $this->requireAdmin();
        
        // Get analytics data
        $stats = [
            'total_users' => User::count(),
            'new_users_month' => User::where('created_at', '>=', now()->subMonth())->count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'admin_users' => User::where('role', 'admin')->count(),
            'total_webinars' => Webinar::count(),
            'upcoming_webinars' => Webinar::where('start_time', '>', now())->count(),
            'total_contributions' => ResearchContribution::count(),
            'approved_contributions' => ResearchContribution::where('status', 'approved')->count(),
            'total_blogs' => Blog::count(),
            'published_blogs' => Blog::where('status', 'published')->count(),
            'total_pages' => Page::count()
        ];

        return view('admin.analytics', compact('stats'));
    }

    /**
     * Show pages management
     */
    public function pages()
    {
        $this->requireAdmin();
        
        $pages = Page::paginate(15);
        return view('admin.pages', compact('pages'));
    }

    /**
     * Show blogs management
     */
    public function blogs()
    {
        $this->requireAdmin();
        
        $blogs = Blog::with('user')->paginate(15);
        return view('admin.blogs', compact('blogs'));
    }

    /**
     * Require admin access
     */
    private function requireAdmin()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Admin access required.');
        }
    }
} 