<?php

namespace Database\Seeders;

use App\Models\Reservation;
use Database\Factories\ReservationFactory;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Reservation::factory(50)->create();
    }
}
