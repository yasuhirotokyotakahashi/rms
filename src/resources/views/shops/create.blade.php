@extends('layouts.app')

@section('content')
    <!-- 店舗登録フォーム -->
    <div class="shop-item">
        <div class="card">
            <div class="card__content">
                <h2 class="card__content-ttl">新しい店舗を登録</h2>
                <!-- 店舗登録フォーム -->
                <form action="{{ route('shops.store') }}" method="POST" enctype="multipart/form-data"
                    class="shop-registration-form">
                    @csrf

                    <div class="form-group">
                        <label for="name">店舗名:</label>
                        <input type="text" name="name" id="name" required>
                    </div>

                    <div class="form-group">
                        <label for="description">説明:</label>
                        <textarea name="description" id="description" required></textarea>
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

                    <button type="submit">登録</button>
                </form>
            </div>
        </div>
    </div>
    <!-- 店舗予約情報表示 -->
    <div class="shop-item">
        <div class="card">
            <div class="card__content">
                <h2 class="card__content-ttl">現在の予約情報</h2>

                @if ($reservations->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>店舗名</th>
                                <th>予約日</th>
                                <th>予約時間</th>
                                <th>予約者</th>
                                <th>連絡先</th>
                                <th>ゲスト数</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reservations as $reservation)
                                <tr>
                                    <td>{{ $reservation->shop->name }}</td>
                                    <td>{{ $reservation->date }}</td>
                                    <td>{{ $reservation->time }}</td>
                                    <td>{{ $reservation->user->name }}</td>
                                    <td>{{ $reservation->user->email }}</td>
                                    <td>{{ $reservation->guests }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>現在、予約情報はありません。</p>
                @endif
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // 画像アップロード時にプレビューを表示
        document.querySelector('input[type="file"]').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgPreview = document.getElementById('image-preview');
                    imgPreview.src = e.target.result;
                    imgPreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
    @push('styles')
        <link href="{{ asset('css/shops/create.css') }}" rel="stylesheet">
    @endpush
@endsection
