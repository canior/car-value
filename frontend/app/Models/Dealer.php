<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dealer extends Model
{
    protected $fillable = [
        'name',
        'street',
        'city',
        'state',
        'zip',
        'updated_at',
        'created_at',
    ];
}
