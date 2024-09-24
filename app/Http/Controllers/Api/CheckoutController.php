<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\{Stripe,SetupIntent,Customer,PaymentIntent,Charge,PaymentMethod};
use App\Models\ShippingAddress;
use App\Models\BillingAddress;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\OrderMeta;
use App\Models\User;
use App\Models\Payment;
use App\Models\ShippingMethod;
use App\Models\Order;
use Str;

class CheckoutController extends Controller
{
    public function __construct()
    {
        // Stripe::setApiKey(env('STRIPE_SECRET'));
        Stripe::setApiKey(env('STRIPE_SECRET'));
        

        // $this->paypalProvider = new PayPalClient;
        // $this->paypalProvider->setApiCredentials(config('paypal'));
        // $this->paypalProvider->getAccessToken();
       
    }

    // public function GetClientSecret()
    // {
    //     $setup_intent = SetupIntent::create();
    //     $client_secret = $setup_intent->client_secret;
    // }
    
    public function checkout(Request $request)
    {
        // return response()->json($request->all());
        $validatedData = $this->validateCheckoutRequest($request);
    
        $shippingAddress = null;
        $billingAddress = null;
    
        // if ($validatedData['billingAddressSameAsShipping'] === 'true') {
        //     $shippingAddress = $this->saveShippingAddress($validatedData);
        //     $billingAddress = $shippingAddress;
        // } else {

            $shippingAddress = $this->saveShippingAddress($validatedData);
            $billingAddress = $this->saveBillingAddress($validatedData);
        // }
    
        if (!$shippingAddress || !$billingAddress) {
            return response()->json(['error' => 'Failed to save addresses.'], 400);
        }
    
        $cartData = Cart::where('user_id', $request->user()->id)->where('is_ordered', 0)->get();
    
        if ($cartData->isNotEmpty()) {
            $order = $this->saveOrderData($validatedData, $cartData, $shippingAddress, $billingAddress);

            if($request->payment_method == 'stripe'){

                $paymentdata['stripe_payment_method'] = $request->payment_token;
                $user = User::where('id',$request->user()->id)->first();
                // return response()->json($user->stripe_customer_id);
                if($user->stripe_customer_id != null) {
                    
                    $stripe_customer = Customer::retrieve($user->stripe_customer_id, []);
                    if($stripe_customer->id){
                        $paymentdata['stripe_customer_id'] = $user->stripe_customer_id;
                    } else {
                        $stripe_customer_id = $this->CreateStripeCustomer($request);
                        // return response()->json($stripe_customer_id);
                        $user->update(['stripe_customer_id' => $stripe_customer_id]);
                        $paymentdata['stripe_customer_id'] = $stripe_customer_id;
                    }
                } else {
                    // return response()->json($validatedData);
                    $stripe_customer_id = $this->CreateStripeCustomer($validatedData,$billingAddress);
                    // return response()->json($stripe_customer_id);
                    $user->update(['stripe_customer_id' => $stripe_customer_id]);
                    $paymentdata['stripe_customer_id'] = $stripe_customer_id;
                }

                // return response()->json($stripe_customer_id);
                $paymentMethod = PaymentMethod::retrieve($request->payment_token);
                $paymentMethod->attach(['customer' => $paymentdata['stripe_customer_id']]);
                $paymentObj = $this->PaymentWithStripe($paymentdata,$order);
                // return response()->json($paymentObj);

                if($paymentObj != null) {
                    $payment_data = new Payment();
                    $payment_data->order_id = $order->id;
                    $payment_data->user_id = $request->user()->id;
                    $payment_data->payment_method = $request->payment_method;
                    $payment_data->amount = $paymentObj->amount;
                    $payment_data->transaction_id = $paymentObj->id;
                    $payment_data->currency = $paymentObj->currency;
                    $payment_data->payment_status = $paymentObj->status;
                

                    if($paymentObj->status == 'succeeded') {

                        // $payment_data->status = true;
                        $payment_data->save();

                        $order = Order::find($order->id);
                        $order->order_status = $paymentObj->status;
                        // $order->status = 1;
                        
                        $order->save();


                        //  reduce coupon if use
                        if (!empty($validatedData['code'])) {
                            $coupon = Coupon::where('code', $validatedData['code'])->first();
                        
                            if ($coupon && $coupon->usage_limit > 0) {
                                $coupon->usage_limit -= 1;                        
                                if ($coupon->usage_limit < 0) {
                                    $coupon->usage_limit = 0;
                                }
                        
                                $coupon->save();
                            }
                        }

                        // remove items from cart 
                        Cart::where('user_id', $user->id)->delete();

                        // $confirmation_email = $request->confirmation_email;
                        // $mail_data = $this->SendOrderMail($payment_data->id,$order->order_number,$confirmation_email);
                    
                        // return redirect('/order-received/'.$order->order_number)->with('success','payment success');
                        return response()->json([
                            'success' => true,
                            'data' => [
                                'order' => $order,
                            ],
                        ], 200);
                    } else {
                        // $payment_data->status = false;
                        $payment_data->save();
                        return response()->json([
                            'success' => false,
                            'message' => 'Something went wrong.'
                        ], 400);
                        // return redirect('checkout')->with('error' , 'Something went wrong.');
                    }
                } else {
                    // return redirect('checkout')->with('error' , 'Something went wrong.');
                }
                // return response()->json($paymentObj);
            }

        } else {
            return response()->json(['error' => 'Cart is empty'], 400);
        }
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong.'
        ], 400);
        // return response()->json([
        //     'shipping_address' => $shippingAddress,
        //     'billing_address' => $billingAddress,
        //     'order' => $order,
        //     'message'=>'Your Payment has been done',
        // ]);
    }
 

    public function PaymentWithStripe($data,$order){ // Paymwnt using stripe
        try{

            $paymentIntentObject = PaymentIntent::create([
                'amount' => (int) $order->total_price * 100,
                'currency' => 'USD',
                'customer' => $data['stripe_customer_id'],
                'payment_method_types' => ['card'],
                'payment_method' => $data['stripe_payment_method'],
                'metadata' => ['order_id' => $order->id],
                'capture_method' => 'automatic',
                'confirm' => true,
                'off_session' => true,
                'description' => 'Customized banners',
            ]);

            return $paymentIntentObject;

        } catch(Exception $e){
            // dd($e);
            return null; 
        }
        
    }
    
    public function CreateStripeCustomer($validatedData, $billingAddress) {
        if($billingAddress != null) {
            $address = $billingAddress->address;
            $zipcode =  $billingAddress->zip_code;
            $city =  $billingAddress->city;
            $state  =  $billingAddress->state ?? 'Punjab';
            $country =  $billingAddress->country ?? 'India';
        } else {
            $address = $validatedData['address'];
            $zipcode = $validatedData['Postalcode'];
            $city = $validatedData['city'];
            $state  = $validatedData['state'] ?? 'Punjab';  // Add state field if available
            $country = $validatedData['country'] ?? 'India';  // Add country field if available
        }
    
        // Create Stripe customer using the Stripe SDK
        $stripeCustomer = \Stripe\Customer::create([
            'name' => $validatedData['firstName'] . ' ' . $validatedData['lastName'],
            'email' => $validatedData['email'], 
            'address' => [
                'line1' => $address,
                'city' => $city,
                'postal_code' => $zipcode,
                'state' => $state,
                'country' => $country
            ],
            'payment_method' => $validatedData['payment_token'],  // Attach payment method
        ]);
    
        // Return Stripe customer ID
        return $stripeCustomer->id;
    }
    

    private function saveOrderData($validatedData, $cartData, $shippingAddress, $billingAddress)
    {
        $total = 0;
        $price = 0;
        foreach ($cartData as $item) {
            $total += floatval($item->product->price) * intval($item->quantity); 
            $price += floatval($item->product->price) * intval($item->quantity); 
        }
    
        $shippingPrice = 0;
        $shippingData = ShippingMethod::where('id', $validatedData['shipping_method'])->first();
    
        if ($shippingData) {
            $shippingPrice = floatval($shippingData->price); // Cast as float
    
            if ($shippingData->is_free_shipping_enabled === 1) {
                if ($shippingData->free_shipping_over !== null && $total > $shippingData->free_shipping_over && $shippingData->free_shipping_over != 0) {
                    $shippingPrice = 0; // Free shipping
                }
            } 
        }
    
        // Calculate discount
        $couponData = Coupon::where('code', $validatedData['code'])->first();
        $discount = 0;
    
        if ($couponData) {
            if ($couponData->discount_type == 'percentage') {
                $discount = ($price * floatval($couponData->discount_value)) / 100;
            } elseif ($couponData->discount_type == 'fixed') {
                $discount = floatval($couponData->discount_value);
            }
    
            // Check for maximum discount
            if ($couponData->maximum_discount && $discount > $couponData->maximum_discount) {
                $discount = floatval($couponData->maximum_discount);
            }
        }
    
        // Create a new order
        $order = new Order();
        $order->user_id = auth()->id();
        $order->order_number = strtoupper(Str::random(10));
        $order->confirmation_email = $validatedData['email'];
    
        // if ($validatedData['billingAddressSameAsShipping'] !== 'true') {
            $order->billing_address_id = $billingAddress->id ?? null;
        // }
    
        $order->shipping_address_id = $shippingAddress->id ?? null;
        $order->shipping_method = $shippingData->id ?? null;
        $order->coupon_id = $couponData->id ?? null;
        $order->payment_method = $validatedData['payment_method'];
        $order->currency = $validatedData['currency'] ?? 'USD';
        $order->billing_address_same_as_shipping = $validatedData['billingAddressSameAsShipping'] === 'true' ? 1 : 0;
    
        // Format total_price correctly
        $order->total_price = number_format((float) $total + (float) $shippingPrice - (float) $discount, 2, '.', ''); 
        $order->price = number_format($price, 2, '.', ''); // Format price
    
        $order->save();
    
        // Save each cart item into the order metas
      
        foreach ($cartData as $item) {
            $orderMeta = new OrderMeta();
            $orderMeta->product_id = $item->product_id;
            $orderMeta->order_id = $order->id;
            $orderMeta->qty = $item->quantity;
            $orderMeta->item_price = $item->product->price;
            $orderMeta->total_price = $total;
            $orderMeta->shipping_price = $shippingPrice ?? 0;
            $orderMeta->coupon_price = $validatedData['coupon_price'] ?? 0;
            $orderMeta->additional_price = $validatedData['additional_price'] ?? 0;
            $orderMeta->save();
        }
    
        return $order;
    }
    


    private function validateCheckoutRequest(Request $request)
    {
        return $request->validate([
            'email' => 'required|email',
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'phoneNumber' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string',
            'Postalcode' => 'required|string',
            'billingAddressSameAsShipping' => 'required',
            'payment_method' => 'required|string',
            'payment_token' => 'required|string',
            'code' => 'nullable|string|max:255',
            'bfirstName' => 'nullable|string|max:255',
            'blastName' => 'nullable|string|max:255',
            'bphoneNumber' => 'nullable|string|max:255',
            'bemail' => 'nullable|email',
            'baddress' => 'nullable|string',
            'bcity' => 'nullable|string',
            'bPostalcode' => 'nullable|string',
            'shipping_method' => 'nullable|string',
        ]);
    }

    private function saveShippingAddress(array $data)
    {
        return ShippingAddress::create([
            'user_id' => auth()->id(),
            'first_name' => $data['firstName'],
            'last_name' => $data['lastName'],
            'email' => $data['email'],
            'phone_number' => $data['phoneNumber'],
            'address' => $data['address'],
            'additional_address' => $data['additional_address'] ?? null,
            'city' => $data['city'],
            'zip_code' => $data['Postalcode'],
            'save_for_future' => $data['save_for_future'] ?? false,
        ]);
    }

    private function saveBillingAddress(array $data)
    {
        if($data['billingAddressSameAsShipping'] === 'true'){
            return BillingAddress::create([
                'user_id' => auth()->id(),
                'first_name' => $data['firstName'],
                'last_name' => $data['lastName'],
                'email' => $data['email'],
                'phone_number' => $data['phoneNumber'],
                'address' => $data['address'],
                'additional_address' => $data['additional_address'] ?? null,
                'city' => $data['city'],
                'zip_code' => $data['Postalcode'],
                'save_for_future' => $data['save_for_future'] ?? false,
            ]);
        }else{
            return BillingAddress::create([
                'user_id' => auth()->id(),
                'first_name' => $data['bfirstName'],
                'last_name' => $data['blastName'],
                'email' => $data['bemail'],
                'phone_number' => $data['bphoneNumber'],
                'address' => $data['baddress'],
                'additional_address' => $data['badditional_address'] ?? null,
                'city' => $data['bcity'],
                'zip_code' => $data['bPostalcode'],
                'save_for_future' => $data['save_for_future'] ?? false,
            ]);
        }
    }
}
