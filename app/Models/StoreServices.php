<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreServices extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'service_id',
        // 'service_type_id',
    ];

    // Define the relationship with Store
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    // Define the relationship with Service
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    // Define the relationship with ServiceType
    // public function serviceType()
    // {
    //     return $this->belongsTo(StoreServiceType::class, 'store_service_id');
    // }
    public function serviceType()
    {
        return $this->hasMany(StoreServiceType::class, 'store_service_id');
    }
}
