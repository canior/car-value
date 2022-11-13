<?php

namespace App\Service;

use App\Dtos\CarValuePredictionDto;
use App\Models\CarSold;
use Illuminate\Contracts\Database\Query\Builder;

class CarSoldService implements CarSoldServiceInterface
{
    public function listQuery(CarValuePredictionDto $carValuePredictionDto): Builder
    {
        return CarSold::query()
            ->select('car_solds.*')
            ->join('car_models', 'car_models.id', '=',  'car_solds.car_model_id')
            ->join('car_makes', 'car_makes.id', '=',  'car_models.car_make_id')
            ->where('car_makes.name', 'like',  '%'.$carValuePredictionDto->make.'%')
            ->where('car_models.name', 'like', '%'.$carValuePredictionDto->model.'%');
    }
}
