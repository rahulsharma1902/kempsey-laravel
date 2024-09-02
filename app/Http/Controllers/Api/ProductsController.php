<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    //
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'selected_filters_options' => 'nullable|json',
            'description' => 'nullable|string',
            'details' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'thumbnail_index' => 'nullable|integer',
        ]);

        // Handle image uploads
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $image) {
                $filename = 'image_'.$key . time() . '.' . $image->extension();
                $image->move(public_path('products'), $filename);
                $imageUrl = url('products/' . $filename);
                $images[] = $imageUrl;
            }
        }

        // Create the product
        $product = new Product();
        $product->name = $validated['name'];
        $product->slug = $validated['slug'];
        $product->category_id = $validated['category_id'];
        $product->brand_id = $validated['brand_id'] ?? null;
        $product->selected_filters_options = json_encode($validated['selected_filters_options'] ?? []);
        $product->description = $validated['description'] ?? null;
        $product->details = $validated['details'] ?? null;
        $product->price = $validated['price'];
        $product->stock = $validated['stock'];
        $product->weight = $validated['weight'] ?? null;
        $product->images = json_encode($images);
        $product->thumbnail_index = $validated['thumbnail_index'] ?? 0;
        $product->save();

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ]);
    }





    public function update(Request $request)
{
    // $existingImages = json_decode($request->existingImages, true) ?? [];

    // return response()->json($existingImages);
    $id = $request->id;

    // Validate the request data
    $validated = $request->validate([
        'name' => 'required|unique:products,name,' . $request->id,
        'slug' => 'required|unique:products,slug,' . $request->id,
        'category_id' => 'required|exists:categories,id',
        'brand_id' => 'nullable|exists:brands,id',
        'selected_filters_options' => 'nullable|json',
        'description' => 'nullable|string',
        'details' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'weight' => 'nullable|numeric|min:0',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'thumbnail_index' => 'nullable|string',
    ]);

    // Find the product
    $product = Product::findOrFail($id);

    $existingImages = json_decode($request->existingImages, true) ?? [];

    // $productImages = json_decode($product->images, true) ?? [];

    $newImages = [];

    // Handle image uploads
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $key => $image) {
            $filename = 'image_' . $key . '_' . time() . '.' . $image->extension();
            $image->move(public_path('products'), $filename);
            $imageUrl = url('products/' . $filename);
            $newImages[] = $imageUrl;
        }
    }

    // Combine existing and new images
    $allImages = array_merge($existingImages, $newImages);

    // Calculate the new thumbnail index
    $thumbnailIndex = 0;
    if (!empty($request->thumbnail_index)) {
        $thumbnailData = json_decode($request->thumbnail_index, true);
        if ($thumbnailData['type'] === 'existing') {
            $thumbnailIndex = $thumbnailData['index'];
        } else {
            $thumbnailIndex = count($existingImages) + $thumbnailData['index'];
        }
    }

    // Update the product
    $product->name = $validated['name'];
    $product->slug = $validated['slug'];
    $product->category_id = $validated['category_id'];
    $product->brand_id = $validated['brand_id'] ?? null;
    $product->selected_filters_options = json_encode($validated['selected_filters_options'] ?? []);
    $product->description = $validated['description'] ?? null;
    $product->details = $validated['details'] ?? null;
    $product->price = $validated['price'];
    $product->stock = $validated['stock'];
    $product->weight = $validated['weight'] ?? null;
    $product->images = json_encode($allImages);
    $product->thumbnail_index = $thumbnailIndex;
    $product->save();

    return response()->json([
        'message' => 'Product updated successfully',
        'product' => $product
    ]);
}





    public function products(Request $request){
        try {
            $products = Product::with('Categorie','Brand')->get();

            return response()->json([
                'success' => true,
                'data' => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }


    public function getProductById(Request $request, $id){
        try {
            $product = Product::where('id',$id)->first();

            return response()->json([
                'success' => true,
                'data' => $product
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        } 
    }




    public function removeProduct(Request $request, $id)
    {
        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'product ID is required.'
            ], 400);
        }
    
        $product = Product::find($id);
    
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'product not found.'
            ], 404);
        }
    
        try {
            $product->delete();
            return response()->json([
                'success' => true,
                'data' => 'product successfully removed.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while removing the product.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}
