<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServiceType;
use App\Models\ServiceOption;

class ServiceController extends Controller
{
    //
    public function addService(Request $request){
        $rules = [
            'name' => 'required|unique:services,name' . ($request->id ? ',' . $request->id : ''),
            'slug' => 'required|unique:services,slug' . ($request->id ? ',' . $request->id : ''),
        ];
    
        $validatedData = $request->validate($rules);
    
        try {
            if ($request->id) {
                $service = Service::findOrFail($request->id);
            } else {
                $service = new Service();
            }
    
            $service->name = $validatedData['name'];
            $service->slug = $validatedData['slug'];
            $service->save();
    
            return response()->json([
                'success' => true,
                'message' => $request->id ? 'Successfully updated service' : 'Successfully added service',
                'data' => $service
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getServices(Request $request)
    {
        try {
            // Fetch services with serviceTypes and serviceOptions
            $services = Service::with(['serviceTypes', 'serviceOptions'])->get();
    
            // Enhance serviceTypes with serviceOptions
            $services->each(function ($service) {
                $service->serviceTypes->each(function ($serviceType) use ($service) {
                    // Manually convert the JSON string to an array
                    $serviceOptions = ServiceOption::where('service_id', $service->id)
                        ->get()
                        ->filter(function ($option) use ($serviceType) {
                            // Convert service_type_ids string to array
                            $serviceTypeIds = json_decode($option->service_type_ids, true);
                            // Check if the current serviceType id is in the array
                            return is_array($serviceTypeIds) && in_array($serviceType->id, $serviceTypeIds);
                        })->values()->toArray(); // Convert the collection to an array
    
                    // Assign filtered service options back to serviceType
                    $serviceType->serviceOptions = $serviceOptions;
                });
            });
    
            return response()->json([
                'success' => true,
                'data' => $services
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
    


    public function getServiceById(Request $request, $id){
        try {
            $service = Service::where('id',$id)->first();

            return response()->json([
                'success' => true,
                'data' => $service
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        } 
    }

    public function removeService(Request $request, $id)
    {
        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'service ID is required.'
            ], 400);
        }
    
        $service = Service::find($id);
    
        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'service not found.'
            ], 404);
        }
    
        try {
            $service->delete();
            return response()->json([
                'success' => true,
                'data' => 'service successfully removed.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while removing the service.',
                'error' => $e->getMessage()
            ], 500);
        }
    }




    // ServiceType
    public function addServiceType(Request $request){
        $rules = [
            'name' => 'required|unique:service_types,name' . ($request->id ? ',' . $request->id : ''),
            'slug' => 'required|unique:service_types,slug' . ($request->id ? ',' . $request->id : ''),
            'service_id' => 'required|exists:services,id', 
            'full_price' => 'required|numeric|min:0',
            'club_price' => 'required|numeric|min:0',
        ];
        
    
        $validatedData = $request->validate($rules);
    
        try {
            if ($request->id) {
                $serviceType = ServiceType::findOrFail($request->id);
            } else {
                $serviceType = new ServiceType();
            }
    
            $serviceType->name = $validatedData['name'];
            $serviceType->slug = $validatedData['slug'];
            $serviceType->service_id = $validatedData['service_id'];
            $serviceType->full_price = $validatedData['full_price'];
            $serviceType->club_price = $validatedData['club_price'];
            $serviceType->details = $request->details;

           
    
            $serviceType->save();
    
            return response()->json([
                'success' => true,
                'message' => $request->id ? 'Successfully updated serviceType' : 'Successfully added serviceType',
                'data' => $serviceType
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getServiceTypeById(Request $request,$id){
        try {
            $serviceType = ServiceType::where('id',$id)->first();

            return response()->json([
                'success' => true,
                'data' => $serviceType
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        } 
    }
    public function removeServiceType(Request $request, $id)
    {
        if (!$id) {
            return response()->json([ 'success' => false, 'message' => 'service ID is required.' ], 400);
        }
    
        $serviceType = ServiceType::find($id);
    
        if (!$serviceType) {
            return response()->json([ 'success' => false, 'message' => 'service type not found.'], 404);
        }
    
        try {
            $serviceType->delete();
            return response()->json(['success' => true, 'data' => 'service type successfully removed.' ], 200);
        } catch (\Exception $e) {
            return response()->json([ 'success' => false, 'message' => 'An error occurred while removing the service.', 'error' => $e->getMessage()], 500);
        }
    }
    public function getServiceTypeByServiceId(Request $request, $id){
        try {
            $serviceType = ServiceType::where('service_id',$id)->get();

            return response()->json([
                'success' => true,
                'data' => $serviceType
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }  
    }
    public function getServiceType(Request $request){
        try {
            $serviceType = ServiceType::all();

            return response()->json([
                'success' => true,
                'data' => $serviceType
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }  
    }
    public function getServiceTypesByArray(Request $request)
    {
        return response()->json(gettype($request->all()));
        $typeIds = $request->input('type_ids', []);
        try {
            $serviceTypes = ServiceType::whereIn('id', $typeIds)->get();

            return response()->json([
                'success' => true,
                'data' => $serviceTypes
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }  
    }


    // addServiceOption

    public function addServiceOption(Request $request)
    {
        $rules = [
            'name' => 'required|unique:service_options,name' . ($request->id ? ',' . $request->id : ''),
            'slug' => 'required|unique:service_options,slug' . ($request->id ? ',' . $request->id : ''),
            'service_id' => 'required|exists:services,id',
        ];

        $validatedData = $request->validate($rules);

        try {
            if ($request->id) {
                $ServiceOption = ServiceOption::findOrFail($request->id);
            } else {
                $ServiceOption = new ServiceOption();
            }

            $ServiceOption->name = $validatedData['name'];
            $ServiceOption->slug = $validatedData['slug'];
            $ServiceOption->service_id = $validatedData['service_id'];
            
            $serviceTypeIds = json_decode($request->service_type_ids, true);
            $ServiceOption->service_type_ids = json_encode($serviceTypeIds); // Encode the array as JSON

            $ServiceOption->save();

            return response()->json([
                'success' => true,
                'message' => $request->id ? 'Successfully updated ServiceOption' : 'Successfully added ServiceOption',
                'data' => $ServiceOption
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getServiceOptionById(Request $request,$id){
        try {
            $serviceOption = ServiceOption::where('id',$id)->first();

            return response()->json([
                'success' => true,
                'data' => $serviceOption
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        } 
    }
    public function removeServiceOption(Request $request, $id)
    {
        if (!$id) {
            return response()->json([ 'success' => false, 'message' => 'service ID is required.' ], 400);
        }
    
        $serviceOption = ServiceOption::find($id);
    
        if (!$serviceOption) {
            return response()->json([ 'success' => false, 'message' => 'service option not found.'], 404);
        }
    
        try {
            $serviceOption->delete();
            return response()->json(['success' => true, 'data' => 'service option successfully removed.' ], 200);
        } catch (\Exception $e) {
            return response()->json([ 'success' => false, 'message' => 'An error occurred while removing the service option.', 'error' => $e->getMessage()], 500);
        }
    }
}
