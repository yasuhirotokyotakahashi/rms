<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function index()
    {
        // ログイン中のユーザーに関連する予約情報を取得
        $user = Auth::user();
        $reservations = $user->reservations;

        // ログイン中のユーザーに関連するお気に入り店舗情報を取得
        $favoriteShops = $user->favorites;

        return view('mypage', ['reservations' => $reservations, 'favoriteShops' => $favoriteShops]);
    }
}
