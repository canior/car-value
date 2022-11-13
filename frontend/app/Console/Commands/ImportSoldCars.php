<?php

namespace App\Console\Commands;

use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\CarSold;
use App\Models\Dealer;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\LazyCollection;

class ImportSoldCars extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:sold-cars {file-path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import sold cars from a csv file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filePath = $this->argument('file-path');

        $collections = LazyCollection::make(function () use ($filePath, &$count) {
            $handle = fopen($filePath, 'r');
            while (($row = fgetcsv($handle, 4096, '|')) !== false) {
                yield $row;
            }
            fclose($handle);
        });

        $this->output->progressStart($collections->count());

        $collections->skip(1)
        ->chunk(1000)
        ->each(function (LazyCollection $chunk) use (&$bar) {
            $records = $chunk->map(function ($row) use (&$bar) {
                return [
                    'vin' => $row[0],
                    'year' => $row[1],
                    'make' => $row[2],
                    'model' => $row[3],
                    'trim' => $row[4],
                    'dealer_name' => $row[5],
                    'dealer_street' => $row[6],
                    'dealer_city' => $row[7],
                    'dealer_state' => $row[8],
                    'dealer_zip' => $row[9],
                    'listing_price' => $row[10],
                    'listing_mileage' => $row[11],
                    'used' => $row[12],
                    'certified' => $row[13],
                    'style' => $row[14],
                    'driven_wheels' => $row[15],
                    'engine' => $row[16],
                    'fuel_type' => $row[17],
                    'exterior_color' => $row[18],
                    'interior_color' => $row[19],
                    'seller_website' => $row[20],
                    'first_seen_date' => $row[21],
                    'last_seen_date' => $row[22],
                    'dealer_vdp_last_seen_date' => $row[23],
                    'listing_status' => $row[24],
                ];
            })->toArray();

            DB::transaction(function() use ($records, &$bar) {
                foreach($records as $record) {
                    try {
                        $this->output->progressAdvance();
                        $carMake = CarMake::query()->firstOrCreate(['name' => $record['make']]);
                        $carMake->save();
                        $carModel = CarModel::query()->firstOrCreate([
                            'name' => $record['model'],
                            'car_make_id' => $carMake->id,
                        ]);
                        $carModel->save();
                        $dealer = Dealer::query()->firstOrCreate([
                            'name' => $record['dealer_name'],
                            'street' => $record['dealer_street'],
                            'city' => $record['dealer_city'],
                            'state' => $record['dealer_state'],
                            'zip' => $record['dealer_zip'],
                        ]);
                        $dealer->save();
                        $carSold = CarSold::query()->firstOrCreate([
                            'vin' => $record['vin'],
                            'year' => $record['year'],
                            'car_model_id' => $carModel->id,
                            'dealer_id' => $dealer->id,
                            'listing_price_in_cents' => (float)$record['listing_price'] * 100,
                            'listing_mileage' => empty($record['listing_mileage']) ? null : $record['listing_mileage'],
                            'used' => (boolean)$record['used'],
                            'certified' => (boolean)$record['certified'],
                            'style' => $record['style'],
                            'driven_wheels' => $record['driven_wheels'],
                            'engine' => $record['engine'],
                            'fuel_type' => empty($record['fuel_type']) ? null : $record['fuel_type'],
                            'exterior_color' => $record['exterior_color'],
                            'interior_color' => $record['interior_color'],
                            'seller_website' => $record['seller_website'],
                            'first_seen_date' => empty($record['first_seen_date']) ? null : Carbon::createFromFormat('Y-m-d', $record['first_seen_date']),
                            'last_seen_date' => empty($record['last_seen_date']) ? null : Carbon::createFromFormat('Y-m-d', $record['last_seen_date']),
                            'dealer_vdp_last_seen_date' => empty($record['dealer_vdp_last_seen_date']) ? null : Carbon::createFromFormat('Y-m-d', $record['dealer_vdp_last_seen_date']),
                            'listing_status' => $record['listing_status'],
                        ]);
                        $carSold->save();
                    } catch (\Exception $e) {
                        Log::info('invalid row: ' . json_encode($record) . ', exception: ' . $e->getMessage());
                        continue;
                    }
                }
            });
        });

        $this->output->progressFinish();

        return Command::SUCCESS;
    }
}
