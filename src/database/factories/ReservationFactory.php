<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Reservation::class;


    public function definition()
    {

        //

        $shop = Shop::inRandomOrder()->first();

        return [
            'user_id' => \App\Models\User::inRandomOrder()->first()->id,  // 修正
            'shop_id' => $shop->id,  // 修正
            'date' => $this->faker->date,
            'time' => $this->faker->time,
            'guests' => $this->faker->numberBetween(1, 10),
        ];
    }
}
