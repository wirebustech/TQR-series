<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Webinar;
use Carbon\Carbon;

class WebinarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $webinars = [
            [
                'title' => 'Introduction to Qualitative Research Methods',
                'description' => 'A comprehensive overview of qualitative research methodologies, including interviews, focus groups, and ethnographic studies. Perfect for beginners and those looking to refresh their knowledge.',
                'scheduled_at' => Carbon::now()->addDays(7)->setTime(14, 0),
                'duration' => 90,
                'max_attendees' => 50,
                'platform' => 'zoom',
                'meeting_url' => 'https://zoom.us/j/123456789',
                'tags' => 'qualitative research, methodology, interviews, focus groups',
                'status' => 'published',
                'requires_registration' => true,
                'is_public' => true,
                'user_id' => 1
            ],
            [
                'title' => 'Advanced Data Analysis in Qualitative Research',
                'description' => 'Deep dive into advanced techniques for analyzing qualitative data, including thematic analysis, grounded theory, and NVivo software usage.',
                'scheduled_at' => Carbon::now()->addDays(14)->setTime(15, 30),
                'duration' => 120,
                'max_attendees' => 30,
                'platform' => 'teams',
                'meeting_url' => 'https://teams.microsoft.com/l/meetup-join/123456',
                'tags' => 'data analysis, thematic analysis, NVivo, advanced methods',
                'status' => 'published',
                'requires_registration' => true,
                'is_public' => true,
                'user_id' => 1
            ],
            [
                'title' => 'Ethical Considerations in Qualitative Research',
                'description' => 'Critical discussion of ethical issues in qualitative research, including informed consent, confidentiality, and researcher reflexivity.',
                'scheduled_at' => Carbon::now()->addDays(21)->setTime(10, 0),
                'duration' => 60,
                'max_attendees' => 75,
                'platform' => 'zoom',
                'meeting_url' => 'https://zoom.us/j/987654321',
                'tags' => 'ethics, informed consent, confidentiality, reflexivity',
                'status' => 'published',
                'requires_registration' => true,
                'is_public' => true,
                'user_id' => 1
            ],
            [
                'title' => 'Mixed Methods Research Design',
                'description' => 'Exploring the integration of qualitative and quantitative methods in research design, with practical examples and case studies.',
                'scheduled_at' => Carbon::now()->addDays(28)->setTime(13, 0),
                'duration' => 90,
                'max_attendees' => 40,
                'platform' => 'meet',
                'meeting_url' => 'https://meet.google.com/abc-defg-hij',
                'tags' => 'mixed methods, research design, integration, case studies',
                'status' => 'draft',
                'requires_registration' => true,
                'is_public' => true,
                'user_id' => 1
            ],
            [
                'title' => 'Writing and Publishing Qualitative Research',
                'description' => 'Guidance on writing up qualitative research findings for publication, including journal selection, peer review process, and common pitfalls to avoid.',
                'scheduled_at' => Carbon::now()->addDays(35)->setTime(16, 0),
                'duration' => 75,
                'max_attendees' => 60,
                'platform' => 'zoom',
                'meeting_url' => 'https://zoom.us/j/456789123',
                'tags' => 'writing, publishing, peer review, academic writing',
                'status' => 'draft',
                'requires_registration' => true,
                'is_public' => true,
                'user_id' => 1
            ],
            [
                'title' => 'Digital Ethnography and Online Research Methods',
                'description' => 'Exploring qualitative research methods adapted for digital environments, including social media research, online interviews, and virtual ethnography.',
                'scheduled_at' => Carbon::now()->addDays(42)->setTime(11, 30),
                'duration' => 90,
                'max_attendees' => 35,
                'platform' => 'webex',
                'meeting_url' => 'https://webex.com/meeting/123456',
                'tags' => 'digital ethnography, online research, social media, virtual methods',
                'status' => 'draft',
                'requires_registration' => true,
                'is_public' => true,
                'user_id' => 1
            ]
        ];

        foreach ($webinars as $webinar) {
            Webinar::create($webinar);
        }
    }
}
