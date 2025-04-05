<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebinarImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'webinar_id',
        'image',
    ];

    public function webinar()
    {
        return $this->belongsTo('App\Models\Webinar', 'webinar_id');
    }
} 