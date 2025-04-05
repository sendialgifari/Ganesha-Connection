<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_category_id',
        'name',
        'description',
        'ratings',
        'topics',
        'short_description',
        'views_counter',
        'image',
        'image_thumb',
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
        'goal_amount',
        'collected_amount',
        'external_link',
        'admin_promotion_category_id',
        'is_public',
    ];

    public function donation_category()
    {
        return $this->hasOne('App\Models\DonationCategory', 'id', 'donation_category_id');
    }

    public function donation_comments()
    {
        return $this->hasMany('App\Models\DonationComment', 'donation_id', 'id');
    }

    public function images()
    {
        return $this->hasMany('App\Models\DonationImage', 'donation_id', 'id');
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
        return $this->belongsToMany(WorkUnit::class, 'donation_work_units');
    }
} 