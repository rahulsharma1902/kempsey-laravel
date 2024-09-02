<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Carousel;
use Illuminate\Support\Facades\Log;

class CarouselController extends Controller
{
    //

    
    public function store(Request $request) {
        // Validate the incoming request
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'nullable|string',
            'items.*.heading' => 'required|string',
            'items.*.sub_heading' => 'nullable|string',
            'items.*.text' => 'nullable|string',
            'items.*.button_text' => 'nullable|string',
            'items.*.button_link' => 'nullable|string',
            'items.*.image' => 'nullable|file|mimes:jpg,jpeg,png', // Optional file
            'items.*.image_url' => 'nullable|string',
            'items.*.position' => 'required|integer',
        ]);
    
        $carouselItems = [];
    
        // Get all existing carousel items
        $existingItems = Carousel::all()->keyBy('id')->toArray();
        $requestItemIds = array_column($request->items, 'id');
    
        // Delete items that are not in the request
        foreach ($existingItems as $id => $item) {
            if (!in_array($id, $requestItemIds)) {
                Carousel::find($id)->delete();
            }
        }
    
        foreach ($request->items as $item) {
            // Find or create a new Carousel item
            $carouselItem = Carousel::find($item['id']) ?? new Carousel;
    
            $imageURL = '';
            if (isset($item['image']) && $item['image'] instanceof \Illuminate\Http\UploadedFile) {
                $filename = time() . '_' . $item['image']->getClientOriginalName();
                $item['image']->move(public_path('carousel_images'), $filename);
                $imageURL = url('carousel_images/' . $filename);
            } else {
                $imageURL = $item['image_url'] ?? $carouselItem->image;
            }
    
            // Update carousel item properties
            $carouselItem->heading = $item['heading'];
            $carouselItem->sub_heading = $item['sub_heading'] ?? null;
            $carouselItem->text = $item['text'] ?? null;
            $carouselItem->button_text = $item['button_text'] ?? null;
            $carouselItem->button_link = $item['button_link'] ?? null;
            $carouselItem->image = $imageURL;
            $carouselItem->position = $item['position'];
            $carouselItem->save();
    
            $carouselItems[] = $carouselItem;
        }
    
        return response()->json([
            'message' => 'Carousel items saved successfully!',
            'items' => $carouselItems,
        ]);
    }
    
    
    
    
    
    
    public function carousel(Request $request){
        try {
            $carousels = Carousel::all();

            return response()->json([
                'success' => true,
                'data' => $carousels
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}
