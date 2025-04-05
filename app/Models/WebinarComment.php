<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebinarComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'webinar_id',
        'comment',
        'user_id',
    ];

    public function webinar()
    {
        return $this->hasOne('App\Models\Webinar', 'id', 'webinar_id');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
} 