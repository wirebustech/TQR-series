<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Opportunity;

class OpportunitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $opportunities = [
            [
                'title' => 'Quantum Computing Research Collaboration',
                'content' => 'Join our interdisciplinary team exploring quantum algorithms for machine learning applications.',
                'url' => 'https://example.com/quantum-research',
                'type' => 'Research',
                'is_active' => true,
            ],
            [
                'title' => 'Global Health Data Science Partnership',
                'content' => 'Collaborate with international researchers on epidemiological modeling and health informatics.',
                'url' => 'https://example.com/health-collaboration',
                'type' => 'Collaboration',
                'is_active' => true,
            ],
            [
                'title' => 'NSF Research Grant: AI Ethics',
                'content' => 'Apply for funding to study ethical implications of artificial intelligence in healthcare.',
                'url' => 'https://example.com/nsf-grant',
                'type' => 'Funding',
                'is_active' => true,
            ],
            [
                'title' => 'International Conference on Digital Humanities',
                'content' => 'Present your research at the premier conference for digital humanities scholars.',
                'url' => 'https://example.com/dh-conference',
                'type' => 'Conference',
                'is_active' => true,
            ],
            [
                'title' => 'Special Issue: Sustainable Technology',
                'content' => 'Submit your research on sustainable technology solutions to our peer-reviewed journal.',
                'url' => 'https://example.com/sustainable-tech',
                'type' => 'Publication',
                'is_active' => true,
            ],
        ];

        foreach ($opportunities as $opportunity) {
            Opportunity::create($opportunity);
        }
    }
}
