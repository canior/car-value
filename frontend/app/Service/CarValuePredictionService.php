<?php

namespace App\Service;

use App\Dtos\CarValuePredictionDto;
use App\Models\CarMake;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CarValuePredictionService implements CarValuePredictionServiceInterface
{
    public function predict(CarValuePredictionDto $carValuePredictionDto): int
    {
        try {
            $carMake = CarMake::query()
                ->where('name', 'like', '%' . $carValuePredictionDto->make . '%')
                ->first();

            $response = Http::post(getenv('CAR_PREDICTION_URL'), [
                'model_id' => $carMake->id,
                'mileage' => $carValuePredictionDto->mileage,
                'age' => Carbon::now()->format('Y') - $carValuePredictionDto->year,
            ]);

            return intval($response->json());

        } catch(\Exception $e) {
            Log::info('Cannot connect to prediction service'. $e->getTraceAsString());
            return 0;
        }

    }
}
