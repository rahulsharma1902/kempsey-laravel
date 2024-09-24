<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    //
    public function orders(Request $request){
        $orders = Order::with('user','orderMeta','orderMeta.product','shippingAddress','billingAddress','payment')->get();
        return response()->json([
            'success' => true,
            'data' => $orders
        ], 200);
    }
}
