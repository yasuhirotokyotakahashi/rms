@extends('layouts.app')

@section('content')
    <nav class="representative-nav">
        <ul>
            <li><a href="{{ route('representative.index') }}">Home</a></li>
            <li><a href="{{ route('shops.create') }}">Create Shop</a></li>
            <li><a href="{{ route('representative.info') }}">店舗情報画面へ</a></li>
        </ul>
    </nav>
    @push('styles')
        <link href="{{ asset('css/representative/index.css') }}" rel="stylesheet">
    @endpush
@endsection
