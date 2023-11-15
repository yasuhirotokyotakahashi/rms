<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ジャンルのシーディング
        $genres = [
            '寿司',
            '焼肉',
            '居酒屋',
            'イタリアン',
            'ラーメン',
            // 他のジャンルを追加
        ];

        foreach ($genres as $genre) {
            Genre::create(['name' => $genre]);
        }
    }
}
