<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'image', 'is_selected'
    ];

    public function donations()
    {
        return $this->hasMany('App\Models\Donation', 'donation_category_id', 'id');
    }
} 