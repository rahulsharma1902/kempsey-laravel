<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;

class BrandsController extends Controller
{
    //
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|unique:brands,name' . ($request->id ? ',' . $request->id : ''),
            'slug' => 'required|unique:brands,slug' . ($request->id ? ',' . $request->id : ''),
        ];
    
        $validatedData = $request->validate($rules);
    
        try {
            if ($request->id) {
                $brand = Brand::findOrFail($request->id);
            } else {
                $brand = new Brand();
            }
    
            $brand->name = $validatedData['name'];
            $brand->slug = $validatedData['slug'];
    

       
    
            $brand->save();
    
            return response()->json([
                'success' => true,
                'message' => $request->id ? 'Successfully updated brand' : 'Successfully added brand',
                'data' => $brand
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getBrands(Request $request){
        try {
            $brands = Brand::all();

            return response()->json([
                'success' => true,
                'data' => $brands
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }


    public function getBrandById(Request $request, $id){
        try {
            $brand = Brand::where('id',$id)->first();

            return response()->json([
                'success' => true,
                'data' => $brand
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        } 
    }

    public function removeBrand(Request $request, $id)
    {
        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'Brand ID is required.'
            ], 400);
        }
    
        $brand = Brand::find($id);
    
        if (!$brand) {
            return response()->json([
                'success' => false,
                'message' => 'Brand not found.'
            ], 404);
        }
    
        try {
            $brand->delete();
            return response()->json([
                'success' => true,
                'data' => 'Brand successfully removed.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while removing the brand.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
