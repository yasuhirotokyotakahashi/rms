@extends('layouts.app')

@section('content')
    @auth
        <p class="welcome-message">{{ Auth::user()->name }} 様、ようこそ！</p>
    @endauth

    <div class="container shop-list"> <!-- ここにcontainerクラスを追加 -->
        @if ($shops->isEmpty())
            <p>該当する店舗は見つかりませんでした。</p>
        @else
            <div class="shop-row">
                @foreach ($shops as $shop)
                    <div class="shop-item">
                        <div class="card">
                            <div class="card__img">
                                @if (strpos($shop->image_path, 'http') === 0)
                                    <!-- URLの場合 -->
                                    <img src="{{ $shop->image_path }}" alt="{{ $shop->name }}">
                                @elseif (strpos($shop->image_path, 'images') === 0)
                                    <!-- 直接publicディレクトリに保存された画像の場合 -->
                                    <img src="{{ asset($shop->image_path) }}" alt="{{ $shop->name }}">
                                @else
                                    <!-- ローカルの場合 -->
                                    <img src="{{ asset('storage/' . $shop->image_path) }}" alt="{{ $shop->name }}">
                                @endif
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
                                <a class="card__content-link"
                                    href="{{ route('shops.show', ['shop_id' => $shop->id]) }}">詳しく見る</a>
                            </div>
                        </div>
                    </div>
                    @if ($loop->iteration % 4 == 0)
            </div>
            <div class="shop-row">
        @endif
        @endforeach
    </div>
    @endif
    </div>

    @push('styles')
        <link href="{{ asset('css/shops/index.css') }}" rel="stylesheet">
    @endpush
@endsection
