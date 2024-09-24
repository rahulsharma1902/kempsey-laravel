<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaqContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('faq_contents')->truncate();
        DB::table('faq_contents')->insert([
            'heading' => 'FAQ\'S',
            'sub_heading' => 'KEMPSEY OUTDOORS',
            'content_heading' => 'Frequently Asked Questions',
            'banner_image_url' => 'https://sagmetic.site/2023/laravel/kempsey/public/faq_images/banner_image_url_1725531007.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
