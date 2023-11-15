<form action="{{ route('reviews.store') }}" method="POST">
    @csrf
    <label for="rating">評価（5段階評価）</label>
    <input type="number" name="rating" min="1" max="5" required>

    <label for="comment">コメント</label>
    <textarea name="comment"></textarea>

    <button type="submit">評価を投稿</button>
</form>
