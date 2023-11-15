@extends('layouts.app')

@section('content')
    <h1>店舗検索結果</h1>

    <!-- 検索フォーム -->
    <form action="{{ route('shops.search') }}" method="GET">
        <div class="form-group">
            <label for="area">エリア:</label>
            <input type="text" name="area" id="area" value="{{ request('area') }}">
        </div>
        <div class="form-group">
            <label for="genre">ジャンル:</label>
            <input type="text" name="genre" id="genre" value="{{ request('genre') }}">
        </div>
        <div class="form-group">
            <label for="name">店名:</label>
            <input type="text" name="name" id="name" value="{{ request('name') }}">
        </div>
        <button type="submit">検索</button>
    </form>

    <div class="shop-list">
        @if ($results->isEmpty())
            <p>該当する店舗は見つかりませんでした。</p>
        @else
            <div class="shop-row">
                @foreach ($results as $shop)
                    <div class="shop-item">
                        <div class="card">
                            <div class="card__img">
                                <img src="{{ asset('storage/' . $shop->image_path) }}" alt="{{ $shop->name }}">
                            </div>
                            <div class="card__content">
                                <h2 class="card__content-ttl">{{ $shop->name }}</h2>
                                <div class="card__content-tag">{{ $shop->genre->name }}</div>
                                <div class="card__content-tag">{{ $shop->address->city }}</div>
                                <button class="heart-button">❤️</button>
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
