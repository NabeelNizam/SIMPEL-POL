@extends('layouts.template')

@section('content')
    <div class="w-full max-w-9xl bg-white rounded-lg shadow-md overflow-hidden">
        {{-- Container kuning --}}
        <div class="relative">
            {{-- Garis kuning di bawah kiri --}}
            <div class="absolute top-0 left-0 bottom-0 w-2.5 rounded-tl-lg rounded-bl-lg bg-yellow-500 z-0"></div>

            {{-- Header gradien biru menimpa div kuning --}}
            <div
                class="bg-gradient-to-r from-blue-600 via-blue-800 to-blue-900 p-8 shadow-md p-8 flex items-center text-white rounded-tr-lg relative z-10 ml-2.5">
                <div
                    class="bg-white bg-opacity-30 border-4 border-white rounded-full p-0 w-50 h-50 flex items-center justify-center mr-6 flex-shrink-0 overflow-hidden">
                    <img src="{{ asset($profile->foto_profil ? $profile->foto_profil : 'img/profiles.svg') }}"
                        alt="photo profil" class="object-cover w-full h-full">
                </div>
                <div>
                    <div class="text-2xl font-bold">{{ $profile->nama }}</div>
                    <div class="text-lg opacity-80">{{ $profile->role->nama_role }}</div>
                </div>
            </div>
        </div>

        <div class="px-8 py-12 grid grid-cols-1 md:grid-cols-3 gap-y-6 gap-x-8">
            <div>
                <div class="text-gray-600 text-sm mb-1">Email</div>
                <div class="text-gray-800 font-medium">{{ $profile->email }}</div>
            </div>
            <div>
                <div class="text-gray-600 text-sm mb-1">{{isset(auth()->user()->pegawai->nip) ? "NIP" : 'NIM'}}</div>
                <div class="text-gray-800 font-medium">{{ $identifier }}</div>
            </div>
            <div>
                <div class="text-gray-600 text-sm mb-1">Jurusan</div>
                <div class="text-gray-800 font-medium">{{ $profile->jurusan->nama_jurusan }}</div>
            </div>
            <div>
                <div class="text-gray-600 text-sm mb-1">Username</div>
                <div class="text-gray-800 font-medium">{{ $profile->username }}</div>
            </div>
            <div>
                <div class="text-gray-600 text-sm mb-1">No. Telepon</div>
                <div class="text-gray-800 font-medium">{{ $profile->no_hp }}</div>
            </div>
        </div>
    </div>
    <div id="myModal" class="fixed inset-0 z-50 hidden items-center justify-center backdrop-blur-sm bg-white/30"></div>
@endsection

@push('css')
    <style>
        /* Custom gradient untuk header */
        .header-gradient {
            background: linear-gradient(to right, #1D57D8, #1642A5, #0F2E72);
        }
    </style>
@endpush
