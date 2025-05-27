@extends('layouts.template')

@section('content')
    <div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-600">
        <div class="flex items-center justify-between">
            <span class="text-sm text-gray-700">Daftar Pengguna yang terdaftar dalam sistem</span>
            <div class="flex gap-2">
                <button class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                    <i class="fas fa-file-import"></i> Import
                </button>
                <button class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                    <i class="fas fa-file-excel"></i> Ekspor Excel
                </button>
                <button class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                    <i class="fas fa-file-pdf"></i> Ekspor PDF
                </button>
                <button
                    class="bg-green-600 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-green-700">
                    <i class="fas fa-plus"></i> Tambah
                </button>
                <button onclick="modalAction('/admin/show_profil')"
                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Lihat Profil
                </button>
            </div>
        </div>
    </div>
    

      

@endsection

