<?php

namespace Database\Seeders;

use App\Models\CarSold;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        CarSold::factory(200)->create();
    }
}
