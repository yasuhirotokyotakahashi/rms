<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Review;
use App\Models\Shop;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function showReviewForm($shop_id)
    {
        // 指定された店舗の詳細情報を取得
        $shop = Shop::find($shop_id);
        if (!$shop) {
            // 指定された店舗が見つからない場合のエラーハンドリング
            return redirect()->route('shops.index')->with('error', '指定された店舗が見つかりません。');
        }

        return view('shops.review', compact('shop'));
    }

    public function store(ReviewRequest $request)
    {
        // 既存のレビューを取得
        $existingReview = Review::where('user_id', auth()->user()->id)
            ->where('shop_id', $request->input('shop_id'))
            ->first();
        if (!$existingReview) {
            // 画像アップロード処理
            if ($request->hasFile('image')) {
                // 画像を保存するディレクトリを指定
                $image = $request->file('image');
                $imagePath = $image->store(
                    'images',
                    'public'
                ); // ファイルをstorage/app/public/imagesに保存し、publicディスクを使用


                $review = new Review([
                    'user_id' => auth()->user()->id,
                    'shop_id' => $request->input('shop_id'), // フォームから正しい shop_id を取得
                    'rating' => $request->input('rating'),
                    'comment' => $request->input('comment'),
                ]);
                $review->image_path = $imagePath; // 保存したファイルパスをimage_pathに設定

                $review->save();
            }

            // 評価完了後のリダイレクトまたはメッセージを追加
            return
                redirect()->route('shops.show', ['shop_id' => $request->shop_id])->with('success', 'レビューが作成されました');
        } else {
            // 既存のレビューがある場合は何らかの処理を行う（例えばエラーメッセージを返すなど）
            return redirect()->back()->with('error', '既にレビューが存在します。');
        }
    }

    public function index()
    {
        $reviews = Review::all(); // すべての評価を取得
        return view('reviews.index', compact('reviews'));
    }

    public function edit($review_id)
    {
        $review = Review::find($review_id);
        if (!$review) {
            // 指定されたレヴューが見つからない場合のエラーハンドリング
            return redirect()->route('shops.index')->with('error', '指定されたレビューが見つかりません。');
        }

        return view('reviews.edit', compact('review'));
    }

    public function update(ReviewRequest $request, $review_id)
    {
        $review = Review::findOrFail($review_id);
        if ($request->hasFile('image')) {
            // 画像を保存するディレクトリを指定
            $image = $request->file('image');
            $imagePath = $image->store(
                'images',
                'public'
            ); // ファイルをstorage/app/public/imagesに保存し、publicディスクを使用
            $review->rating = $request->input('rating');
            $review->comment = $request->input('comment');
            $review->image_path = $imagePath; // 保存したファイルパスをimage_pathに設定
            $review->save();
        }

        return redirect()->route('shops.index', ['review_id' => $review->id])->with('success', 'レビューが更新されました');
    }

    public function delete($review_id)
    {
        $review = Review::find($review_id);
        $shop_id = $review->shop_id;
        $review->delete();

        // 削除が成功したらリダイレクトまたは適切な応答を返す
        return redirect()->route('shops.show', ['shop_id' => $shop_id])->with('success', 'レビューが削除されました');
    }



    // 他のメソッドを追加
}
