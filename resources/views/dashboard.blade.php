@extends('layouts.dashboard')

@section('content')

<div class="p-4">

    <h1 class="text-2xl font-bold text-gray-900">
        Dashboard Stockify
    </h1>

    <p class="mt-2 text-gray-600">
        Selamat datang, {{ auth()->user()->name }}.
    </p>

</div>

@endsection