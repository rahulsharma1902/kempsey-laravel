<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\Cart;

class CouponController extends Controller
{
    public function store(Request $request)
    {
        // return response()->json($request->all());
        $rules = [
            'code' => 'required|string|unique:coupons,code' . ($request->id ? ',' . $request->id : ''),
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'minimum_order' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:0',
        ];

        $validatedData = $request->validate($rules);

        try {
            if ($request->id) {
                $coupon = Coupon::findOrFail($request->id);
            } else {
                $coupon = new Coupon();
            }

            $coupon->code = $validatedData['code'];
            $coupon->description = $validatedData['description'];
            $coupon->discount_type = $validatedData['discount_type'];
            $coupon->discount_value = $validatedData['discount_value'];
            $coupon->start_date = $validatedData['start_date'];
            $coupon->end_date = $validatedData['end_date'];
            $coupon->minimum_order = $validatedData['minimum_order'];
            $coupon->maximum_discount = $validatedData['maximum_discount'];
            $coupon->usage_limit = $validatedData['usage_limit'];

            $coupon->save();

            return response()->json([
                'success' => true,
                'message' => $request->id ? 'Successfully updated coupon' : 'Successfully added coupon',
                'data' => $coupon
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getCoupons(Request $request){
        try {
            $coupons = Coupon::all();

            return response()->json([
                'success' => true,
                'data' => $coupons
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }


    public function removeCoupon(Request $request, $id)
    {
        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'coupon ID is required.'
            ], 400);
        }
    
        $coupon = Coupon::find($id);
    
        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'coupon not found.'
            ], 404);
        }
    
        try {
            $coupon->delete();
            return response()->json([
                'success' => true,
                'data' => 'coupon successfully removed.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while removing the coupon.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function applyCoupon(Request $request){
        $rules = [
            'code' => 'required|string|exists:coupons,code',
        ];
    
        $validatedData = $request->validate($rules);
    
        try {
            $user = auth('sanctum')->user();
            if ($user) {
                $cart = Cart::where('user_id', $user->id)
                            ->with('product')
                            ->get();
            } else {
                $tempId = $request->tempId;
                // return response()->json($request->tempId);

                if ($tempId) {
                    $cart = Cart::where('temp_id', $tempId)
                                ->with('product')
                                ->get();
                } else {
                    $cart = []; 
                }
            }
    
            $coupon = Coupon::where('code', $validatedData['code'])->first();
    
            if (!$coupon) {
                return response()->json([
                    'success' => false,
                    'message' => 'Coupon not found.'
                ], 404);
            }
    
            // Check if coupon is valid
            if ($coupon->start_date > now() || $coupon->end_date < now()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Coupon is not valid.'
                ], 400);
            }
    
            // Calculate total price
            $total = 0;
            foreach ($cart as $item) {
                $total += floatval($item->product->price) * intval($item->quantity);
                
            }
            // return response()->json($cart);
            // return response()->json($total);
            if ($coupon->minimum_order > $total) {
                return response()->json([
                    'success' => false,
                    'message' => 'Minimum order amount is not met.'
                ], 400);
            }
    
            // Calculate discount
            $discount = 0;
            if ($coupon->discount_type == 'percentage') {
                $discount = ($total * $coupon->discount_value) / 100;
            } elseif ($coupon->discount_type == 'fixed') {
                $discount = $coupon->discount_value;
            }
    
            if ($coupon->maximum_discount && $discount > $coupon->maximum_discount) {
                $discount = $coupon->maximum_discount;
            }
    
            return response()->json([
                'success' => true,
                'data' => [
                    'cart' => $cart,
                    'coupon' => $coupon,
                    'discount' => $discount,
                    'total' => $total - $discount,
                ],
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}
