<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarMake extends Model
{
    protected $fillable = [
        'name',
        'updated_at',
        'created_at',
    ];
}
