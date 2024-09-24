<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Store;
use App\Models\ServiceType;
use App\Mail\StoreMail;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'bikeDetail' => 'nullable|string|max:255',
            'bikeDetails.bikeBrand' => 'nullable|string|max:255',
            'bikeDetails.bikeModel' => 'nullable|string|max:255',
            'bikeDetails.bikeType' => 'nullable|string|max:255',
            'bikeDetails.bikeColor' => 'nullable|string|max:255',
            'serviceDate' => 'nullable|date',
            'services' => 'nullable|array',
            'servicesIds' => 'nullable|array',
            'storeId' => 'nullable|integer',
            'types' => 'nullable|array',
            'userDetails.fname' => 'nullable|string|max:255',
            'userDetails.lname' => 'nullable|string|max:255',
            'userDetails.email' => 'nullable|string|max:255',
            'userDetails.phone' => 'nullable|string|max:255',
            'userDetails.hearAboutUs' => 'nullable|string|max:255',
        ]);
    
        // Generate a booking number
        $bookingNumber = 'BK-' . strtoupper(uniqid());
    
        // Calculate total price
        $totalPrice = 0;
        $types = $request->input('types', []);
        if (is_array($types)) {
            foreach ($types as $type) {
                $type = ServiceType::find($type);
                if (isset($type['club_price']) && is_numeric($type['club_price'])) {
                    $totalPrice += floatval($type['club_price']);
                }
            }
        }
    
        // Create a new booking
        $booking = new Booking;
        $booking->bike_detail = $request->input('bikeDetail');
        $booking->bike_brand = $request->input('bikeDetails.bikeBrand');
        $booking->bike_model = $request->input('bikeDetails.bikeModel');
        $booking->bike_type = $request->input('bikeDetails.bikeType');
        $booking->bike_color = $request->input('bikeDetails.bikeColor');
        $booking->service_date = $request->input('serviceDate');
        $booking->services = json_encode($request->input('services', []));
        $booking->service_ids = json_encode($request->servicesIds);
        $booking->store_id = $request->input('storeId');
        $booking->types = json_encode($types);
        $booking->user_fname = $request->input('userDetails.fname');
        $booking->user_lname = $request->input('userDetails.lname');
        $booking->user_email = $request->input('userDetails.email');
        $booking->user_phone = $request->input('userDetails.phone');
        $booking->hear_about_us = $request->input('userDetails.hearAboutUs');
        $booking->booking_number = $bookingNumber;
        $booking->service_price = $totalPrice; 
        $booking->save();

        $mailData = $booking;

        $store = Store::find($booking->store_id);
        if($store){
            Mail::to($store->email)->send(new StoreMail($mailData));
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Your service booking has been confirmed. Please check your email for a copy of your order details.',
            'data' => $booking
        ], 200);
    }
    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'bikeDetail' => 'nullable|string|max:255',
    //         'bikeDetails.bikeBrand' => 'nullable|string|max:255',
    //         'bikeDetails.bikeModel' => 'nullable|string|max:255',
    //         'bikeDetails.bikeType' => 'nullable|string|max:255',
    //         'bikeDetails.bikeColor' => 'nullable|string|max:255',
    //         'serviceDate' => 'nullable|date',
    //         'services' => 'nullable|array',
    //         'servicesIds' => 'nullable|array',
    //         'storeId' => 'nullable|integer',
    //         'types' => 'nullable|array',
    //         'userDetails.fname' => 'nullable|string|max:255',
    //         'userDetails.lname' => 'nullable|string|max:255',
    //         'userDetails.email' => 'nullable|email|max:255',
    //         'userDetails.phone' => 'nullable|string|max:255',
    //         'userDetails.hearAboutUs' => 'nullable|string|max:255',
    //     ]);
    
    //     $bookingNumber = 'BK-' . strtoupper(uniqid());
    
    //     $booking = new Booking;
    //     $booking->bike_detail = $request->input('bikeDetail');
    //     $booking->bike_brand = $request->input('bikeDetails.bikeBrand');
    //     $booking->bike_model = $request->input('bikeDetails.bikeModel');
    //     $booking->bike_type = $request->input('bikeDetails.bikeType');
    //     $booking->bike_color = $request->input('bikeDetails.bikeColor');
    //     $booking->service_date = $request->serviceDate;
    //     $booking->services = json_encode($request->services);
    //     $booking->service_ids = json_encode($request->servicesIds);
    //     $booking->store_id = $request->storeId;
    //     $booking->types = json_encode($request->types);
    //     $booking->user_fname = $request->input('userDetails.fname');
    //     $booking->user_lname = $request->input('userDetails.lname');
    //     $booking->user_email = $request->input('userDetails.email');
    //     $booking->user_phone = $request->input('userDetails.phone');
    //     $booking->hear_about_us = $request->input('userDetails.hearAboutUs');
    //     $booking->booking_number = $bookingNumber;
    //     $booking->save();
    
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Your service booking has been confirmed. Please check your email for a copy of your order details.',
    //         'data' => $booking
    //     ], 200);
    // }
public function getBookings(Request $request)
{
    // $bookings = Booking::with('store')->get();

    // foreach ($bookings as $booking) {
    //     $booking->related_services_with_types = $booking->related_services_with_types; 
    // }

    // echo '<pre>';
    // print_r($bookings->toArray());
    // die();
    try {
        $bookings = Booking::with('store')->get();
        foreach ($bookings as $booking) {
            $booking->related_services_with_types = $booking->related_services_with_types; 
        }
        return response()->json([
            'success' => true,
            'data' => $bookings
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred: ' . $e->getMessage()
        ], 500);
    } 
}


    // public function getBookings(Request $request){
    //     $bookings = Booking::with('store','services')->get()->toArray();
    //     echo '<pre>';
    //     print_r($bookings);
    //     die();

    //     try {
    //         $bookings = Booking::all();

    //         return response()->json([
    //             'success' => true,
    //             'data' => $bookings
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'An error occurred: ' . $e->getMessage()
    //         ], 500);
    //     }  
    // }

    public function BookingStatus(Request $request,$id)
    {
        try {
            $booking = Booking::find($id);
            if($booking){
                $booking->update(['status' => true]);

                return response()->json([
                    'success' => true,
                    'data' => $booking
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred: ' 
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }  
    }

    public function RemoveBooking(Request $request,$id)
    {
        try {
            $booking = Booking::find($id);
            if($booking){
                $booking->delete();

                return response()->json([
                    'success' => true,
                    'data' => $booking
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred: ' 
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }  
    }
}
