@extends('layouts.app')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="shop-detail-container">
        <div class="shop-detail-left">
            <div class="shop-detail-image">
                @if (strpos($shop->image_path, 'http') === 0)
                    <!-- URLの場合 -->
                    <img src="{{ $shop->image_path }}" alt="{{ $shop->name }}">
                @else
                    <!-- ローカルの場合 -->
                    <img src="{{ asset('storage/' . $shop->image_path) }}" alt="{{ $shop->name }}">
                @endif
            </div>
            <div class="shop-detail-info">
                <p class="shop-detail-address">{{ $shop->address->city }}</p>
                <p class="shop-detail-genre">{{ $shop->genre->name }}</p>
                <h2>{{ $shop->name }}</h2>
                <p>{{ $shop->description }}</p>
                <!-- お気に入りボタン -->
                @if (Auth::check())
                    <div class="favorite-button">
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
                    </div>
                @endif
            </div>
            <!-- レビュー情報を表示 -->
            <div class="shop-reviews">
                <h2>レビュー</h2>
                @if ($reviews->isEmpty())
                    <p>まだレビューがありません。</p>
                @else
                    <ul class="review-list">
                        @foreach ($reviews as $review)
                            <li class="review-item">
                                <div class="review-header">
                                    <div class="rating-stars">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <span class="star {{ $i <= $review->rating ? 'filled' : '' }}">&#9733;</span>
                                        @endfor
                                    </div>
                                    @auth
                                        @if ($review->user_id === auth()->user()->id || auth()->user()->roles()->where('role_id', 1)->exists())
                                            <div class="review-actions">
                                                @if ($review->user_id === auth()->user()->id)
                                                    <a href="{{ route('reviews.edit', ['review_id' => $review->id]) }}">編集</a>
                                                @endif
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
                <div class="form-container">
                    <h2>予約フォーム</h2>
                    <form action="{{ route('reservations.store') }}" method="POST">
                        @csrf
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
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
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
                            <p>選択条件: <span id="selected-conditions">-</span></p>
                        </div>
                        <button type="submit">予約する</button>
                    </form>
                </div>
                @if (auth()->check() &&
                        !auth()->user()->roles()->whereIn('role_id', [1, 2])->exists())
                    <div class="review-form">
                        <h2>評価と口コミ</h2>
                        <a href="{{ route('reviews.index', ['shop_id' => $shop->id]) }}">レビューを投稿する</a>
                    </div>
                @endif
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
