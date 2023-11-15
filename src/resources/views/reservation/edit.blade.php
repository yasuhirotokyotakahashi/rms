@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>予約を編集</h1>
        <form action="{{ route('reservations.update', ['reservationId' => $reservation->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- 以下に編集フォームのフィールドを追加 -->

            <div class="form-group">
                <label for="date">予約日:</label>
                <input type="date" name="date" id="date" value="{{ $reservation->date }}">
            </div>

            <div class="form-group">
                <label for="time">予約時間:</label>
                <input type="time" name="time" id="time" value="{{ $reservation->time }}">
            </div>

            <div class="form-group">
                <label for="guests">ゲスト数:</label>
                <input type="number" name="guests" id="guests" value="{{ $reservation->guests }}">
            </div>

            <!-- 編集フォームのフィールドを追加 -->

            <button type="submit">更新</button>
        </form>
    </div>
@endsection
