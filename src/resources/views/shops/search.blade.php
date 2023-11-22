@extends('layouts.app')

@section('content')
    <h1>店舗検索結果</h1>

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
