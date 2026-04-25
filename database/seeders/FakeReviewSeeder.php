<?php

namespace Database\Seeders;

use App\Models\WebsiteReview;
use Illuminate\Database\Seeder;

class FakeReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reviews = [
            [
                'author_name' => 'Sophia Laurent',
                'comment' => 'The craftsmanship at CAVARI is simply unparalleled. My bespoke necklace is a masterpiece that I will treasure forever.',
                'location' => 'Paris, France',
                'rating' => 5,
                'is_approved' => true
            ],
            [
                'author_name' => 'James Montgomery',
                'comment' => 'Finding ethically sourced gems was my priority. CAVARI provided transparency and a stunning sapphire that exceeded my expectations.',
                'location' => 'London, UK',
                'rating' => 5,
                'is_approved' => true
            ],
            [
                'author_name' => 'Isabella Rossi',
                'comment' => 'The attention to detail and the personalized service made the entire process so special. Highly recommend for unique pieces.',
                'location' => 'Milan, Italy',
                'rating' => 5,
                'is_approved' => true
            ],
            [
                'author_name' => 'Michael Chen',
                'comment' => 'Exceptional quality and timeless design. The loose emerald I purchased is of incredible clarity and color.',
                'location' => 'Singapore',
                'rating' => 5,
                'is_approved' => true
            ]
        ];

        foreach ($reviews as $review) {
            WebsiteReview::create($review);
        }
        
        $this->command->info('Fake website reviews created successfully!');
    }
}
