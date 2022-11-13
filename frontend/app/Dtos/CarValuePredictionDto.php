<?php

namespace App\Dtos;

use Spatie\DataTransferObject\DataTransferObject;

class CarValuePredictionDto extends DataTransferObject
{
    public int $year;
    public string $make;
    public string $model;
    public ?int $mileage;
}
