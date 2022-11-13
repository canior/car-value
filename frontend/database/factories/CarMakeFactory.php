<?php

namespace Database\Factories;

use Faker\Provider\Fakecar;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarMakeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $this->faker->addProvider(new Fakecar($this->faker));
        $vehicle = $this->faker->vehicleArray();

        return [
            'name' => $vehicle['brand'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
