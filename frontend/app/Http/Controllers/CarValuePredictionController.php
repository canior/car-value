<?php

namespace App\Http\Controllers;

use App\Dtos\CarValuePredictionDto;
use App\Http\Requests\CarValuePredictionRequest;
use App\Http\Resources\CarSoldResource;
use App\Service\CarSoldServiceInterface;
use App\Service\CarValuePredictionServiceInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class CarValuePredictionController extends Controller
{
    private CarValuePredictionServiceInterface $carValuePredictionService;
    private CarSoldServiceInterface $carSoldService;

    public function __construct(CarValuePredictionServiceInterface $carValuePredictionService,
                                CarSoldServiceInterface $carSoldService) {
        $this->carValuePredictionService = $carValuePredictionService;
        $this->carSoldService = $carSoldService;
    }

    /**
     * @throws UnknownProperties
     */
    public function predict(CarValuePredictionRequest $request): AnonymousResourceCollection
    {
        $carValuePredictionDto = new CarValuePredictionDto([
           'year' => $request->input('year'),
           'make' => $request->input('make'),
           'model' => $request->input('model'),
           'mileage' => $request->input('mileage'),
        ]);

        $prediction = $this->carValuePredictionService->predict($carValuePredictionDto);

        return CarSoldResource::collection($this->carSoldService
            ->listQuery($carValuePredictionDto)
            ->paginate(100)
        )->additional(['prediction' => '$' . number_format($prediction)]);
    }
}
