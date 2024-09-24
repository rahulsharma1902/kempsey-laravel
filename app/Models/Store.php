<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'postal_code',
        'city',
        'address',
        'state',
        'country',
        'phone',
        'email',
        'latitude',
        'longitude',
        'details',
        'image',
    ];

    // Define the relationship with StoreServices
    public function storeServices()
    {
        return $this->hasMany(StoreServices::class, 'store_id');
    }
    public function services()
    {
        return $this->hasMany(StoreServices::class)
            ->with('serviceType')
            ->with('service')
            ->select('service_id', 'store_id')
            ->groupBy('service_id', 'store_id');
    }
}
