<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Shop;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    //
    public function store(Request $request)
    {
        // バリデーションを追加

        $review = new Review([
            'user_id' => auth()->user()->id,
            'shop_id' => $request->input('shop_id'), // フォームから正しい shop_id を取得
            'rating' => $request->input('rating'),
            'comment' => $request->input('comment'),
        ]);

        $review->save();

        // 評価完了後のリダイレクトまたはメッセージを追加
        return view('reviews.thanks');
    }

    public function index()
    {
        $reviews = Review::all(); // すべての評価を取得
        return view('reviews.index', compact('reviews'));
    }

    // 他のメソッドを追加
}
