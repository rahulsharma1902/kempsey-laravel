<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarouselSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('carousels')->insert([
            [
                'heading' => 'Cozy Up Anywhere, Anytime',
                'sub_heading' => 'Kempsey Outdoors',
                'text' => 'Discover the latest collection of Kempsey Outdoors',
                'button_text' => 'SHOP NOW',
                'button_link' => '/',
                'image' => 'https://sagmetic.site/2023/laravel/kempsey/public/carousel_images/crousel1.png',
                'position' => 1
            ],
            [
                'heading' => 'Cozy Up Anywhere, Anytime',
                'sub_heading' => 'Kempsey Outdoors',
                'text' => 'Discover the latest collection of Kempsey Outdoors',
                'button_text' => 'SHOP NOW',
                'button_link' => '/',
                'image' => 'https://sagmetic.site/2023/laravel/kempsey/public/carousel_images/crousel2.png',
                'position' => 2
            ]
            // Add more slides as needed
        ]);
    }
}
