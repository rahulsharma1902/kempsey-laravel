<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteContent;
use App\Models\ContactUsContent;
use App\Models\WorkshopContent;

class SiteContentController extends Controller
{
    public function sitecontent(Request $request){
        try {
            $sitecontent = SiteContent::first();

            return response()->json([
                'success' => true,
                'data' => $sitecontent
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }


    public function store(Request $request) 
    {
        $validated = $request->validate([
            'header_offer_text' => 'required',
            'footer_instagram_name' => 'required',
            'footer_contact_title' => 'required',
            // 'footer_contact_banner' => 'required',
            'footer_facebook_link' => 'required',
            'footer_instagram_link' => 'required',
            'footer_twitter_link' => 'required',
            'footer_description' => 'required',

            'address' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'footer_policy' => 'required',
        ]);

        $site_content = SiteContent::firstOrNew();
        $site_content->header_offer_text = $request->header_offer_text;

        $site_content->footer_instagram_name = $request->footer_instagram_name;
        $site_content->footer_contact_title = $request->footer_contact_title;
        // $site_content->footer_contact_banner = $request->footer_contact_banner;
        $site_content->footer_facebook_link = $request->footer_facebook_link;
        $site_content->footer_instagram_link = $request->footer_instagram_link;
        $site_content->footer_twitter_link = $request->footer_twitter_link;
        $site_content->footer_description = $request->footer_description;
        $site_content->address = $request->address;
        $site_content->phone = $request->phone;
        $site_content->email = $request->email;
        $site_content->footer_policy = $request->footer_policy;
        
        if (isset($request->footer_contact_banner)) {
            if ($this->isUrl($request->footer_contact_banner)) {
                $site_content->footer_contact_banner = $request->footer_contact_banner;
            } else {
                if ($request->hasFile("footer_contact_banner")) {
                    if ($request->hasFile("footer_contact_banner")) {
                        $file = $request->file("footer_contact_banner");
                        $filename = 'footer_bimg' . '_' . time() . '.' . $file->getClientOriginalExtension();
                        $file->move(public_path('site_images'), $filename);
                        $site_content->footer_contact_banner = asset('site_images/' . $filename);
                    }
                
                } elseif (isset($request->footer_contact_banner) && is_string($request->footer_contact_banner)) {
                    $site_content->footer_contact_banner = $request->footer_contact_banner;
                }
            }
        } 


        if (isset($request->footer_slider_image)) {
            if ($this->isUrl($request->footer_slider_image)) {
                $site_content->footer_slider_image = $request->footer_slider_image;
            } else {
                if ($request->hasFile("footer_slider_image")) {
                    if ($request->hasFile("footer_slider_image")) {
                        $file = $request->file("footer_slider_image");
                        $filename = 'footer_simg' . '_' . time() . '.' . $file->getClientOriginalExtension();
                        $file->move(public_path('site_images'), $filename);
                        $site_content->footer_slider_image = asset('site_images/' . $filename);
                    }
                
                } elseif (isset($request->footer_slider_image) && is_string($request->footer_slider_image)) {
                    $site_content->footer_slider_image = $request->footer_slider_image;
                }
            }
        } 


        $footerInstagramImages = [];
        
        foreach ($request->input('footer_instagram_images',[]) as  $index => $details) {
            if (isset($details) && is_string($details)) {
                $footerInstagramImages[] = $details; 
            }
        }
        
        foreach ($request->file('footer_instagram_images',[]) as  $index => $details) {
            
            if ($request->hasFile("footer_instagram_images.$index")) {
                $file = $request->file("footer_instagram_images.$index");
                $filename = 'footer_img' . '_' . rand().time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('site_images'), $filename);
                $footerInstagramImages[] = asset('site_images/' . $filename); 
            } 
        }


        $site_content->footer_instagram_images = json_encode($footerInstagramImages);
        
        // return  json_encode($footerInstagramImages);

        $site_content->save();

        return response()->json([
            'message' => 'about content changed successfully!',
            'data' => $site_content
        ]);
    }

    private function isUrl($string) {
        return filter_var($string, FILTER_VALIDATE_URL) !== false;
    }

    public function ContactUsContent(Request $request){
        try {
            $contactuscontent = ContactUsContent::first();

            return response()->json([
                'success' => true,
                'data' => $contactuscontent
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeContactData(Request $request) {

        $validated = $request->validate([
            'sub_heading' => 'required',
            'heading' => 'required',
            'content_heading' => 'required',
            'content_sub_heading' => 'required',
        ]);

        // return $request->all();
    
        $contactuscontent = ContactUsContent::firstOrNew();
    
        $contactuscontent->sub_heading = $request->sub_heading;
        $contactuscontent->heading = $request->heading;
        $contactuscontent->content_heading = $request->content_heading;
        $contactuscontent->content_sub_heading = $request->content_sub_heading;
        $contactuscontent->banner_image_url = $this->upload($request, 'banner_image_url', $contactuscontent->banner_image_url);
       
        $contactuscontent->save();
        return response()->json([
            'message' => 'Contact us content changed successfully!',
            'data' => $contactuscontent
        ]);
    }
    
    public function WorkshopContent(Request $request){
        try {
            $workshopcontent = WorkshopContent::first();

            return response()->json([
                'success' => true,
                'data' => $workshopcontent
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeWorkshopData(Request $request) {

        $validated = $request->validate([
            'sub_heading' => 'required',
            'heading' => 'required',
            'content_heading' => 'required',
            'content_text' => 'required',
            'content_title' => 'required',
            'button_text' => 'required',
            'description' => 'required',
        ]);

        $workshopcontent = WorkshopContent::firstOrNew();
    
        $workshopcontent->sub_heading = $request->sub_heading;
        $workshopcontent->heading = $request->heading;
        $workshopcontent->content_heading = $request->content_heading;
        $workshopcontent->content_title = $request->content_title;
        $workshopcontent->content_text = $request->content_text;
        $workshopcontent->button_text = $request->button_text;
        $workshopcontent->description = $request->description;
        $workshopcontent->banner_image_url = $this->upload($request, 'banner_image_url', $workshopcontent->banner_image_url);
       
        $workshopcontent->save();
        return response()->json([
            'message' => 'workshop content changed successfully!',
            'data' => $workshopcontent
        ]);
    }
    
    public function upload(Request $request, $fieldName, $currentFilePath = null) {
        if ($request->hasFile($fieldName)) {
            $file = $request->file($fieldName);
            $filename = $fieldName . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('site_images'), $filename);

            return asset('site_images/' . $filename);
        }
        return $currentFilePath;
    }
}
