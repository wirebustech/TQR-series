<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Webinar;
use App\Models\ResearchContribution;
use App\Models\Blog;
use App\Models\Page;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin users
        $adminUsers = [
            [
                'name' => 'Admin User',
                'email' => 'admin@tqrs.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'status' => 'active',
                'organization' => 'TQRS Admin',
                'email_verified_at' => now(),
                'is_admin' => true,
            ],
            [
                'name' => 'Moderator User',
                'email' => 'moderator@tqrs.com',
                'password' => Hash::make('password123'),
                'role' => 'moderator',
                'status' => 'active',
                'organization' => 'TQRS Team',
                'email_verified_at' => now(),
                'is_admin' => false,
            ],
        ];

        foreach ($adminUsers as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        // Create sample regular users
        $regularUsers = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'status' => 'active',
                'organization' => 'Research Institute',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password123'),
                'role' => 'researcher',
                'status' => 'active',
                'organization' => 'University of Research',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Bob Wilson',
                'email' => 'bob@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'status' => 'active',
                'organization' => 'Independent Researcher',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($regularUsers as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        // Create sample webinars
        $webinars = [
            [
                'title' => 'Introduction to Qualitative Research',
                'description' => 'A comprehensive introduction to qualitative research methodologies and best practices.',
                'scheduled_at' => now()->addDays(7),
                'duration' => 120,
                'max_attendees' => 50,
                'meeting_url' => 'https://zoom.us/j/123456789',
                'platform' => 'zoom',
                'status' => 'published',
                'requires_registration' => true,
                'is_public' => true,
                'tags' => 'research, methodology, introduction',
                'user_id' => User::where('email', 'admin@tqrs.com')->first()->id,
            ],
            [
                'title' => 'Advanced Data Analysis Techniques',
                'description' => 'Learn advanced techniques for analyzing qualitative data using modern tools.',
                'scheduled_at' => now()->addDays(14),
                'duration' => 180,
                'max_attendees' => 30,
                'meeting_url' => 'https://zoom.us/j/987654321',
                'platform' => 'zoom',
                'status' => 'published',
                'requires_registration' => true,
                'is_public' => true,
                'tags' => 'data analysis, advanced, techniques',
                'user_id' => User::where('email', 'moderator@tqrs.com')->first()->id,
            ],
            [
                'title' => 'Ethical Considerations in Research',
                'description' => 'Understanding ethical principles and considerations in qualitative research.',
                'scheduled_at' => now()->addDays(21),
                'duration' => 90,
                'max_attendees' => 40,
                'meeting_url' => 'https://zoom.us/j/456789123',
                'platform' => 'zoom',
                'status' => 'draft',
                'requires_registration' => true,
                'is_public' => true,
                'tags' => 'ethics, research, principles',
                'user_id' => User::where('email', 'admin@tqrs.com')->first()->id,
            ],
        ];

        foreach ($webinars as $webinarData) {
            Webinar::updateOrCreate(
                ['title' => $webinarData['title']],
                $webinarData
            );
        }

        // Create sample research contributions
        $contributions = [
            [
                'title' => 'Mixed Methods Research: A Comprehensive Guide',
                'description' => 'This paper explores the integration of qualitative and quantitative research methods.',
                'file_url' => 'https://example.com/papers/mixed-methods-guide.pdf',
                'status' => 'pending',
                'user_id' => User::where('email', 'jane@example.com')->first()->id,
            ],
            [
                'title' => 'Case Study: Qualitative Research in Education',
                'description' => 'A detailed case study examining qualitative research applications in educational settings.',
                'file_url' => 'https://example.com/papers/education-case-study.pdf',
                'status' => 'approved',
                'user_id' => User::where('email', 'john@example.com')->first()->id,
            ],
            [
                'title' => 'Methodology Framework for Social Sciences',
                'description' => 'A comprehensive framework for conducting qualitative research in social sciences.',
                'file_url' => 'https://example.com/papers/methodology-framework.pdf',
                'status' => 'rejected',
                'user_id' => User::where('email', 'bob@example.com')->first()->id,
            ],
        ];

        foreach ($contributions as $contributionData) {
            ResearchContribution::updateOrCreate(
                ['title' => $contributionData['title']],
                $contributionData
            );
        }

        // Create sample blogs
        $blogs = [
            [
                'title' => 'Getting Started with Qualitative Research',
                'slug' => 'getting-started-with-qualitative-research',
                'content' => 'A beginner-friendly guide to starting your qualitative research journey.',
                'excerpt' => 'Learn the basics of qualitative research and how to get started.',
                'is_published' => true,
                'published_at' => now()->subDays(5),
                'author_id' => User::where('email', 'admin@tqrs.com')->first()->id,
            ],
            [
                'title' => 'Best Practices for Data Collection',
                'slug' => 'best-practices-for-data-collection',
                'content' => 'Essential tips and best practices for collecting qualitative data effectively.',
                'excerpt' => 'Discover proven methods for collecting high-quality qualitative data.',
                'is_published' => true,
                'published_at' => now()->subDays(3),
                'author_id' => User::where('email', 'moderator@tqrs.com')->first()->id,
            ],
            [
                'title' => 'Understanding Research Ethics',
                'slug' => 'understanding-research-ethics',
                'content' => 'A comprehensive overview of ethical considerations in research.',
                'excerpt' => 'Navigate the complex landscape of research ethics and compliance.',
                'is_published' => false,
                'published_at' => null,
                'author_id' => User::where('email', 'admin@tqrs.com')->first()->id,
            ],
        ];

        foreach ($blogs as $blogData) {
            Blog::updateOrCreate(
                ['title' => $blogData['title']],
                $blogData
            );
        }

        // Create sample pages
        $pages = [
            [
                'title' => 'About TQRS',
                'slug' => 'about',
                'content' => 'Learn about The Qualitative Research Series and our mission.',
                'is_published' => true,
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact',
                'content' => 'Get in touch with the TQRS team for questions and support.',
                'is_published' => true,
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy',
                'content' => 'Our privacy policy and data protection practices.',
                'is_published' => true,
            ],
        ];

        foreach ($pages as $pageData) {
            Page::updateOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }

        $this->command->info('Admin seeder completed successfully!');
        $this->command->info('Admin credentials: admin@tqrs.com / password123');
        $this->command->info('Moderator credentials: moderator@tqrs.com / password123');
    }
} 