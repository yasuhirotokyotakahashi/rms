@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row">
            <!-- 左側の予約状況 -->
            <div class="card card-left">
                <div class="card-header">予約状況</div>
                <div class="card-body">
                    <!-- 予約変更成功メッセージを表示 -->
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <!-- 予約情報を表示 -->
                    <div class="card-row">
                        @foreach ($reservations as $reservation)
                            <div>
                                <p>{{ $reservation->shop->name }}</p>
                                <p>{{ $reservation->date }}</p>
                                <p>{{ $reservation->time }}</p>
                                <p>{{ $reservation->guests }}人</p>
                                <a href="{{ route('reservations.edit', ['reservationId' => $reservation->id]) }}">編集</a>
                                <form method="POST"
                                    action="{{ route('reservations.destroy', ['reservationId' => $reservation->id]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">削除</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- 右側のお気に入り店舗 -->
            <!-- 右側のコードは変更せず、お気に入り店舗情報の表示が続きます -->
            <div class="card card-right">
                <div class="card-header">お気に入り店舗</div>
                <div class="card-body">
                    <!-- お気に入り店舗情報を表示 -->
                    <div class="shop-list">
                        @foreach ($favoriteShops as $shop)
                            <div class="shop-item">
                                <div class="card">
                                    <div class="card__img">
                                        <img src="{{ asset('storage/' . $shop->image_path) }}" alt="{{ $shop->name }}">
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
                                                    <button type="submit">好き</button>
                                                </form>
                                            @else
                                                <form action="{{ route('favorites.store', $shop) }}" method="POST">
                                                    @csrf
                                                    <button type="submit">嫌い</button>
                                                </form>
                                            @endif
                                        @endif
                                        <a class="card__content-link"
                                            href="{{ route('shops.show', ['shop_id' => $shop->id]) }}">詳しく見る</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('styles')
        <link href="{{ asset('css/mypage.css') }}" rel="stylesheet">
    @endpush
    </div>
@endsection
