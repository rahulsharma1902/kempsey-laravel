<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    use HasFactory;
    public function filterOptions()
    {
        return $this->hasMany(FilterOption::class, 'filter_id', 'id');
    }

    public function Categorie()
    {
        return $this->belongsTo(Categorie::class, 'category_id');
    }
}
