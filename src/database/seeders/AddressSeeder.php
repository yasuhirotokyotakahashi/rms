<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 住所のシーディング
        $addresses = [

            '東京都',
            '大阪府',
            '福岡県',

            // 他の住所を追加
        ];

        foreach ($addresses as $address) {
            Address::create(['city' => $address]);
        }
    }
}
