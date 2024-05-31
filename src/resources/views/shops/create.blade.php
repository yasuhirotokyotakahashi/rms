@extends('layouts.app')

@section('content')
    <!-- 店舗登録フォーム -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
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
