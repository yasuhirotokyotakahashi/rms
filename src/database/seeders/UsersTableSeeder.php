<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Shop;
use App\Models\User;
use App\Models\UserShopRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 代表者とAdminの役割を取得
        $representativeRole = Role::where('name', 'Representative')->first();
        $adminRole = Role::where('name', 'Admin')->first();

        // 各店舗のデフォルト代表者ユーザーを作成
        $shops = Shop::all();

        // Adminユーザーを作成
        $admin = User::create([
            'name' => '管理者',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // Adminに関連付けられた店舗IDを保存
        UserShopRole::create([
            'user_id' => $admin->id,
            'role_id' => $adminRole->id,
        ]);

        foreach ($shops as $shop) {
            // 代表者ユーザーを作成
            $representative = User::create([
                'name' => $shop->name . '代表者',
                'email' => $shop->id . '_representative@example.com',
                'password' => Hash::make('password'),
            ]);

            // 代表者に関連付けられた店舗IDを保存
            UserShopRole::create([
                'user_id' => $representative->id,
                'shop_id' => $shop->id,
                'role_id' => $representativeRole->id,
            ]);
        }
    }
}
