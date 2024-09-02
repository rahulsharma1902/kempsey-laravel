<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AboutUsContent;

class AboutUsController extends Controller
{

    public function store(Request $request) 
    {
        $validated = $request->validate([
            'about_us_banner_title' => 'required',
            // 'about_us_banner_image' => 'required',
            'about_us_banner_sub_title' => 'required',
            'about_us_heading' => 'required',
            'about_us_details' => 'required',
            'about_us_btn' => 'required',
            'about_us_btn_link' => 'required',
            'about_us_shop_title' => 'required',
            'about_us_bottom_title' => 'required',

            // 'about_section_image' => 'required',
            'about_section_btn' => 'required',
            'about_section_btn_link' => 'required',
        ]);

        $about_content = AboutUsContent::firstOrNew();
        $about_content->about_us_banner_title = $request->about_us_banner_title;

        if (isset($request->about_us_banner_image)) {
            if ($this->isUrl($request->about_us_banner_image)) {
                $about_content->about_us_banner_image = $request->about_us_banner_image;
            } else {
                if ($request->hasFile("about_us_banner_image")) {
                    if ($request->hasFile("about_us_banner_image")) {
                        $file = $request->file("about_us_banner_image");
                        $filename = 'banner_image' . '_' . time() . '.' . $file->getClientOriginalExtension();
                        $file->move(public_path('about_images'), $filename);
                        $about_content->about_us_banner_image = asset('about_images/' . $filename);
                    }
                
                } elseif (isset($request->about_us_banner_image) && is_string($request->about_us_banner_image)) {
                    $about_content->about_us_banner_image = $request->about_us_banner_image;
                }
            }
        } 
        $about_content->about_us_banner_sub_title = $request->about_us_banner_sub_title;
        $about_content->about_us_heading = $request->about_us_heading;
        $about_content->about_us_details = $request->about_us_details;
        $about_content->about_us_btn = $request->about_us_btn;
        $about_content->about_us_btn_link = $request->about_us_btn_link;
        $about_content->about_us_shop_title = $request->about_us_shop_title;
        $about_content->about_us_bottom_title = $request->about_us_bottom_title;
        
        if (isset($request->about_us_image)) {
            if ($this->isUrl($request->about_us_image)) {
                $about_content->about_us_image = $request->about_us_image;
            } else {
                if ($request->hasFile("about_us_image")) {
                    if ($request->hasFile("about_us_image")) {
                        $file = $request->file("about_us_image");
                        $filename = 'image' . '_' . time() . '.' . $file->getClientOriginalExtension();
                        $file->move(public_path('about_images'), $filename);
                        $about_content->about_us_image = asset('about_images/' . $filename);
                    }
                
                } elseif (isset($request->about_us_image) && is_string($request->about_us_image)) {
                    $about_content->about_us_image = $request->about_us_image;
                }
            }
        } 

        if (isset($request->about_us_logo)) {
            if ($this->isUrl($request->about_us_logo)) {
                $about_content->about_us_logo = $request->about_us_logo;
            } else {
                if ($request->hasFile("about_us_logo")) {
                    if ($request->hasFile("about_us_logo")) {
                        $file = $request->file("about_us_logo");
                        $filename = 'about_logo' . '_' . time() . '.' . $file->getClientOriginalExtension();
                        $file->move(public_path('about_images'), $filename);
                        $about_content->about_us_banner_image = asset('about_images/' . $filename);
                    }
                
                } elseif (isset($request->about_us_logo) && is_string($request->about_us_logo)) {
                    $about_content->about_us_logo = $request->about_us_logo;
                }
            }
        } 

        if (isset($request->about_us_bottom_banner)) {
            if ($this->isUrl($request->about_us_bottom_banner)) {
                $about_content->about_us_bottom_banner = $request->about_us_bottom_banner;
            } else {
                if ($request->hasFile("about_us_bottom_banner")) {
                    if ($request->hasFile("about_us_bottom_banner")) {
                        $file = $request->file("about_us_bottom_banner");
                        $filename = 'about_logo' . '_' . time() . '.' . $file->getClientOriginalExtension();
                        $file->move(public_path('about_images'), $filename);
                        $about_content->about_us_banner_image = asset('about_images/' . $filename);
                    }
                
                } elseif (isset($request->about_us_bottom_banner) && is_string($request->about_us_bottom_banner)) {
                    $about_content->about_us_bottom_banner = $request->about_us_bottom_banner;
                }
            }
        } 

        $aboutUsShopDetails = [];
        foreach ($request->input('about_us_shop_details', []) as $index => $details) {
            $aboutUsShopDetails[$index] = [
                'title' => $details['title'] ?? '',
                'text' => $details['text'] ?? '',
                'image' => null
            ];

            if ($request->hasFile("about_us_shop_details.$index.image")) {
                $file = $request->file("about_us_shop_details.$index.image");
                $filename = 'shop_img' . '_' . time().rand() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('about_images'), $filename);
                $aboutUsShopDetails[$index]['image'] = asset('about_images/' . $filename); 
            } elseif (isset($details['image']) && is_string($details['image'])) {
                $aboutUsShopDetails[$index]['image'] = $details['image']; 
            }
        }
        $about_content->about_us_shop_details = json_encode($aboutUsShopDetails);

        $about_content->save();

        return response()->json([
            'message' => 'about content changed successfully!',
            'data' => $about_content
        ]);
    }

    function CheckFile(Request $request ,$file) {
        if (isset($details['image'])) {
            if ($this->isUrl($details['image'])) {
                return $file;
            } else {
                if ($request->hasFile("about_us_banner_image")) {
                    if ($request->hasFile("about_us_banner_image")) {
                        $file = $request->file("about_us_banner_image");
                        $filename = 'banner_image' . '_' . time() . '.' . $file->getClientOriginalExtension();
                        $file->move(public_path('about_images'), $filename);
                
                        return asset('about_images/' . $filename);
                    }
                
                } elseif (isset($request->about_us_banner_image) && is_string($request->about_us_banner_image)) {
                    $imagefile = $details['image']; 
                }
            }
        } 
    }



    private function isUrl($string) {
        return filter_var($string, FILTER_VALIDATE_URL) !== false;
    }

    public function upload(Request $request, $fieldName, $currentFilePath = null) {
        if ($request->hasFile($fieldName)) {
            $file = $request->file($fieldName);
            $filename = $fieldName . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('about_images'), $filename);
    
            return asset('about_images/' . $filename);
        }
    
        return $currentFilePath;
    }

    

    public function aboutuscontent(Request $request){
        try {
            $aboutuscontent = AboutUsContent::first();

            return response()->json([
                'success' => true,
                'data' => $aboutuscontent
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
    
}
