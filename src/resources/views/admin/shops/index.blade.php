<link rel="stylesheet" type="text/css" href="{{ asset('css/representative/index.css') }}">
<!-- 店舗情報の表示フォーム -->
<div class="shop-item">
    <div class="card">
        <div class="card__content">
            <!-- 編集ボタン -->
            @if ($reservations->isNotEmpty() && $reservations->first()->shop)
                <a href="{{ route('representative.edit', $reservations->first()->shop->id) }}" class="edit-button">編集</a>
            @else
                <a href="#" class="edit-button" onclick="event.preventDefault()">編集</a>
            @endif
            <h2 class="card__content-ttl">店舗情報</h2>

            @if ($reservations->isNotEmpty() && $reservations->first()->shop)
                <div class="form-group">
                    <label for="name">店舗名:</label>
                    <p>{{ $reservations->first()->shop->name }}</p>
                </div>

                <div class="form-group">
                    <label for="description">説明:</label>
                    <p>{{ $reservations->first()->shop->description }}</p>
                </div>

                <div class="form-group">
                    <label>画像:</label>
                    <img src="{{ asset('storage/' . $reservations->first()->shop->image_path) }}" alt="店舗画像"
                        style="max-width: 200px; max-height: 200px;">
                </div>

                <!-- 予約情報 -->
                <div class="form-group">
                    <label for="reservations">予約情報:</label>
                    <ul>
                        @foreach ($reservations as $reservation)
                            <li>
                                <a href="{{ route('reservations.show', $reservation->id) }}">
                                    {{ $reservation->user->name }} - {{ $reservation->date }} - {{ $reservation->time }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="form-group">
                    <p>予約情報がありません。</p>
                </div>
            @endif
        </div>
    </div>
</div>
