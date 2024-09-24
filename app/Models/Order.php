<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function orderMeta()
    {
        return $this->hasMany(OrderMeta::class, 'order_id', 'id');
    }
    public function shippingAddress(){
        return $this->hasOne(ShippingAddress::class,'id','shipping_address_id');
    }
    public function billingAddress(){
        return $this->hasOne(BillingAddress::class,'id','billing_address_id');
    }
    public function payment(){
        return $this->hasOne(Payment::class,'order_id','id');
    }
}
