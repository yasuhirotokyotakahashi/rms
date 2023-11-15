@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">管理画面</div>

                    <div class="card-body">
                        <!-- メール送信フォーム -->
                        <form action="{{ route('sendMail') }}" method="POST">
                            @csrf

                            <button type="submit" class="btn btn-primary">
                                利用者にメールを送信
                            </button>
                        </form>

                        <!-- 予約情報一覧 -->
                        <h2>予約情報一覧</h2>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>予約ID</th>
                                    <th>ユーザー名</th>
                                    <th>店舗名</th>
                                    <th>日付</th>
                                    <th>時間</th>
                                    <th>ゲスト数</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reservations as $reservation)
                                    <tr>
                                        <td>{{ $reservation->id }}</td>
                                        <td>{{ $reservation->user->name }}</td>
                                        <td>{{ $reservation->date }}</td>
                                        <td>{{ $reservation->time }}</td>
                                        <td>{{ $reservation->guests }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- 更新情報一覧 -->
                        <h2>更新情報一覧</h2>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>更新ID</th>
                                    <th>タイトル</th>
                                    <th>内容</th>
                                    <th>更新日時</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($updates as $update)
                                    <tr>
                                        <td>{{ $update->id }}</td>
                                        <td>{{ $update->title }}</td>
                                        <td>{{ $update->content }}</td>
                                        <td>{{ $update->updated_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
