<?php

namespace App\Console\Commands;

use App\Service\ImportSoldCarServiceInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\LazyCollection;

class ImportSoldCarsCommand extends Command
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
    public function handle(ImportSoldCarServiceInterface $importSoldCarService)
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
        ->each(function (LazyCollection $chunk) use (&$bar, $importSoldCarService) {
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

            DB::transaction(function() use ($records, &$bar, $importSoldCarService) {
                foreach($records as $record) {
                    try {
                        $this->output->progressAdvance();
                        $result = $importSoldCarService->import($record);
                        if (!$result) {
                            Log::info('invalid row: ' . json_encode($record) . ', exception: ' . $e->getMessage());
                        }
                    } catch (\Exception $e) {
                        Log::info('exception row: ' . json_encode($record) . ', exception: ' . $e->getMessage());
                        continue;
                    }
                }
            });
        });

        $this->output->progressFinish();

        return Command::SUCCESS;
    }
}
