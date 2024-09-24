<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactUsContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('contact_us_contents')->truncate();
        DB::table('contact_us_contents')->insert([
            'heading' => 'Contact Us',
            'sub_heading' => 'KEMPSEY OUTDOORS',
            'content_heading' => 'Let’s See How We Can Help You!',
            'content_sub_heading' => 'To speak about how I can help capture your essence in film, get in touch.',
            'banner_image_url' => 'https://sagmetic.site/2023/laravel/kempsey/public/faq_images/banner_image_url_1725533396.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
