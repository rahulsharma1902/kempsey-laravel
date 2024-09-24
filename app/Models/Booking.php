<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'bike_brand', 'bike_model', 'bike_type', 'bike_color', 'service_date',
        'services', 'service_ids', 'store_id', 'types', 'user_fname', 'user_lname',
        'user_email', 'user_phone', 'hear_about_us', 'status',
    ];

    protected $casts = [
        'service_ids' => 'array', // Cast service_ids to an array
        'services' => 'array',    // Cast services to an array
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    public function getRelatedServicesWithTypesAttribute()
    {
        // Decode services to an associative array
        $serviceDetails = json_decode($this->services, true) ?? [];
    
        // Check if serviceDetails is an array
        if (!is_array($serviceDetails)) {
            return collect([]);
        }
    
        // Extract service IDs
        $serviceIds = array_column($serviceDetails, 'id');
    
        // Get services with the extracted IDs
        $services = Service::whereIn('id', $serviceIds)->get();
    
        foreach ($services as $service) {
            // Find the service data in the array using service ID
            $serviceData = collect($serviceDetails)->firstWhere('id', $service->id);
    
            // Check if serviceData is an array and contains 'types'
            if (is_array($serviceData) && isset($serviceData['types']) && is_array($serviceData['types'])) {
                // Fetch related types
                $service->types = ServiceType::whereIn('id', $serviceData['types'])->get()->toArray();
            } else {
                $service->types = collect([]);
            }
        }
    
        return $services;
    }
    
}
