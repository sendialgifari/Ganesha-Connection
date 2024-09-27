<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceWorkUnit extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_id',
        'work_unit_id'
    ];

    public function service()
    {
        return $this->hasOne('App\Models\Service', 'id', 'service_id');
    }

    public function work_unit()
    {
        return $this->hasOne('App\Models\WorkUnit', 'id', 'work_unit_id');
    }
}
