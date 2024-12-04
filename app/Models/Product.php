<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_category_id',
        'name',
        'description',
        'price',
        'ratings',
        'topics',
        'short_description',
        'views_counter',
        'image',
        'image_thumb',
        'fake_price',
        'is_active_comment',
        'slug',
        'user_id',
        'total_comments',
        'total_comment_star_1',
        'total_comment_star_2',
        'total_comment_star_3',
        'total_comment_star_4',
        'total_comment_star_5',
        'is_selected',
        'admin_category_id',
        'price_type',
        'is_readystock',
        'image_real',
        'external_link',
        'admin_promotion_category_id',
        'is_public',
    ];

    // protected $casts = [
    //     'ratings' => 'decimal:1' 
    // ];

    public function product_category()
    {
        return $this->hasOne('App\Models\ProductCategory', 'id', 'product_category_id');
    }

    public function product_comments()
    {
        return $this->hasMany('App\Models\ProductComment', 'id', 'product_id');
    }

    public function images()
    {
        return $this->hasMany('App\Models\ProductImage', 'product_id', 'id');
    }

    public function work_units()
    {
        return $this->belongsToMany(WorkUnit::class, 'product_work_units');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function admin_category()
    {
        return $this->hasOne('App\Models\AdminCategory', 'id', 'admin_category_id');
    }

    public function admin_promotion_category()
    {
        return $this->hasOne('App\Models\AdminPromotionCategory', 'id', 'admin_promotion_category_id');
    }
}
