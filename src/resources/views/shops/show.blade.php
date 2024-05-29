@extends('layouts.app')

@section('content')
    <h1>店舗詳細</h1>
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="shop-detail-container">
        <div class="shop-detail-left">
            <div class="shop-detail-image">
                <img src="{{ asset('storage/' . $shop->image_path) }}" alt="{{ $shop->name }}">
            </div>
            <div class="shop-detail-info">
                <p class="shop-detail-address">{{ $shop->address->city }}</p>
                <p class="shop-detail-genre">{{ $shop->genre->name }}</p>

                <p>{{ $shop->name }}</p>
                <p>{{ $shop->description }}</p>

                <!-- お気に入りボタン -->
                @if (Auth::check())
                    <!-- ログインしているユーザーかどうかを確認 -->
                    @if (Auth::user()->favorites->contains($shop))
                        <form action="{{ route('favorites.destroy', $shop) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit">お気に入りから削除</button>
                        </form>
                    @else
                        <form action="{{ route('favorites.store', $shop) }}" method="POST">
                            @csrf
                            <button type="submit">お気に入りに追加</button>
                        </form>
                    @endif
                @endif
            </div>
            <!-- レビュー情報を表示 -->
            <div class="shop-reviews">
                <h2>レビュー</h2>
                @if ($reviews->isEmpty())
                    <p>まだレビューがありません。</p>
                @else
                    <ul class="shop-reviews">
                        @foreach ($reviews as $review)
                            <li class="review-item">
                                <div class="review-header">
                                    <div class="rating-stars">
                                        @for ($i = 5; $i >= 1; $i--)
                                            <span class="star {{ $i <= $review->rating ? 'filled' : '' }}">&#9733;</span>
                                        @endfor
                                    </div>
                                    @auth
                                        @if ($review->user_id === auth()->user()->id)
                                            <div class="review-actions">
                                                <!-- 編集ボタン -->
                                                <a href="{{ route('reviews.edit', ['review_id' => $review->id]) }}">編集</a>
                                                <!-- 削除ボタン -->
                                                <form action="{{ route('reviews.delete', ['review_id' => $review->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit">削除</button>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                                <div class="review-body">
                                    <p class="review-comment">{{ $review->comment }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
        <div class="shop-detail-right">
            <div class="reservation-container">
                <!-- 予約フォームのスタイルをここに追加 -->
                <div class="form-container">
                    <form action="{{ route('reservations.store') }}" method="POST"> <!-- ルート名を使用 -->
                        @csrf

                        <!-- バリデーションエラーメッセージを表示 -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{!! $error !!}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- 隠しフィールドで店舗 ID を渡す -->
                        <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}"> <!-- ログインユーザーの ID を保存 -->

                        <div class="form-group">
                            <label for="reservation-date">予約日付</label>
                            <input type="date" id="reservation-date" name="reservation_date" required>
                        </div>

                        <div class="form-group">
                            <label for="reservation-time">予約時刻</label>
                            <select id="reservation-time" name="reservation_time" required>
                                @for ($i = 0; $i < 24; $i++)
                                    @for ($j = 0; $j < 60; $j += 30)
                                        <option value="{{ sprintf('%02d:%02d', $i, $j) }}">
                                            {{ sprintf('%02d:%02d', $i, $j) }}</option>
                                    @endfor
                                @endfor
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="party-size">人数</label>
                            <input type="number" id="party-size" name="party_size" required min="1" step="1">
                        </div>

                        <div class="selected-conditions">
                            <!-- 選択された条件を表示 -->
                            <p>選択条件: <span id="selected-conditions">-</span></p>
                        </div>

                        <button type="submit">予約する</button>
                    </form>
                </div>


                <p>評価と口コミ</p>
                <a href="{{ route('reviews.showForm', ['shop_id' => $shop->id]) }}">レビューを投稿する</a>
                {{-- <div class="form-container">
                    <!-- レビューフォーム -->
                    <form action="{{ route('reviews.submit', ['shop_id' => $shop->id]) }}" method="POST">
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

                        <input type="hidden" name="shop_id" value="{{ $shop->id }}">

                        <button type="submit">評価とコメントを投稿</button>
                    </form>
                </div> --}}
            </div>
        </div>
    </div>
    <script>
        // HTMLのフォーム要素を取得
        const reservationDateInput = document.getElementById('reservation-date');
        const reservationTimeInput = document.getElementById('reservation-time');
        const partySizeInput = document.getElementById('party-size');

        // 表示要素を取得
        const selectedConditions = document.getElementById('selected-conditions');

        // フォームの値が変更されたときに呼び出される関数
        function updateSelectedConditions() {
            const date = reservationDateInput.value;
            const time = reservationTimeInput.value;
            const partySize = partySizeInput.value;

            // 選択された条件を表示要素に設定
            selectedConditions.innerText = `予約日: ${date}, 時刻: ${time}, 人数: ${partySize}`;
        }

        // 各フォームフィールドの変更を監視
        reservationDateInput.addEventListener('change', updateSelectedConditions);
        reservationTimeInput.addEventListener('change', updateSelectedConditions);
        partySizeInput.addEventListener('input', updateSelectedConditions);
    </script>
    @push('styles')
        <link href="{{ asset('css/shops/detail.css') }}" rel="stylesheet">
    @endpush
@endsection
