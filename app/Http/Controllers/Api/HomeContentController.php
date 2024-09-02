<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomeContent;

class HomeContentController extends Controller
{
    //

    public function store(Request $request) {
        // Validate the request data
        // return response()->json($request->all());
        $validated = $request->validate([
            'closet_section_heading' => 'required',
            'closet_section_sub_heading' => 'required',
            'closet_section_btn' => 'required',
            'closet_section_btn_link' => 'required',
            'closet_section_banner' => 'required',
            'closet_section_banner_heading' => 'required',
            
            'new_arrivals_first_banner' => 'required',
            'new_arrivals_bg_image' => 'required',
            'new_arrivals_title' => 'required',
            'new_arrivals_text' => 'required',
            'new_arrivals_btn' => 'required',
            'new_arrivals_btn_link' => 'required',
            'new_arrivals_logo' => 'required',
            'new_arrivals_product_image' => 'required',
            'new_arrivals_product_name' => 'required',
            'new_arrivals_product_text' => 'required',
            'new_arrivals_product_btn' => 'required',
            'new_arrivals_product_btn_link' => 'required',
            'new_arrivals_product_banner' => 'required',
            
            'about_section_heading' => 'required',
            'about_section_logo' => 'required',
            'about_section_details' => 'required',
            'about_section_image' => 'required',
            'about_section_btn' => 'required',
            'about_section_btn_link' => 'required',
        ]);
    
        $home_content = HomeContent::firstOrNew();
    
        $home_content->closet_section_heading = $request->closet_section_heading;
        $home_content->closet_section_sub_heading = $request->closet_section_sub_heading;
        $home_content->closet_section_btn = $request->closet_section_btn;
        $home_content->closet_section_btn_link = $request->closet_section_btn_link;
        $home_content->closet_section_banner = $request->closet_section_banner;
        $home_content->closet_section_banner_heading = $request->closet_section_banner_heading;
        $home_content->new_arrivals_first_banner = $request->new_arrivals_first_banner;
        $home_content->new_arrivals_title = $request->new_arrivals_title;
        $home_content->new_arrivals_text = $request->new_arrivals_text;
        $home_content->new_arrivals_btn = $request->new_arrivals_btn;
        $home_content->new_arrivals_btn_link = $request->new_arrivals_btn_link;
        $home_content->new_arrivals_logo = $request->new_arrivals_logo;
        $home_content->new_arrivals_product_image = $request->new_arrivals_product_image;
        $home_content->new_arrivals_product_name = $request->new_arrivals_product_name;
        $home_content->new_arrivals_product_text = $request->new_arrivals_product_text;
        $home_content->new_arrivals_product_btn = $request->new_arrivals_product_btn;
        $home_content->new_arrivals_product_btn_link = $request->new_arrivals_product_btn_link;
        $home_content->new_arrivals_product_banner = $request->new_arrivals_product_banner;
        $home_content->about_section_heading = $request->about_section_heading;
        $home_content->about_section_logo = $request->about_section_logo;
        $home_content->about_section_details = $request->about_section_details;
        $home_content->about_section_image = $request->about_section_image;
        $home_content->about_section_btn = $request->about_section_btn;
        $home_content->about_section_btn_link = $request->about_section_btn_link;
    
        // Handle file uploads using the upload method
        $home_content->closet_section_banner = $this->upload($request, 'closet_section_banner', $home_content->closet_section_banner);
        $home_content->new_arrivals_first_banner = $this->upload($request, 'new_arrivals_first_banner', $home_content->new_arrivals_first_banner);
        $home_content->new_arrivals_product_image = $this->upload($request, 'new_arrivals_product_image', $home_content->new_arrivals_product_image);
        $home_content->new_arrivals_bg_image = $this->upload($request, 'new_arrivals_bg_image', $home_content->new_arrivals_bg_image);
        $home_content->new_arrivals_logo = $this->upload($request, 'new_arrivals_logo', $home_content->new_arrivals_logo);
        $home_content->new_arrivals_product_banner = $this->upload($request, 'new_arrivals_product_banner', $home_content->new_arrivals_product_banner);
        $home_content->about_section_logo = $this->upload($request, 'about_section_logo', $home_content->about_section_logo);
        $home_content->about_section_image = $this->upload($request, 'about_section_image', $home_content->about_section_image);
    
        // Save the content to the database
        $home_content->save();
        return response()->json([
            'message' => 'Home content changed successfully!',
            'data' => $home_content
        ]);
        // return response()->json(['message' => 'Home content saved successfully!']);
    }
    
    public function upload(Request $request, $fieldName, $currentFilePath = null) {
        if ($request->hasFile($fieldName)) {
            $file = $request->file($fieldName);
            $filename = $fieldName . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('home_images'), $filename);
    
            // Delete the old file if it exists
            // if ($currentFilePath && file_exists(public_path($currentFilePath))) {
            //     unlink(public_path($currentFilePath));
            // }
            return asset('home_images/' . $filename);

        }
    
        return $currentFilePath;
    }
    
    

    public function homecontent(Request $request){
        try {
            $homecontent = HomeContent::first();

            return response()->json([
                'success' => true,
                'data' => $homecontent
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}
