<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('site_contents')->insert([
            'header_offer_text' => 'Need help finding something? Let us know!',
            'footer_instagram_name' => '@KempseyOutdoors',
            'footer_instagram_images' => json_encode([
                asset('site_images/insta_post1.png'), 
                asset('site_images/insta_post2.png'), 
                asset('site_images/insta_post3.png'), 
                asset('site_images/insta_post4.png'), 
                asset('site_images/insta_post5.png'), 
                asset('site_images/insta_post6.png')
            ]),
            'footer_contact_title' => 'Join our mailing list to stay up to date on the latest trends and new arrivals.',
            'footer_contact_banner' => asset('site_images/footer_contact_banner.png'),
            'footer_facebook_link' => 'https://facebook.com/',
            'footer_instagram_link' => 'https://instagram.com/',
            'footer_twitter_link' => 'https://twitter.com/',
            'footer_slider_image' => asset('site_images/footer_slider_image.png'),
            'footer_description' => '<p class="">Lorem Ipsum has been the  standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>',
            'address' => '8975 W Charleston Blvd. Suite 190 Las Vegas, NV 89117',
            'phone' => '0 123 4567 890',
            'email' => 'contact@KempseyOutdoors.com',
            'footer_policy' => '© 2024 Kempsey Outdoors, All rights reserved.',
        ]);
    }
}
