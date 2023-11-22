@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
@endsection

@section('content')
    <div class="thank-you__content">
        <div class="thank-you-message">
            @if (auth()->check())
                <h1>
                    {{-- ログインしているユーザーの名前を表示 --}}
                    {{ auth()->user()->name }}様
                </h1>
                <h2>会員登録ありがとうございます。</h2>
                <p>ご登録いただいたアカウントでログインして、サービスをご利用ください。</p>
                <a href="{{ url('/') }}" class="btn btn-primary">サービスを開始する</a>
            @else
                <p>ログインしていません。</p>
            @endif

        </div>
    </div>
@endsection
