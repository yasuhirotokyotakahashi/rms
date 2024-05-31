@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <h2>店舗情報編集</h2>
        <form action="{{ route('shops.update', $shop->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">店舗名</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $shop->name }}">
            </div>
            <div class="form-group">
                <label for="genre">ジャンル</label>
                <input type="text" class="form-control" id="genre" name="genre" value="{{ $shop->genre }}">
            </div>
            <div class="form-group">
                <label for="address">住所</label>
                <input type="text" class="form-control" id="address" name="address" value="{{ $shop->address }}">
            </div>
            <!-- 他の店舗情報編集フォーム要素を追加 -->
            <button type="submit" class="btn btn-primary">保存</button>
        </form>
    </div>
@endsection
