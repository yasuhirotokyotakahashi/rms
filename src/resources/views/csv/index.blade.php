@extends('layouts.app')
@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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
