<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoritesController extends Controller
{
    //
    public function store(Shop $shop)
    {


        // ログインしているユーザーのIDを取得
        $userId = Auth::id();

        // すでにお気に入りに登録されているか確認
        if (!$shop->favoritedBy()->where('user_id', $userId)->exists()) {
            // お気に入りに登録
            $shop->favoritedBy()->attach($userId);
            return back()->with('success', 'お気に入りに追加しました');
        } else {
            return back()->with('error', 'すでにお気に入りに登録されています');
        }
    }

    public function destroy(Shop $shop)
    {
        // ログインしているユーザーのIDを取得
        $userId = Auth::id();

        // お気に入りから削除
        if ($shop->favoritedBy()->where('user_id', $userId)->exists()) {
            Auth::user()->favorites()->detach($shop->id);
            return back()->with('success', 'お気に入りから削除しました');
        } else {
            return back()->with('error', 'お気に入りから削除できませんでした');
        }
    }
}
