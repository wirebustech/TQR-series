<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OpportunitySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('opportunities')->insert([
            [
                'title' => 'International Research Collaboration',
                'content' => 'Join our global team for a qualitative research project in healthcare.',
                'url' => 'https://example.com/collaboration',
                'type' => 'Collaboration',
                'is_active' => true,
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'title' => 'Funding for Innovative Qualitative Studies',
                'content' => 'Apply for grants supporting new qualitative research methodologies.',
                'url' => 'https://example.com/funding',
                'type' => 'Funding',
                'is_active' => true,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'title' => 'Call for Papers: Qualitative Research Conference',
                'content' => 'Submit your work to our annual international conference.',
                'url' => 'https://example.com/conference',
                'type' => 'Conference',
                'is_active' => true,
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'title' => 'Publication Opportunity in Top Journal',
                'content' => 'Publish your qualitative research in a high-impact journal.',
                'url' => 'https://example.com/publication',
                'type' => 'Publication',
                'is_active' => true,
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            [
                'title' => 'Research Assistant Position Available',
                'content' => 'Seeking a research assistant for a qualitative study on education.',
                'url' => 'https://example.com/assistant',
                'type' => 'Research',
                'is_active' => true,
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
        ]);
    }
} 