@extends('layouts.app') <!-- あなたのテンプレートに合わせて変更してください -->

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">予約完了</div>
                    <div class="card-body">
                        <p>予約ありがとうございます。</p>
                        <a href="{{ route('shops.index') }}" class="btn btn-primary">戻る</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('styles')
        <link href="{{ asset('css/reservation/thanks.css') }}" rel="stylesheet">
    @endpush
@endsection
