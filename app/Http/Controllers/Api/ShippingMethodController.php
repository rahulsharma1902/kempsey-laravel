<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShippingMethod;

class ShippingMethodController extends Controller
{
    public function store(Request $request)
{
    // return response()->json($request->all());
    $rules = [
        'type' => 'required|string|max:255',
        'details' => 'required|string',
        'price' => 'required|numeric|min:0',
        // 'is_free_shipping_enabled' => 'boolean',
        'free_shipping_over' => 'nullable|numeric|min:0|required_if:is_free_shipping_enabled,true',
        // 'is_active' => 'boolean',
    ];

    $validatedData = $request->validate($rules);

    try {
        if ($request->id) {
            $shippingMethod = ShippingMethod::findOrFail($request->id);
        } else {
            $shippingMethod = new ShippingMethod();
        }

        $shippingMethod->type = $validatedData['type'];
        $shippingMethod->details = $validatedData['details'];
        $shippingMethod->price = $validatedData['price'];
        $shippingMethod->is_free_shipping_enabled = $request->is_free_shipping_enabled ?? false; 
        $shippingMethod->free_shipping_over = $validatedData['free_shipping_over'] ?? null;
        $shippingMethod->is_active = $request->is_active ?? true;

        $shippingMethod->save();

        return response()->json([
            'success' => true,
            'message' => $request->id ? 'Successfully updated shipping method' : 'Successfully added shipping method',
            'data' => $shippingMethod
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred: ' . $e->getMessage()
        ], 500);
    }
}

    public function getShippingMethods(Request $request){
        try {
            $shippingmethods = ShippingMethod::all();

            return response()->json([
                'success' => true,
                'data' => $shippingmethods
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getShippingMethod(Request $request){
        try {
            $shippingmethod = ShippingMethod::first();

            return response()->json([
                'success' => true,
                'data' => $shippingmethod
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getShippingMethodById(Request $request, $id){
        try {
            $shippingmethod = ShippingMethod::where('id',$id)->first();

            return response()->json([
                'success' => true,
                'data' => $shippingmethod
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        } 
    }

    public function removeShippingMethod(Request $request, $id)
    {
        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'shipping method ID is required.'
            ], 400);
        }
    
        $shippingmethod = ShippingMethod::find($id);
    
        if (!$shippingmethod) {
            return response()->json([
                'success' => false,
                'message' => 'shipping method not found.'
            ], 404);
        }
    
        try {
            $shippingmethod->delete();
            return response()->json([
                'success' => true,
                'data' => 'shipping method successfully removed.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while removing the shipping method.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
