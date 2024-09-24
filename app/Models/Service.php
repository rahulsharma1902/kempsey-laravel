<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    public function serviceTypes() {
        return $this->hasMany(ServiceType::class, 'service_id', 'id');
    }
    public function serviceOptions() {
        return $this->hasMany(ServiceOption::class, 'service_id', 'id');
    }
    public function storeServices()
    {
        return $this->hasMany(StoreServices::class, 'service_id');
    }
    
}
