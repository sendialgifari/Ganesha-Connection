<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'provinces';

    public function cities()
    {
        return $this->hasMany('App\Models\City', 'province_id', 'id');
    }
}
