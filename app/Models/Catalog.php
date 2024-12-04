<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    use HasFactory;

    public function work_units()
    {
        return $this->belongsToMany(WorkUnit::class, 'catalog_work_units');
    }

    public function admin_category()
    {
        return $this->hasOne('App\Models\AdminCategory', 'id', 'admin_category_id');
    }

    public function admin_promotion_category()
    {
        return $this->hasOne('App\Models\AdminPromotionCategory', 'id', 'admin_promotion_category_id');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
