<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'email', 'phone_number', 'address', 'additional_address', 'city', 'state', 'zip_code', 'country', 'additional_info', 'is_default', 'save_for_future'
    ];
}
