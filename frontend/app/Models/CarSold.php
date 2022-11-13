<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarSold extends Model
{
    protected $fillable = [
        'vin',
        'year',
        'car_model_id',
        'dealer_id',
        'trim',
        'listing_price_in_cents',
        'listing_mileage',
        'used',
        'certified',
        'style',
        'driven_wheels',
        'engine',
        'fuel_type',
        'exterior_color',
        'interior_color',
        'seller_website',
        'first_seen_date',
        'last_seen_date',
        'dealer_vdp_last_seen_date',
        'listing_status',
        'updated_at',
        'created_at',
    ];

    public function carModel(): BelongsTo
    {
        return $this->belongsTo(CarModel::class);
    }

    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class);
    }


}
