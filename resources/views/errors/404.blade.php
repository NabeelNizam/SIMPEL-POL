{{-- resources/views/errors/404.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen">
    <h1 class="font-extrabold text-7xl text-[#75757580]">404</h1>
    <img src="{{asset('img/notFound.png')}}" alt="Page Not Found" class="w-1/5 mx-auto" />
    <h2 class="font-extrabold text-2xl text-[#75757580]">Halaman tidak ditemukan...</h2>
</div>

@endsection
