<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerServiceContent;
use Illuminate\Http\Request;

class CustomerServiceContentController extends Controller
{
    public function CustomerServiceContent(Request $request){
        try {
            $customerservicecontent = CustomerServiceContent::first();

            return response()->json([
                'success' => true,
                'data' => $customerservicecontent
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request) {

        $validated = $request->validate([
            'sub_heading' => 'required',
            'heading' => 'required',
            'content' => 'required',
        ]);

        // return $request->all();
    
        $customerservicecontent = CustomerServiceContent::firstOrNew();
    
        $customerservicecontent->sub_heading = $request->sub_heading;
        $customerservicecontent->heading = $request->heading;
        $customerservicecontent->content = $request->content;
        $customerservicecontent->banner_image_url = $this->upload($request, 'banner_image_url', $customerservicecontent->banner_image_url);
       
        $customerservicecontent->save();
        return response()->json([
            'message' => 'Customer Service content changed successfully!',
            'data' => $customerservicecontent
        ]);
    }
    
    public function upload(Request $request, $fieldName, $currentFilePath = null) {
        if ($request->hasFile($fieldName)) {
            $file = $request->file($fieldName);
            $filename = $fieldName . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('customer_images'), $filename);

            return asset('customer_images/' . $filename);
        }
        return $currentFilePath;
    }
}
