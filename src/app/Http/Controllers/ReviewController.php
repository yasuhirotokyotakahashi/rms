<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Review;
use App\Models\Shop;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index($shop_id)
    {
        // 指定された店舗の詳細情報を取得
        $shop = Shop::find($shop_id);
        if (!$shop) {

            return redirect()->route('shops.index')->with('error', '指定された店舗が見つかりません。');
        }

        return view('reviews.index', compact('shop'));
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
                );

                $review = new Review([
                    'user_id' => auth()->user()->id,
                    'shop_id' => $request->input('shop_id'),
                    'rating' => $request->input('rating'),
                    'comment' => $request->input('comment'),
                ]);
                $review->image_path = $imagePath; // 保存したファイルパスをimage_pathに設定

                $review->save();
            }

            return
                redirect()->route('shops.show', ['shop_id' => $request->shop_id])->with('success', 'レビューが作成されました');
        } else {
            return redirect()->back()->with('error', '既にレビューが存在します。');
        }
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

    public function update(Request $request, $review_id)
    {
        $review = Review::findOrFail($review_id);
        if ($request->hasFile('image')) {
            // 画像を保存するディレクトリを指定
            $image = $request->file('image');
            $imagePath = $image->store(
                'images',
                'public'
            );
            $review->image_path = $imagePath; // 保存したファイルパスをimage_pathに設定; // ファイルをstorage/app/public/imagesに保存し、publicディスクを使用
        }
        $review->rating = $request->input('rating');
        $review->comment = $request->input('comment');

        $review->save();


        return redirect()->route('shops.index', ['review_id' => $review->id])->with('success', 'レビューが更新されました');
    }

    public function delete($review_id)
    {
        $review = Review::find($review_id);
        $shop_id = $review->shop_id;
        $review->delete();

        return redirect()->route('shops.show', ['shop_id' => $shop_id])->with('success', 'レビューが削除されました');
    }
}
