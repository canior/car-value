<?php

namespace App\Service;

use App\Dtos\CarValuePredictionDto;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class CarValuePredictionService implements CarValuePredictionServiceInterface
{
    public function predict(CarValuePredictionDto $carValuePredictionDto): float
    {
        $response = Http::retry(3, 100, function ($exception, $request) {
            return $exception instanceof ConnectionException;
        })->post('http://127.0.0.1:5000', [
            'model_id' => 1,
            'mileage' => 0,
            'age' => 1
        ]);
        return $response->json();
    }
}
