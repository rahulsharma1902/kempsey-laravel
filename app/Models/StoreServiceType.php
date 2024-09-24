<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreServiceType extends Model
{
  
    use HasFactory;

    protected $fillable = [
        'store_service_id',
        // 'service_id',
        'service_type_id',
    ];
    public function serviceTypeData()
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id', 'id');
    }
    

}
