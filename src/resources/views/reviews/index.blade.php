@extends('layouts.app')

@section('content')
    <div class="shop-review-container">
        <div class="shop-review-left">
            <div class="shop-info">
                <h1>ご来店ありがとうございました。</h1>
                <div class="shop-item card">
                    <div class="card__img">
                        <img src="{{ asset('storage/' . $shop->image_path) }}" alt="{{ $shop->name }}">
                        <img src="{{ $shop->image_path }}" alt="{{ $shop->name }}">
                        <!-- 下はec2の際 -->
                        {{-- <img src="{{ asset($shop->image_path) }}" alt="{{ $shop->name }}"> --}}
                    </div>
                    <div class="card__content">
                        <h2 class="card__content-ttl">{{ $shop->name }}</h2>
                        <div class="card__content-tag">{{ $shop->genre->name }}</div>
                        <div class="card__content-tag">{{ $shop->address->city }}</div>
                        <!-- お気に入りボタン -->
                        @if (Auth::check())
                            <!-- ログインしているユーザーかどうかを確認 -->
                            @if (Auth::user()->favorites->contains($shop))
                                <form action="{{ route('favorites.destroy', $shop) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="heart-button1" type="submit">❤</button>
                                </form>
                            @else
                                <form action="{{ route('favorites.store', $shop) }}" method="POST">
                                    @csrf
                                    <button class="heart-button2" type="submit">❤</button>
                                </form>
                            @endif
                        @endif
                        <a class="card__content-link" href="{{ route('shops.show', ['shop_id' => $shop->id]) }}">詳しく見る</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 口コミ投稿フォーム -->
        <div class="shop-review-right">
            <div class="review-form">
                <h1>評価と口コミ</h1>
                <div class="form-container">
                    <!-- レビューフォーム -->
                    <form action="{{ route('reviews.store', ['shop_id' => $shop->id]) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <div>
                                <label for="rating">評価:</label>
                                <div class="rating-stars">
                                    <input type="radio" id="star5" name="rating" value="5">
                                    <label for="star5" title="5 stars">&#9733;</label>
                                    <input type="radio" id="star4" name="rating" value="4">
                                    <label for="star4" title="4 stars">&#9733;</label>
                                    <input type="radio" id="star3" name="rating" value="3">
                                    <label for="star3" title="3 stars">&#9733;</label>
                                    <input type="radio" id="star2" name="rating" value="2">
                                    <label for="star2" title="2 stars">&#9733;</label>
                                    <input type="radio" id="star1" name="rating" value="1">
                                    <label for="star1" title="1 star">&#9733;</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="comment">コメント:</label>
                            <textarea name="comment" id="comment" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image">画像アップロード:</label>
                            <input type="file" name="image" accept="image/*" required>
                        </div>

                        <div class="form-group">
                            <label>画像プレビュー:</label>
                            <img id="image-preview" src="#" alt="画像プレビュー"
                                style="max-width: 200px; max-height: 200px; display: none;">
                        </div>

                        <input type="hidden" name="shop_id" value="{{ $shop->id }}">

                        <button type="submit">評価とコメントを投稿</button>
                    </form>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    @push('styles')
        <link href="{{ asset('css/shops/review.css') }}" rel="stylesheet">
    @endpush
@endsection
