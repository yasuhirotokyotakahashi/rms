<link rel="stylesheet" type="text/css" href="{{ asset('css/admin/qr-show.css') }}">
<h2>予約詳細</h2>
<p>ユーザー名: {{ $reservation->user->name }}</p>
<p>予約日時: {{ $reservation->date }} - {{ $reservation->time }}</p>
<p>ゲスト数: {{ $reservation->guests }}</p>
{{-- 他の予約情報を表示するコードを追加 --}}

<!-- QRコードの表示 -->
<img src="{{ $qrCode }}" alt="Reservation QR Code">

<!-- 他の予約詳細情報の表示 -->
