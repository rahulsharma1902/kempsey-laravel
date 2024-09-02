<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carousel extends Model
{
    use HasFactory;
    protected $fillable = [
        'heading',
        'sub_heading',
        'text',
        'button_text',
        'button_link',
        'image', // Ensure this matches your column name in the database
        'position',
    ];
}
