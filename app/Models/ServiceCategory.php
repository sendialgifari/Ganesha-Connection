<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'image', 'is_selected'
    ];

    public function services()
    {
        return $this->hasMany('App\Models\Service', 'id', 'service_category_id');
    }
}
