<?php

namespace App\Http\Controllers;

use App\Dtos\CarValuePredictionDto;
use App\Http\Requests\CarValuePredictionRequest;
use App\Http\Resources\CarMakeResource;
use App\Http\Resources\CarSoldResource;
use App\Models\CarMake;
use App\Models\CarSold;
use App\Service\CarValuePredictionServiceInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class CarValuePredictionController extends Controller
{
    private CarValuePredictionServiceInterface $carValuePredictionService;

    public function __construct(CarValuePredictionServiceInterface $carValuePredictionService) {
        $this->carValuePredictionService = $carValuePredictionService;
    }

    public function index() {
        echo "hi";
    }

    public function makes(): AnonymousResourceCollection
    {
        return CarMakeResource::collection(CarMake::all());
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

        return CarSoldResource::collection(CarSold::query()->select('*')->limit(5)->paginate(10))
            ->additional(['prediction' => $prediction]);
    }
}
