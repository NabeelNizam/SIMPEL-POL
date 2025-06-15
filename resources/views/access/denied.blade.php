@extends('layouts.template')

@section('content')

<div class="flex items-center justify-center h-[500px] align-middle">
    <div class="rounded-lg max-w-4xl w-full text-center">
        <div class="mb-4">
            <svg class="mx-auto h-16 w-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Akses Dibatasi</h1>
        <p class="text-gray-600 mb-6 text-base">
          {!! $pesan !!}
        </p>
    </div>
</div>

@endsection