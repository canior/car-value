<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarSoldResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'make' => $this->carModel->carMake->name,
            'model' => $this->carModel->name,
            'year' => $this->year,
            'mileage' => $this->listing_mileage,
            'price' => $this->listing_price_in_cents / 100,
            'location' => $this->dealer->city . ' ' . $this->dealer->state
        ];
    }
}
