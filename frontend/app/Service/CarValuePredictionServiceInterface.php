<?php

namespace App\Service;

use App\Dtos\CarValuePredictionDto;

interface CarValuePredictionServiceInterface
{
    public function predict(CarValuePredictionDto $carValuePredictionDto): int;
}
