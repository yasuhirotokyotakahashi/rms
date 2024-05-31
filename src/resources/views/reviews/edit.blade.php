@extends('layouts.app')
@section('content')
    <div class="edit-review-container">
        <h2>レビューの編集</h2>
        <form action="{{ route('reviews.update', ['review_id' => $review->id]) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- HTTPメソッドをPUTに設定 -->

            <div class="form-group">
                <label for="rating">評価:</label>
                <div class="rating-stars">
                    @for ($i = 5; $i >= 1; $i--)
                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}"
                            {{ $i == $review->rating ? 'checked' : '' }}>
                        <label for="star{{ $i }}" title="{{ $i }} stars">&#9733;</label>
                    @endfor
                </div>
            </div>
            <div class="form-group">
                <label for="comment">コメント:</label>
                <textarea name="comment" id="comment" rows="4" required>{{ $review->comment }}</textarea>
            </div>

            <div class="card__img">
                <div>
                    @if (strpos($review->image_path, 'http') === 0)
                        <!-- URLの場合 -->
                        <img src="{{ $shop->image_path }}" alt="{{ $review->shop->name }}">
                    @else
                        <!-- ローカルの場合 -->
                        <img src="{{ asset('storage/' . $review->image_path) }}" alt="{{ $review->shop->name }}">
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label for="image">画像アップロード:</label>
                <input type="file" name="image" accept="image/*" required>
            </div>


            <!-- レビューIDの隠しフィールド -->
            <input type="hidden" name="review_id" value="{{ $review->id }}">

            <button type="submit">更新</button>
        </form>
    </div>
    @push('styles')
        <link href="{{ asset('css/review/edit.css') }}" rel="stylesheet">
    @endpush
@endsection
