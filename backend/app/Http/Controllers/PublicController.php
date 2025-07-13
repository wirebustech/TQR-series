<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Webinar;
use App\Models\Page;
use App\Models\NewsletterSubscription;
use App\Models\SupportDonation;

class PublicController extends Controller
{
    /**
     * Show landing page
     */
    public function home()
    {
        $featuredWebinars = Webinar::where('is_public', true)
            ->where('status', 'published')
            ->where('scheduled_at', '>', now())
            ->orderBy('scheduled_at')
            ->take(3)
            ->get();

        $recentBlogs = Blog::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->take(6)
            ->get();

        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_webinars' => Webinar::where('status', 'published')->count(),
            'total_contributions' => \App\Models\ResearchContribution::where('status', 'approved')->count(),
        ];

        return view('public.home', compact('featuredWebinars', 'recentBlogs', 'stats'));
    }

    /**
     * Show blog listing page
     */
    public function blogs(Request $request)
    {
        $query = Blog::where('is_published', true);
        
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
        }
        
        $blogs = $query->orderBy('published_at', 'desc')->paginate(12);
        
        return view('public.blogs', compact('blogs'));
    }

    /**
     * Show single blog post
     */
    public function blog($slug)
    {
        $blog = Blog::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $relatedBlogs = Blog::where('is_published', true)
            ->where('id', '!=', $blog->id)
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        return view('public.blog', compact('blog', 'relatedBlogs'));
    }

    /**
     * Show webinars listing page
     */
    public function webinars(Request $request)
    {
        $query = Webinar::where('is_public', true);
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        $webinars = $query->orderBy('scheduled_at')->paginate(12);
        
        return view('public.webinars', compact('webinars'));
    }

    /**
     * Show single webinar
     */
    public function webinar($id)
    {
        $webinar = Webinar::where('id', $id)
            ->where('is_public', true)
            ->firstOrFail();

        return view('public.webinar', compact('webinar'));
    }

    /**
     * Show about page
     */
    public function about()
    {
        $page = Page::where('slug', 'about')
            ->where('is_published', true)
            ->firstOrFail();

        return view('public.page', compact('page'));
    }

    /**
     * Show contact page
     */
    public function contact()
    {
        $page = Page::where('slug', 'contact')
            ->where('is_published', true)
            ->firstOrFail();

        return view('public.contact', compact('page'));
    }

    /**
     * Handle newsletter subscription
     */
    public function subscribeNewsletter(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletter_subscriptions,email',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
        ]);

        NewsletterSubscription::create([
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'is_active' => true,
            'subscribed_at' => now(),
            'source' => 'website',
        ]);

        return response()->json(['success' => true, 'message' => 'Successfully subscribed to newsletter!']);
    }

    /**
     * Handle donation submission
     */
    public function submitDonation(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'donor_name' => 'required_if:is_anonymous,false|string|max:255',
            'donor_email' => 'required_if:is_anonymous,false|email',
            'message' => 'nullable|string|max:1000',
            'is_anonymous' => 'boolean',
        ]);

        $donation = SupportDonation::create([
            'amount' => $request->amount,
            'currency' => 'USD',
            'payment_method' => 'online',
            'status' => 'pending',
            'donor_name' => $request->donor_name,
            'donor_email' => $request->donor_email,
            'message' => $request->message,
            'is_anonymous' => $request->is_anonymous ?? false,
        ]);

        // Here you would integrate with payment gateway
        // For now, we'll just mark it as completed
        $donation->update(['status' => 'completed']);

        return response()->json(['success' => true, 'message' => 'Thank you for your donation!']);
    }

    /**
     * Show privacy policy page
     */
    public function privacy()
    {
        $page = Page::where('slug', 'privacy')
            ->where('is_published', true)
            ->firstOrFail();

        return view('public.page', compact('page'));
    }
} 