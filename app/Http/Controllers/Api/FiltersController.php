<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Filter;
use App\Models\FilterOption;
use DB;
use Illuminate\Validation\Rule;
class FiltersController extends Controller
{
    //
    // FilterController.php
public function store(Request $request)
{
    // Define validation rules
    $rules = [
        'name' => [
            'required',
            Rule::unique('filters', 'name')->where(function ($query) use ($request) {
                return $query->where('category_id', $request->category_id);
            })->ignore($request->id)
        ],
        'slug' => [
            'required',
            Rule::unique('filters', 'slug')->where(function ($query) use ($request) {
                return $query->where('category_id', $request->category_id);
            })->ignore($request->id)
        ],
        'category_id' => 'required|exists:categories,id',
        'options' => 'required|json', // Ensure options is valid JSON
    ];

    // Validate the incoming request data
    $validatedData = $request->validate($rules);

    // Decode JSON options into an array
    $options = json_decode($validatedData['options'], true);

    try {
        // Begin transaction to ensure data integrity
        DB::beginTransaction();

        // Check if the request has an ID, indicating an update operation
        if ($request->id) {
            // Find the filter by ID or fail
            $filter = Filter::findOrFail($request->id);
        } else {
            // Otherwise, create a new filter instance
            $filter = new Filter();
        }

        // Set the filter's attributes from the validated data
        $filter->name = $validatedData['name'];
        $filter->slug = $validatedData['slug'];
        $filter->category_id = $validatedData['category_id'];

        // Save the filter to the database
        $filter->save();

        // Process filter options
        FilterOption::where('filter_id', $filter->id)->delete(); // Remove existing options before saving new ones

        foreach ($options as $optionName) {
            $option = new FilterOption();
            $option->filter_id = $filter->id; // Assign the filter ID to the option
            $option->name = $optionName;
            $option->save();
        }

        // Commit transaction
        DB::commit();

        // Return a successful JSON response
        return response()->json([
            'success' => true,
            'message' => $request->id ? 'Successfully updated filter' : 'Successfully added filter',
            'data' => $filter
        ], 200);

    } catch (\Exception $e) {
        // Rollback transaction in case of error
        DB::rollBack();

        // Return an error JSON response
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
}
