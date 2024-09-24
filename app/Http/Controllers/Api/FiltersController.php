<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Filter;
use App\Models\Categorie;
use App\Models\FilterOption;
use DB;
use Illuminate\Validation\Rule;
class FiltersController extends Controller
{
    //
    // FilterController.php
    public function store(Request $request)
    {
        $rules = [
            'name' => [
                'required',
                Rule::unique('filters', 'name')->where(function ($query) use ($request) {
                    return $query->where('category_id', $request->category_id);
                })
            ],
            'slug' => [
                'required',
                Rule::unique('filters', 'slug')->where(function ($query) use ($request) {
                    return $query->where('category_id', $request->category_id);
                })
            ],
            'category_id' => 'required|exists:categories,id',
            'options' => 'required|json', // Ensure options is valid JSON
        ];
    
        $validatedData = $request->validate($rules);
    
        $options = json_decode($validatedData['options'], true);
    
        try {
            DB::beginTransaction();
    
            $filter = new Filter();
            $filter->name = $validatedData['name'];
            $filter->slug = $validatedData['slug'];
            $filter->category_id = $validatedData['category_id'];
            $filter->save();
    
            foreach ($options as $optionName) {
                $option = new FilterOption();
                $option->filter_id = $filter->id;
                $option->name = $optionName;
                $option->save();
            }
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Successfully added filter',
                'data' => $filter
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
    

public function update(Request $request)
{
    $id = $request->id;
    if($id){
        $rules = [
            'name' => [
                'required',
                Rule::unique('filters', 'name')->where(function ($query) use ($request) {
                    return $query->where('category_id', $request->category_id);
                })->ignore($id)
            ],
            'slug' => [
                'required',
                Rule::unique('filters', 'slug')->where(function ($query) use ($request) {
                    return $query->where('category_id', $request->category_id);
                })->ignore($id)
            ],
            'category_id' => 'required|exists:categories,id',
            'options' => 'required|json', // Ensure options is valid JSON
        ];

        $validatedData = $request->validate($rules);

        $options = json_decode($validatedData['options'], true);

        try {
            DB::beginTransaction();

            $filter = Filter::findOrFail($id);
            $filter->name = $validatedData['name'];
            $filter->slug = $validatedData['slug'];
            $filter->category_id = $validatedData['category_id'];
            $filter->save();

            // Update filter options
            $existingOptions = collect($options)->filter(function($option) {
                return isset($option['id']);
            });

            $newOptions = collect($options)->filter(function($option) {
                return !isset($option['id']);
            });

            $existingOptionIds = $existingOptions->pluck('id')->toArray();
            FilterOption::where('filter_id', $filter->id)
                ->whereNotIn('id', $existingOptionIds)
                ->delete();

            foreach ($existingOptions as $optionData) {
                $option = FilterOption::findOrFail($optionData['id']);
                $option->name = $optionData['name'];
                $option->save();
            }

            foreach ($newOptions as $optionData) {
                $option = new FilterOption();
                $option->filter_id = $filter->id;
                $option->name = $optionData['name'];
                $option->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Successfully updated filter',
                'data' => $filter
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }else{
        return response()->json([
            'success' => false,
            'message' => 'An error occurred: ' . $e->getMessage()
        ], 500);
    }
}



public function getFilters(Request $request){
    try {
        $filters = Filter::with('filterOptions','Categorie')->get();

        return response()->json([
            'success' => true,
            'data' => $filters
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred: ' . $e->getMessage()
        ], 500);
    }
}

    public function getFilterById(Request $request, $id){
        try {
            $filter = Filter::with('filterOptions','Categorie')->where('id',$id)->first();

            return response()->json([
                'success' => true,
                'data' => $filter
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        } 
    }


    public function removeFilter(Request $request, $id)
    {
        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'Filter ID is required.'
            ], 400);
        }
    
        $filter = Filter::find($id);
    
        if (!$filter) {
            return response()->json([
                'success' => false,
                'message' => 'Filter not found.'
            ], 404);
        }
    
        try {
            $filter->delete();
            return response()->json([
                'success' => true,
                'data' => 'Filter successfully removed.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while removing the filter.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getFilterByCategory(Request $request, $slug)
{
    try {
        // Find the category by slug
        $category = Categorie::where('slug', $slug)->first();

        // Check if the category exists
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        // Check if the category has a parent ID
        if (is_null($category->parent_id)) {
            // Parent ID is null, so this is a top-level category
            $filters = Filter::with('filterOptions', 'Categorie')
                ->where('category_id', $category->id) // Get filters associated with this category
                ->get();
        } else {
            // Parent ID is not null, so get filters for this category and its parent
            $filters = Filter::with('filterOptions', 'Categorie')
                ->where('category_id', $category->id)
                ->orWhere('category_id', $category->parent_id)
                ->get();
        }

        return response()->json([
            'success' => true,
            'data' => $filters
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred: ' . $e->getMessage()
        ], 500);
    }
}

}
