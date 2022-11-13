<?php

namespace App\Service;

use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\CarSold;
use App\Models\Dealer;
use Illuminate\Support\Carbon;

class ImportSoldCarService implements ImportSoldCarServiceInterface
{
    public function import(array $record): bool
    {
        if (empty($record['vin']) || empty($record['year']) || empty($record['make']))
        {
            return false;
        }

        $carMake = CarMake::query()->firstOrCreate(['name' => $record['make']]);
        $carMake->save();
        $carModel = CarModel::query()->firstOrCreate([
            'name' => $record['model'],
            'car_make_id' => $carMake->id,
        ]);
        $carModel->save();
        $dealer = Dealer::query()->firstOrCreate([
            'name' => $record['dealer_name'],
            'street' => $record['dealer_street'],
            'city' => $record['dealer_city'],
            'state' => $record['dealer_state'],
            'zip' => $record['dealer_zip'],
        ]);
        $dealer->save();
        $carSold = CarSold::query()->firstOrCreate([
            'vin' => $record['vin'],
            'year' => $record['year'],
            'trim' => $record['trim'],
            'car_model_id' => $carModel->id,
            'dealer_id' => $dealer->id,
            'listing_price_in_cents' => (float)$record['listing_price'] * 100,
            'listing_mileage' => empty($record['listing_mileage']) ? null : $record['listing_mileage'],
            'used' => empty($record['used']) ? null : (boolean)$record['used'],
            'certified' => empty($record['certified']) ? null :  (boolean)$record['certified'],
            'style' => $record['style'],
            'driven_wheels' => $record['driven_wheels'],
            'engine' => $record['engine'],
            'fuel_type' => $record['fuel_type'],
            'exterior_color' => $record['exterior_color'],
            'interior_color' => $record['interior_color'],
            'seller_website' => $record['seller_website'],
            'first_seen_date' => empty($record['first_seen_date']) ? null : Carbon::createFromFormat('Y-m-d', $record['first_seen_date']),
            'last_seen_date' => empty($record['last_seen_date']) ? null : Carbon::createFromFormat('Y-m-d', $record['last_seen_date']),
            'dealer_vdp_last_seen_date' => empty($record['dealer_vdp_last_seen_date']) ? null : Carbon::createFromFormat('Y-m-d', $record['dealer_vdp_last_seen_date']),
            'listing_status' => $record['listing_status'],
        ]);
        $carSold->save();

        return true;
    }
}
