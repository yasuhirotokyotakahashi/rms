<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Role;
use App\Models\Shop;
use App\Models\User;
use App\Models\UserShopRole;
use Illuminate\Http\Request;

class RoleUserController extends Controller
{

    public function showAssignShopRoleForm()
    {
        // ユーザーと役割のデータを取得
        $users = User::all(); // ユーザーの一覧を取得
        $roles = Role::all(); // 役割の一覧を取得
        $shops = Shop::all();

        $allReservations = Reservation::with('shop')->get();

        // 全てのユーザーとそれに割り当てられた役割情報を取得
        $allUsers = UserShopRole::with('user', 'role')->get();

        return view('assign-role', compact('allReservations', 'allUsers', 'users', 'roles', 'shops'));
    }


    public function assignShopRepresentative(Request $request)
    {
        $userId = $request->input('userId');
        $shopId = $request->input('shopId');
        $roleId = $request->input('roleId');

        // ユーザーを User モデルから取得
        $user = User::find($userId);

        // 店舗を Shop モデルから取得
        $shop = Shop::find($shopId);

        // 役割を Role モデルから取得
        $role = Role::find($roleId);

        if ($user && $shop && $role) {
            // UserShopRole モデルを使用して関連付けを作成
            $userShopRole = new UserShopRole();
            $userShopRole->user_id = $userId;
            $userShopRole->shop_id = $shopId;
            $userShopRole->role_id = $roleId;
            $userShopRole->save();

            $message = $user->name . ' に ' . $role->name . ' 役割が割り当てられました';

            return redirect()->back()->with('success', $message);
        } else {
            return redirect()->back()->with('error', 'ユーザー、店舗、または役割が見つかりません');
        }
    }

    public function unassignRoleFromUser(Request $request)
    {
        $userId = $request->input('userId');
        $roleId = $request->input('roleId');

        // ユーザーを User モデルから取得
        $user = User::find($userId);

        // 役割を Role モデルから取得
        $role = Role::find($roleId);

        if ($user && $role) {
            // User モデルと Role モデルの関連付けを削除
            $user->roles()->detach($role->id);

            $message = $user->name . ' から ' . $role->name . ' 役職が取り消されました';

            return redirect()->back()->with('success', $message);
        } else {
            return redirect()->back()->with('error', 'ユーザーまたは役職が見つかりません');
        }
    }
}
