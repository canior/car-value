<?php

namespace Tests\Feature\Service;

use App\Models\CarSold;
use App\Service\ImportSoldCarService;
use Faker\Provider\Fakecar;
use Faker\Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportSoldCarServiceTest extends TestCase
{
    use RefreshDatabase;

    private Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = fake();
        $this->faker->addProvider(new Fakecar($this->faker));
    }

    public function testImportWithAllFieldsSuccessfully(): void
    {
        /**
         * @var ImportSoldCarService $importSoldCarService
         */
        $importSoldCarService = app()->make(ImportSoldCarService::class);

        for ($i = 0; $i < 10; $i++) {
            $record = [
                'vin' => $this->faker->vin,
                'year' => $this->faker->year(),
                'make' => $this->faker->vehicleBrand,
                'model' => $this->faker->vehicleModel,
                'dealer_name' => $this->faker->company(),
                'dealer_street' => $this->faker->streetAddress(),
                'dealer_city' => $this->faker->city(),
                'dealer_state' => $this->faker->country(),
                'dealer_zip' => $this->faker->postcode(),
                'trim' => $this->faker->vehicleType,
                'listing_price' => $this->faker->numberBetween(1000000, 100000),
                'listing_mileage' => $this->faker->numberBetween(0, 100000),
                'used' => $this->faker->boolean,
                'certified' => $this->faker->boolean,
                'style' => $this->faker->vehicleType,
                'driven_wheels' => "AWD",
                'engine' => $this->faker->vehicleGearBoxType,
                'fuel_type' => $this->faker->vehicleFuelType,
                'exterior_color' => $this->faker->colorName,
                'interior_color' => $this->faker->colorName,
                'seller_website' => $this->faker->url,
                'first_seen_date' => $this->faker->dateTime->format('Y-m-d'),
                'last_seen_date' => $this->faker->dateTime->format('Y-m-d'),
                'dealer_vdp_last_seen_date' => $this->faker->dateTime->format('Y-m-d'),
                'listing_status' => $this->faker->text(20),
            ];

            $importSoldCarService->import($record);

            $carSold = CarSold::query()->orderBy('id', 'desc')->first();

            $this->assertEquals($record['vin'], $carSold->vin);
            $this->assertEquals($record['year'], $carSold->year);
            $this->assertEquals($record['make'], $carSold->carModel->carMake->name);
            $this->assertEquals($record['model'], $carSold->carModel->name);
            $this->assertEquals($record['dealer_name'], $carSold->dealer->name);
            $this->assertEquals($record['dealer_street'], $carSold->dealer->street);
            $this->assertEquals($record['dealer_city'], $carSold->dealer->city);
            $this->assertEquals($record['dealer_state'], $carSold->dealer->state);
            $this->assertEquals($record['dealer_zip'], $carSold->dealer->zip);
            $this->assertEquals($record['trim'], $carSold->trim);
            $this->assertEquals($record['listing_price'], (float)($carSold->listing_price_in_cents / 100));
            $this->assertEquals($record['listing_mileage'], $carSold->listing_mileage);
            $this->assertEquals($record['used'], $carSold->used);
            $this->assertEquals($record['certified'], $carSold->certified);
            $this->assertEquals($record['style'], $carSold->style);
            $this->assertEquals($record['driven_wheels'], $carSold->driven_wheels);
            $this->assertEquals($record['engine'], $carSold->engine);
            $this->assertEquals($record['fuel_type'], $carSold->fuel_type);
            $this->assertEquals($record['exterior_color'], $carSold->exterior_color);
            $this->assertEquals($record['interior_color'], $carSold->interior_color);
            $this->assertEquals($record['seller_website'], $carSold->seller_website);
            $this->assertEquals($record['first_seen_date'], $carSold->first_seen_date->format('Y-m-d'));
            $this->assertEquals($record['last_seen_date'], $carSold->last_seen_date->format('Y-m-d'));
            $this->assertEquals($record['dealer_vdp_last_seen_date'], $carSold->dealer_vdp_last_seen_date->format('Y-m-d'));
            $this->assertEquals($record['listing_status'], $carSold->listing_status);
        }
    }

    public function testImportWithAllRequiredFieldsSuccessfully(): void
    {
        /**
         * @var ImportSoldCarService $importSoldCarService
         */
        $importSoldCarService = app()->make(ImportSoldCarService::class);

        for ($i = 0; $i < 10; $i++) {
            $record = [
                'vin' => $this->faker->vin,
                'year' => $this->faker->year(),
                'make' => $this->faker->vehicleBrand,
                'model' => '',
                'dealer_name' => '',
                'dealer_street' => '',
                'dealer_city' => '',
                'dealer_state' => '',
                'dealer_zip' => '',
                'trim' => '',
                'listing_price' => '',
                'listing_mileage' => '',
                'used' => '',
                'certified' => '',
                'style' => '',
                'driven_wheels' => '',
                'engine' => '',
                'fuel_type' => '',
                'exterior_color' => '',
                'interior_color' => '',
                'seller_website' => '',
                'first_seen_date' => '',
                'last_seen_date' => '',
                'dealer_vdp_last_seen_date' => '',
                'listing_status' => '',
            ];

            $importSoldCarService->import($record);

            $carSold = CarSold::query()->orderBy('id', 'desc')->first();

            $this->assertEquals($record['vin'], $carSold->vin);
            $this->assertEquals($record['year'], $carSold->year);
            $this->assertEquals($record['make'], $carSold->carModel->carMake->name);
            $this->assertEquals($record['model'], $carSold->carModel->name);
            $this->assertEquals($record['dealer_name'], $carSold->dealer->name);
            $this->assertEquals($record['dealer_street'], $carSold->dealer->street);
            $this->assertEquals($record['dealer_city'], $carSold->dealer->city);
            $this->assertEquals($record['dealer_state'], $carSold->dealer->state);
            $this->assertEquals($record['dealer_zip'], $carSold->dealer->zip);
            $this->assertEquals($record['trim'], $carSold->trim);
            $this->assertEquals(0, (float)($carSold->listing_price_in_cents / 100));
            $this->assertEquals(0, $carSold->listing_mileage);
            $this->assertEquals(null, $carSold->used);
            $this->assertEquals(null, $carSold->certified);
            $this->assertEquals($record['style'], $carSold->style);
            $this->assertEquals($record['driven_wheels'], $carSold->driven_wheels);
            $this->assertEquals($record['engine'], $carSold->engine);
            $this->assertEquals($record['fuel_type'], $carSold->fuel_type);
            $this->assertEquals($record['exterior_color'], $carSold->exterior_color);
            $this->assertEquals($record['interior_color'], $carSold->interior_color);
            $this->assertEquals($record['seller_website'], $carSold->seller_website);
            $this->assertEquals(null, $carSold->first_seen_date);
            $this->assertEquals(null, $carSold->last_seen_date);
            $this->assertEquals(null, $carSold->dealer_vdp_last_seen_date);
            $this->assertEquals($record['listing_status'], $carSold->listing_status);
        }
    }

    public function testImportWithInvalidFields(): void
    {
        $record = [
            'vin' => '',
            'year' => '',
            'make' => '',
            'model' => '',
            'dealer_name' => '',
            'dealer_street' => '',
            'dealer_city' => '',
            'dealer_state' => '',
            'dealer_zip' => '',
            'trim' => '',
            'listing_price' => '',
            'listing_mileage' => '',
            'used' => '',
            'certified' => '',
            'style' => '',
            'driven_wheels' => '',
            'engine' => '',
            'fuel_type' => '',
            'exterior_color' => '',
            'interior_color' => '',
            'seller_website' => '',
            'first_seen_date' => '',
            'last_seen_date' => '',
            'dealer_vdp_last_seen_date' => '',
            'listing_status' => '',
        ];

        /**
         * @var ImportSoldCarService $importSoldCarService
         */
        $importSoldCarService = app()->make(ImportSoldCarService::class);

        $result = $importSoldCarService->import($record);

        $this->assertFalse($result);
    }
}
