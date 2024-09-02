<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HomeContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('home_contents')->insert([
            'closet_section_heading' => 'Lorem Ipsum is simply dummy text of the printing and',
            'closet_section_sub_heading' => 'OUR CLOSET IS YOUR CLOSET',
            'closet_section_btn' => 'CONTACT US',
            'closet_section_btn_link' => '/contact-us',
            'closet_section_banner' => asset('home_images/closet_section_banner.png'),
            'closet_section_banner_heading' => 'Browse Kempsey Outdoors Closet',

            'new_arrivals_first_banner' => asset('home_images/new_arrivals_first_banner.png'),
            'new_arrivals_bg_image' => asset('home_images/new_arrivals_bg_image.png'),
            'new_arrivals_title' => 'New Arrivals Weekly',
            'new_arrivals_text' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            'new_arrivals_btn' => 'VIEW THE COLLECTION',
            'new_arrivals_btn_link' => '/',
            'new_arrivals_logo' => asset('home_images/new_arrivals_logo.svg'),
            'new_arrivals_product_image' => asset('home_images/new_arrivals_product_image.png'),
            'new_arrivals_product_name' => 'Product Name',
            'new_arrivals_product_text' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            'new_arrivals_product_btn' => 'VIEW DETAILS',
            'new_arrivals_product_btn_link' => '/',
            'new_arrivals_product_banner' => asset('home_images/new_arrivals_product_banner.png'),

            'about_section_heading' => 'Easy and affordable way for the whole family',
            'about_section_logo' => asset('home_images/about_section_logo.svg'),
            'about_section_details' => '<p class="size32">Camping World and Compleat Angler Kempsey &amp; Barney’s Bikes and Kempsey Firearms</p><p>We stock all the leading brands and we have everything you need for your camping and outdoor adventure: From Tents to Tent pegs, Furniture, Bedding, Lighting and Cooking equipment, Ice boxes, Coolers, Fridges, Kayaks, Rods, Reels, Lures, Lines, even Bikes &amp; Bikes accessories</p><p>Camping is a fun, easy and affordable way for the whole family to relax together, get back to nature and enjoy the simple things in life.</p><p>We stock a wide range of firearm accessories for gun, shooting, reloading, optics, safes &amp; ammunition.</p>',
            'about_section_image' => asset('home_images/about_section_image.png'),
            'about_section_btn' => 'CONTACT US',
            'about_section_btn_link' => '/contact-us',

            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
