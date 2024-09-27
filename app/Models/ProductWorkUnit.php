<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductWorkUnit extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'work_unit_id'
    ];

    public function product()
    {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }

    public function work_unit()
    {
        return $this->hasOne('App\Models\WorkUnit', 'id', 'work_unit_id');
    }
}
