<link rel="stylesheet" type="text/css" href="{{ asset('css/representative/index.css') }}">
<!-- 店舗情報の編集フォーム -->
<div class="shop-item">
    <div class="card">
        <div class="card__content">
            <h2 class="card__content-ttl">店舗情報の編集</h2>
            <!-- 店舗情報の編集フォーム -->
            <form
                action="{{ $reservations->isNotEmpty() ? route('update-shop', $reservations->first()->shop->id) : '#' }}"
                method="POST" enctype="multipart/form-data" class="shop-registration-form">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">店舗名:</label>
                    <input type="text" name="name" id="name"
                        value="{{ old('name', $reservations->first()->shop->name) }}" required>
                </div>

                <div class="form-group">
                    <label for="description">説明:</label>
                    <textarea name="description" id="description" required>{{ old('description', $reservations->first()->shop->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="image">新しい画像をアップロード:</label>
                    <input type="file" name="image" accept="image/*">
                </div>

                <div class="form-group">
                    <label>現在の画像:</label>
                    <img src="{{ asset('storage/' . $reservations->first()->shop->image_path) }}" alt="店舗画像"
                        style="max-width: 200px; max-height: 200px;">
                </div>

                <div class="form-group">
                    <label>画像プレビュー:</label>
                    <img id="image-preview" src="#" alt="画像プレビュー"
                        style="max-width: 200px; max-height: 200px; display: none;">
                </div>

                <button type="submit">更新</button>
                <a href="{{ route('representative.index') }}" class="back-button">戻る</a>
            </form>
        </div>
    </div>
</div>
