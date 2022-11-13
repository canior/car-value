<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'car_make_id',
        'updated_at',
        'created_at',
    ];


    public function carMake(): BelongsTo
    {
        return $this->belongsTo(CarMake::class);
    }
}
