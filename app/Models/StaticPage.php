<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaticPage extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'is_active',
        'image',
        'image_thumb',
        'slug',
    ];
}
