<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 役割のデータを追加
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Representative']);
        Role::create(['name' => 'User']);
        // 他の役割を必要に応じて追加
    }
}
