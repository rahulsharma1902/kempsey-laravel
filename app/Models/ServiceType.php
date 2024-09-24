<?php

// ServiceType.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    use HasFactory;

    // Define a relationship to fetch service options
    public function serviceOptions()
    {
        return $this->belongsToMany(ServiceOption::class, 'service_option_service_type', 'service_type_id', 'service_option_id');
    }
    public function storeServices()
    {
        return $this->hasMany(StoreServices::class, 'service_type_id');
    }
    
}
