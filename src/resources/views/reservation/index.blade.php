@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>予約一覧</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>予約日時</th>
                    <th>人数</th>
                    <th>店舗名</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservations as $reservation)
                    <tr>
                        <td>{{ $reservation->date }} {{ $reservation->time }}</td>
                        <td>{{ $reservation->guests }}</td>
                        <td>{{ $reservation->shop->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
