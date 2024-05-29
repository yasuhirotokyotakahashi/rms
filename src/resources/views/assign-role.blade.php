@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <h1>全てのユーザー一覧</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>ユーザー名</th>
                    <th>店舗名</th>
                    <th>Email</th>
                    <th>役割</th>
                    <th>アクション</th> <!-- 削除ボタンを追加 -->
                </tr>
            </thead>
            <tbody>
                @foreach ($allUsers as $user)
                    <tr>
                        <td>{{ $user->user->name }}</td>
                        <td>
                            @if ($user->role->name === 'Admin')
                                管理者
                            @elseif ($user->shop)
                                {{ $user->shop->name }}
                            @else
                                未所属
                            @endif
                        </td>
                        <td>{{ $user->user->email }}</td>
                        <td>{{ $user->role->name }}</td>
                        <td>
                            <form action="{{ route('unassignRoleFromUser') }}" method="POST">
                                @csrf
                                <input type="hidden" name="userId" value="{{ $user->user->id }}">
                                <input type="hidden" name="roleId" value="{{ $user->role->id }}">
                                <button type="submit" class="btn btn-danger">削除</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="container">
        <h1>店舗代表者任命</h1>



        <form action="{{ route('assignRole') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="userId">ユーザーを選択:</label>
                <select name="userId" id="userId" class="form-control">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="shopId">店舗を選択:</label>
                <select name="shopId" id="shopId" class="form-control">
                    @foreach ($shops as $shop)
                        <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="roleId">役割を選択:</label>
                <select name="roleId" id="roleId" class="form-control">
                    <option value="{{ $representativeRoleId }}">店舗代表者</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">店舗代表者権限を与える</button>
        </form>
    </div>

    <div>
        <form action="{{ route('sendNotification') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="user_id">ユーザーを選択:</label>
                <select name="user_id" id="user_id" class="form-control">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">お知らせメールを送信</button>
        </form>
    </div>

    <div class="container">
        <h1>全ての店舗の予約情報</h1>

        @forelse ($allReservations as $reservation)
            <table class="table">
                <thead>
                    <tr>
                        <th>店舗名</th>
                        <th>ユーザー名</th>
                        <th>予約日</th>
                        <th>予約時間</th>
                        <th>ゲスト数</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $reservation->shop->name }}</td>
                        <td>{{ $reservation->user->name }}</td>
                        <td>{{ $reservation->date }}</td>
                        <td>{{ $reservation->time }}</td>
                        <td>{{ $reservation->guests }}人</td>
                    </tr>
                </tbody>
            </table>
        @empty
            <p>予約情報はありません。</p>
        @endforelse
    </div>


    @push('styles')
        <link href="{{ asset('css/assign-shop-role.css') }}" rel="stylesheet">
    @endpush
@endsection
