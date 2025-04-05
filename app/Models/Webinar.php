<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Webinar extends Model
{
    use HasFactory;

    protected $fillable = [
        'webinar_category_id',
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
        'datetime',
        'duration',
        'external_link',
        'admin_promotion_category_id',
        'is_public',
    ];

    public function webinar_category()
    {
        return $this->hasOne('App\Models\WebinarCategory', 'id', 'webinar_category_id');
    }

    public function webinar_comments()
    {
        return $this->hasMany('App\Models\WebinarComment', 'webinar_id', 'id');
    }

    public function images()
    {
        return $this->hasMany('App\Models\WebinarImage', 'webinar_id', 'id');
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

    public function work_units()
    {
        return $this->belongsToMany(WorkUnit::class, 'webinar_work_units');
    }
} 