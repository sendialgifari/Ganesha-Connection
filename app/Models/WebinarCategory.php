<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebinarCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'image', 'is_selected'
    ];

    public function webinars()
    {
        return $this->hasMany('App\Models\Webinar', 'webinar_category_id', 'id');
    }
} 