<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Auth;

class CartController extends Controller
{

    public function generateTempId(Request $request)
    {
        if (!$request->session()->has('user_temp_id')) {
            $tempId = 'temp_'.(string) Str::uuid();
            $request->session()->put('user_temp_id', $tempId);
        }

        return response()->json([
            'temp_id' => $request->session()->get('user_temp_id')
        ]);
    }
    public function getCart(Request $request)
    { 
        try {
            $user = auth('sanctum')->user();
            if ($user) {
                $cart = Cart::where('user_id', $user->id)
                            ->with('product')
                            ->get();
            } else {
                $tempId = $request->query('temp_id');
                // return $tempId;
                if ($tempId) {
                    $cart = Cart::where('temp_id', $tempId)
                                ->with('product')
                                ->get();
                } else {
                    $cart = []; 
                }
            }

            return response()->json([
                'success' => true,
                'data' => $cart
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function CartCount(Request $request){
        return response()->json($request->all());
        try {
            $user = auth('sanctum')->user();
            if ($user) {
                $cart = Cart::where('user_id', $user->id)
                            ->with('product')
                            ->count();
            } else {
                $tempId = $request->query('temp_id'); 
                if ($tempId) {
                    $cart = Cart::where('temp_id', $tempId)
                                ->with('product')
                                ->count();
                } else {
                    $cart = 0; // or return a default value
                }
            }
    
            return response()->json([
                'success' => true,
                'data' => $cart
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
    public function AddCart(Request $request)
    {
        // $user = auth('sanctum')->user();
        // return response()->json($user->id);
        $validatedData = $request->validate([
            'product_id' => 'required',
            'quantity' => 'required'
        ]);
        // return $request->user();
        try {
            $user = auth('sanctum')->user();
            if ($user) {
                $cart = Cart::where('user_id',$user->id ?? '')->where('product_id',$request->product_id)->first();
                if(!$cart){
                    $cart = new Cart();
                }

                $cart->user_id = $user->id;

            } else {
                $tempId = $request->tempId;

                if (!$tempId) {
                    // return response()->json([
                    //     'success' => false,
                    //     'message' => 'Something went wrong '
                    // ], 500);
                }

                $cart = Cart::where('temp_id',$tempId)->where('product_id',$request->product_id)->first();

                if(!$cart){
                    $cart = new Cart();
                }
                $cart->temp_id = $tempId;
            }
            $cart->product_id = $request->product_id;
            if($request->updateQty){
                $cart->quantity = $request->quantity;
            }else{
            $cart->quantity += $request->quantity;
            }
            $cart->save();

            return response()->json([
                'success' => true,
                'message' => 'Product has been successfully added to the cart',
                'data' => $cart
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function mergeGuestCartToUser(Request $request)
    {
        if (Auth::check()) {
            $tempId = $request->cookie('tempId'); // Assuming tempId is stored in cookies
    
            if ($tempId) {
                // Fetch guest cart items
                $guestCartItems = Cart::where('temp_id', $tempId)->get();
    
                foreach ($guestCartItems as $item) {
                    // Check if item already exists in user's cart
                    $existingCartItem = Cart::where('user_id', Auth::id())
                                            ->where('product_id', $item->product_id)
                                            ->first();
    
                    if ($existingCartItem) {
                        // Merge quantities
                        $existingCartItem->quantity += $item->quantity;
                        $existingCartItem->save();
                    } else {
                        // Assign guest cart item to authenticated user
                        $item->user_id = Auth::id();
                        $item->temp_id = null; // Clear temporary ID
                        $item->save();
                    }
                }
            }
        }
    }
    
    public function RemoveCart(Request $request,$id)
    {
        try {
            $cart = Cart::find($id);
            if($cart) {

                $cart->delete();

                return response()->json([
                    'success' => true,
                    'data' => $cart
                ], 200);
            }  else {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong!'
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
