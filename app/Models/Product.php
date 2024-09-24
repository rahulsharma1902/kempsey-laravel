<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function Categorie()
    {
        return $this->hasOne(Categorie::class, 'id','category_id');
    }
    public function Brand()
    {
        return $this->hasOne(Brand::class, 'id','brand_id');
    }
}
