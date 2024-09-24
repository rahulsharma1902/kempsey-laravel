<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\StoreServices;
use App\Models\StoreServiceType;

class StoreController extends Controller
{
    public function store(Request $request)
    {
        // Define validation rules
        // return response()->json($request->all());
        $rules = [
            'name' => 'required|unique:stores,name,' . ($request->id ? $request->id : 'NULL'),
            'slug' => 'required|unique:stores,slug,' . ($request->id ? $request->id : 'NULL'),
            'postal_code' => 'required',
            'city' => 'required',
            'address' => 'required',
            'state' => 'required',
            'country' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'details' => 'required',
            'image' => 'required', // Validation for image file
            'service_ids' => 'nullable|json',
            'service_types' => 'nullable|json',
        ];

        // Validate request data
        $validatedData = $request->validate($rules);

        try {
            // Find or create a new store
            $store = $request->id ? Store::findOrFail($request->id) : new Store();
            $store->fill($validatedData);

            // Handle image upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = 'store_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('store_images'), $filename);
                $store->image = asset('store_images/' . $filename);
            }

            // Save the store
            $store->save();

            // Handle services and service types
            if ($request->has('service_ids') && $request->has('service_types')) {
                $serviceIds = json_decode($request->service_ids, true);
                $serviceTypes = json_decode($request->service_types, true);

                // Delete existing services for the store
                StoreServices::where('store_id', $store->id)->delete();

                // Insert new services
                foreach ($serviceIds as $serviceId) {
                    // Create the store service
                    $storeService = StoreServices::create([
                        'store_id' => $store->id,
                        'service_id' => $serviceId,
                    ]);

                    // Check if serviceId exists in serviceTypes
                    if (isset($serviceTypes[$serviceId])) {
                        foreach ($serviceTypes[$serviceId] as $serviceTypeId) {
                            StoreServiceType::create([
                                'store_service_id' => $storeService->id,
                                'service_id' => $serviceId,
                                'service_type_id' => $serviceTypeId,
                            ]);
                        }
                    }
                }
            }

            // Return success response
            return response()->json([
                'success' => true,
                'message' => $request->id ? 'Successfully updated store' : 'Successfully added store',
                'data' => $store
            ], 200);
        } catch (\Exception $e) {
            // Return error response
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStores()
    {
        // $stores = Store::with('storeServices.service','storeServices.serviceType.serviceTypeData')->get()->toArray();
        // echo '<pre>';
        // print_r($stores);
        // die();
        try {
            $stores = Store::with('storeServices.service','storeServices.serviceType.serviceTypeData')->get();
    
            return response()->json([
                'success' => true,
                'data' => $stores
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function getStoreById(Request $request,$id){
        try {
            $store = Store::where('id',$id)->with('storeServices.service','storeServices.serviceType.serviceTypeData')->first();

            return response()->json([
                'success' => true,
                'data' => $store
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
    

    
}
