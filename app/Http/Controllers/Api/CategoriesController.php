<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categorie;

class CategoriesController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|unique:categories,name' . ($request->id ? ',' . $request->id : ''),
            'slug' => 'required|unique:categories,slug' . ($request->id ? ',' . $request->id : ''),
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg',
        ];
    
        $validatedData = $request->validate($rules);
    
        try {
            if ($request->id) {
                $category = Categorie::findOrFail($request->id);
            } else {
                $category = new Categorie();
            }
    
            $category->name = $validatedData['name'];
            $category->slug = $validatedData['slug'];
            $category->parent_id = $request->parent_id;
            $category->description = $request->description;
            if($request->visibility){
            $category->visibility = $request->visibility;
            }
    
            if ($request->hasFile('image')) {
                $img = $request->file('image');
                $filename = 'image_' . time() . '.' . $img->extension();
                $imgPath = $img->move(public_path('category_Images'), $filename);
                
                $imageUrl = url('category_Images/' . $filename);
                $category->image = $imageUrl;
            }
    
            $category->save();
    
            return response()->json([
                'success' => true,
                'message' => $request->id ? 'Successfully updated category' : 'Successfully added category',
                'data' => $category
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
    public function categories(Request $request)
    {
        try {
            $categories = Categorie::where('visibility', 'enabled')->with('filters','filters.filterOptions')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $categories
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
    

    public function activeParentCategories(Request $request)
    {
        try {
            $categories = Categorie::whereNull('parent_id')
                ->where('visibility', 'enabled')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $categories
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function parentCategories(Request $request){
        try {
            $categories = Categorie::whereNull('parent_id')
            ->with('children')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $categories
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }


    public function activeChildCategories(Request $request)
    {
        try {
            $categories = Categorie::whereNotNull('parent_id')
                ->where('visibility', 'enabled')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $categories
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }


    public function childCategories(Request $request){
        try {
            $categories = Categorie::whereNotNull('parent_id')
                ->with('parent')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $categories
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
    


    public function getCategoryById(Request $request, $id){
        try {
            $categories = Categorie::where('id',$id)
                ->first();

            return response()->json([
                'success' => true,
                'data' => $categories
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        } 
    }



    public function removeCategory(Request $request, $id)
    {
        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'Category ID is required.'
            ], 400);
        }
    
        $category = Categorie::find($id);
    
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found.'
            ], 404);
        }
    
        try {
            $category->delete();
            return response()->json([
                'success' => true,
                'data' => 'Category and its child categories were successfully removed.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while removing the category.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}
