<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_category_id',
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
    ];

    // protected $casts = [
    //     'ratings' => 'decimal:1' 
    // ];

    public function service_category()
    {
        return $this->hasOne('App\Models\ServiceCategory', 'id', 'service_category_id');
    }

    public function service_comments()
    {
        return $this->hasMany('App\Models\ServiceComment', 'id', 'service_id');
    }

    public function images()
    {
        return $this->hasMany('App\Models\ServiceImage', 'service_id', 'id');
    }

    public function work_units()
    {
        return $this->belongsToMany(WorkUnit::class, 'service_work_units');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function admin_category()
    {
        return $this->hasOne('App\Models\AdminCategory', 'id', 'admin_category_id');
    }
}
