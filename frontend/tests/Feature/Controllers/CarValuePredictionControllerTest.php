<?php

namespace Tests\Feature\Controllers;

use App\Dtos\CarValuePredictionDto;
use App\Http\Resources\CarSoldResource;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\CarSold;
use App\Service\CarSoldService;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Promise\RejectedPromise;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CarValuePredictionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        CarSold::factory()->create();
    }

    public function testSuccessPredictionWithoutMileage() {
        $prediction = 10000;

        Http::fake([
            getenv('CAR_PREDICTION_URL') => Http::response($prediction),
        ]);

        $carModel = CarModel::first();
        $year = 2022;
        $make = $carModel->carMake->name;
        $model = $carModel->name;

        $response = $this->json('post','api/car/prediction', [
            'year' => $year,
            'make' => $make,
            'model' => $model,
        ]);

        $response->assertOk();

        $data = json_decode($response->content(), true);

        $this->assertEquals('$' . number_format($prediction), $data['prediction']);

        /**
         * @var CarSoldService $carSoldService
         */
        $carSoldService = app()->make(CarSoldService::class);
        $expectArray = json_decode(CarSoldResource::collection($carSoldService
                ->listQuery(new CarValuePredictionDto([
                    'year' => $year,
                    'make' => $make,
                    'model' => $model,
                ]))->paginate(100)
            )->toJson(), true);

        $this->assertEquals($expectArray, $data['data']);
    }

    public function testSuccessPredictionWithMileage() {
        $prediction = 10000;

        Http::fake([
            getenv('CAR_PREDICTION_URL') => Http::response($prediction, 200),
        ]);

        $carModel = CarModel::first();
        $year = 2022;
        $make = $carModel->carMake->name;
        $model = $carModel->name;
        $mileage = 1000;

        $response = $this->json('post','api/car/prediction', [
            'year' => $year,
            'make' => $make,
            'model' => $model,
            'mileage' => $mileage,
        ]);

        $response->assertOk();

        $data = json_decode($response->content(), true);

        $this->assertEquals('$' . number_format($prediction), $data['prediction']);

        /**
         * @var CarSoldService $carSoldService
         */
        $carSoldService = app()->make(CarSoldService::class);
        $expectArray = json_decode(CarSoldResource::collection($carSoldService
            ->listQuery(new CarValuePredictionDto([
                'year' => $year,
                'make' => $make,
                'model' => $model,
                'mileage' => $mileage,
            ]))->paginate(100)
        )->toJson(), true);

        $this->assertEquals($expectArray, $data['data']);
    }

    public function testInvalidRequests(): void
    {
        $this->json('post','api/car/prediction', [
        ])->assertUnprocessable();

        $this->json('post','api/car/prediction', [
            'year' => 'abc'
        ])->assertUnprocessable();

        $this->json('post','api/car/prediction', [
            'year' => 2022
        ])->assertUnprocessable();

        $this->json('post','api/car/prediction', [
            'year' => 2022,
            'make' => 'not exist'
        ])->assertUnprocessable();

        $this->json('post','api/car/prediction', [
            'year' => 2022,
            'make' => CarMake::first()->name,
            'model' => 'not exist'
        ])->assertUnprocessable();
    }

    public function testPredictionServiceIsDown(): void
    {
        $guzzleRequest = new \GuzzleHttp\Psr7\Request('get', getenv('CAR_PREDICTION_URL'));
        Http::fake([
            getenv('CAR_PREDICTION_URL') =>
                fn ($request) =>
                new RejectedPromise(new ConnectException('Connection error', $guzzleRequest)),
        ]);

        $model = CarModel::first();

        $response = $this->json('post','api/car/prediction', [
            'year' => 2022,
            'make' => $model->carMake->name,
            'model' => $model->name,
        ]);

        $response->assertOk();

        $data = json_decode($response->content(), true);

        $this->assertEquals('$' . number_format(0), $data['prediction']);

    }
}
