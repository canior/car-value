<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('street');
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->timestamps();
        });

        Schema::create('car_makes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('car_models', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('car_make_id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('car_solds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('car_model_id');
            $table->unsignedBigInteger('dealer_id');
            $table->string('vin');
            $table->string('year');
            $table->bigInteger('listing_price_in_cents');
            $table->bigInteger('listing_mileage')->nullable();
            $table->boolean('used');
            $table->boolean('certified');
            $table->string('style');
            $table->string('driven_wheels');
            $table->string('engine');
            $table->string('fuel_type')->nullable();
            $table->string('exterior_color');
            $table->string('interior_color');
            $table->string('seller_website');
            $table->dateTime('first_seen_date')->nullable();
            $table->dateTime('last_seen_date')->nullable();
            $table->dateTime('dealer_vdp_last_seen_date')->nullable();
            $table->string('listing_status');
            $table->timestamps();

            $table->foreign('car_model_id')->references('id')->on('car_models');
            $table->foreign('dealer_id')->references('id')->on('dealers');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
