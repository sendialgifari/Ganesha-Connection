<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name', 'slug', 'image', 'is_selected'
    ];

    public function products()
    {
        return $this->hasMany('App\Models\Product', 'id', 'product_category_id');
    }
}
