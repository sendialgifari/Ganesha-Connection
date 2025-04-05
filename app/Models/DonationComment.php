<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_id',
        'comment',
        'user_id',
    ];

    public function donation()
    {
        return $this->hasOne('App\Models\Donation', 'id', 'donation_id');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
} 