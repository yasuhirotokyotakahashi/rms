<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {

        $shops = Shop::all();


        return view('shops.index', compact('shops'));
    }

    public function show($shop_id)
    {
        // 指定された店舗の詳細情報を取得
        $shop = Shop::find($shop_id);

        if (!$shop) {
            // 指定された店舗が見つからない場合のエラーハンドリング
            return redirect()->route('shops.index')->with('error', '指定された店舗が見つかりません。');
        }

        // 指定された店舗のレビュー情報を取得
        $reviews = $shop->reviews;

        // 取得した詳細情報をビューに渡す
        return view('shops.show', compact('shop', 'reviews'));
    }

    public function create()
    {

        $reservations = Reservation::all(); // 全ての予約情報を取得
        return view('shops.create', compact('reservations'));
    }


    public function store(Request $request)
    {

        // フォームデータのバリデーションを行う
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            // 他のフォームフィールドのバリデーションルールを追加
        ]);

        // 画像アップロード処理
        if ($request->hasFile('image')) {
            // 画像を保存するディレクトリを指定
            $image = $request->file('image');
            $imagePath = $image->store(
                'images',
                'public'
            ); // ファイルをstorage/app/public/imagesに保存し、publicディスクを使用

            // 画像パスをデータベースに保存
            $shop = new Shop();
            $shop->name = $request->input('name');
            $shop->description = $request->input('description');
            $shop->image_path = $imagePath; // 保存したファイルパスをimage_pathに設定
            $shop->address_id = 1;
            $shop->genre_id = 1;
            $shop->save();
        }

        return redirect('/admin/shops/create')->with('success', '店舗が登録されました');
    }

    public function search(Request $request)
    {
        $area = $request->input('area');
        $genre = $request->input('genre');
        $name = $request->input('name');

        $results = Shop::query()
            ->when($area, function ($query, $area) {
                return $query->searchByArea($area);
            })
            ->when($genre, function ($query, $genre) {
                return $query->searchByGenre($genre);
            })
            ->when($name, function ($query, $name) {
                return $query->searchByName($name);
            })
            ->get();

        return view('shops.search', compact('results'));
    }
}
