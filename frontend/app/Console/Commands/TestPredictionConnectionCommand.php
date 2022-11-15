<?php

namespace App\Console\Commands;

use App\Dtos\CarValuePredictionDto;
use App\Models\CarMake;
use App\Service\CarValuePredictionService;
use App\Service\ImportSoldCarServiceInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\LazyCollection;

class TestPredictionConnectionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:prediction-service {year} {make} {model} {mileage}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test prediction server connection';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(CarValuePredictionService $carValuePredictionService)
    {
        $year = $this->argument('year');
        $make = $this->argument('make');
        $model = $this->argument('model');
        $mileage = $this->argument('mileage');

        $carValuePredictionDto = new CarValuePredictionDto([
            'year' => $year,
            'make' => $make,
            'model' => $model,
            'mileage' => $mileage,
        ]);

        $carMake = CarMake::query()
            ->where('name', 'like', '%' . $carValuePredictionDto->make . '%')
            ->first();

        $response = Http::post(env('CAR_PREDICTION_URL', 'http://127.0.0.1:5000'), [
            'make_id' => intval($carMake->id),
            'mileage' => intval($carValuePredictionDto->mileage),
            'age' => intval(Carbon::now()->format('Y') - $carValuePredictionDto->year),
        ]);

        dd($response->body());

        return Command::SUCCESS;
    }
}
