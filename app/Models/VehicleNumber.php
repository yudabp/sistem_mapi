<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleNumber extends Model
{
    protected $fillable = [
        'number',
        'description',
        'is_active',
    ];
}
