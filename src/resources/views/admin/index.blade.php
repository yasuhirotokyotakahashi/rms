@extends('layouts.app')

@section('content')
    <nav class="admin-nav">
        <ul>
            <li><a href="{{ route('admin.index') }}">Home</a></li>
            <li><a href="{{ route('showAssignShopRoleForm') }}">Assign Role</a></li>
            <li><a href="{{ route('shops.create') }}">Create Shop</a></li>
            <li><a href="{{ route('csv.csv') }}">CSVインポート</a></li>
        </ul>
    </nav>
    @push('styles')
        <link href="{{ asset('css/admin/index.css') }}" rel="stylesheet">
    @endpush
@endsection
