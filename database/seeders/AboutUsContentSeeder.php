<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AboutUsContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('about_us_contents')->insert([
            'about_us_banner_title' => 'ABOUT US',
            'about_us_banner_image' => 'https://sagmetic.site/2023/laravel/kempsey/public/about_images/about_us_banner_image.png',
            'about_us_banner_sub_title' => 'KEMPSEY OUTDOORS',

            'about_us_heading' => 'Easy and affordable way for the whole family',
            'about_us_logo' => 'https://sagmetic.site/2023/laravel/kempsey/public/about_images/about_us_logo.svg',
            'about_us_details' => '<p class="size32">Camping World and Compleat Angler Kempsey &amp; Barney’s Bikes and Kempsey Firearms</p><p>We stock all the leading brands and we have everything you need for your camping and outdoor adventure: From Tents to Tent pegs, Furniture, Bedding, Lighting and Cooking equipment, Ice boxes, Coolers, Fridges, Kayaks, Rods, Reels, Lures, Lines, even Bikes &amp; Bikes accessories</p><p>Camping is a fun, easy and affordable way for the whole family to relax together, get back to nature and enjoy the simple things in life.</p><p>We stock a wide range of firearm accessories for gun, shooting, reloading, optics, safes &amp; ammunition.</p>',
            'about_us_image' => 'https://sagmetic.site/2023/laravel/kempsey/public/about_images/about_us_image.png',
            'about_us_btn' => 'CONTACT US',
            'about_us_btn_link' => '/contact-us',

            'about_us_shop_title' => 'Why shop with us',
            'about_us_shop_details' => json_encode([
                [
                    'title' => 'Widest Range',
                    'text' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the',
                    'image' => 'https://sagmetic.site/2023/laravel/kempsey/public/about_images/about_us_shop_details1.svg',
                ],
                [
                    'title' => 'Lowest Prices',
                    'text' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the',
                    'image' => 'https://sagmetic.site/2023/laravel/kempsey/public/about_images/about_us_shop_details2.svg',
                ],
                [
                    'title' => 'Customer Service',
                    'text' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the',
                    'image' => 'https://sagmetic.site/2023/laravel/kempsey/public/about_images/about_us_shop_details3.svg',
                ],
            ]),

            'about_us_bottom_title' => 'Browse Kempsey Outdoors Closet',
            'about_us_bottom_banner' => 'https://sagmetic.site/2023/laravel/kempsey/public/about_images/about_us_bottom_banner.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
