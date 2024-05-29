@extends('layouts.app')
@section('content')
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <form method="post" action="{{ route('csv.run') }}" enctype="multipart/form-data">
        @csrf
        <label for="csvFile">CSVファイル</label>
        <input type="file" name="csvFile" id="csvFile" />
        <button type="submit">送信</button>
    </form>
@endsection
