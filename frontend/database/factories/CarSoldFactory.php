<?php

namespace Database\Factories;

use App\Models\CarModel;
use App\Models\Dealer;
use Faker\Provider\Fakecar;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CarSoldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $this->faker->addProvider(new Fakecar($this->faker));

        return [
            'vin' => $this->faker->vin,
            'year' => fake()->year(),
            'car_model_id' => CarModel::factory()->create()->id,
            'dealer_id' => Dealer::factory()->create()->id,
            'trim' => $this->faker->vehicleType,
            'listing_price_in_cents' => $this->faker->numberBetween(1000000, 10000000),
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
            'first_seen_date' => $this->faker->dateTime,
            'last_seen_date' => $this->faker->dateTime,
            'dealer_vdp_last_seen_date' => $this->faker->dateTime,
            'listing_status' => $this->faker->text(20),
            'updated_at' => Carbon::now(),
            'created_at'=> Carbon::now(),
        ];
    }
}
