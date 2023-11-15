<form method="POST" action="{{ route('reservation.store') }}">
    @csrf

    <div class="form-group">
        <label for="date">予約日</label>
        <input type="date" id="date" name="date" required>
    </div>

    <div class="form-group">
        <label for="time">予約時間</label>
        <input type="time" id="time" name="time" required>
    </div>

    <div class="form-group">
        <label for="guests">人数</label>
        <input type="number" id="guests" name="guests" required>
    </div>

    <!-- 他のフォームフィールド -->

    <button type="submit">予約する</button>
</form>
