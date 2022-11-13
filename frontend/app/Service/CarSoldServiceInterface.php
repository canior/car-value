<?php

namespace App\Service;

use App\Dtos\CarValuePredictionDto;
use Illuminate\Contracts\Database\Query\Builder;

interface CarSoldServiceInterface
{
    /**
     * @param CarValuePredictionDto $carValuePredictionDto
     * @return Builder
     */
    public function listQuery(CarValuePredictionDto $carValuePredictionDto): Builder;
}
