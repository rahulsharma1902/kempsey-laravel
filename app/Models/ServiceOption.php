<?php
// ServiceOption.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceOption extends Model
{
    use HasFactory;

    protected $casts = [
        'service_type_ids' => 'array', // Automatically casts JSON to an array
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function serviceTypes()
    {
        return $this->belongsToMany(ServiceType::class, 'service_option_service_type', 'service_option_id', 'service_type_id');
    }
}
