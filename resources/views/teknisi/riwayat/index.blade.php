@extends('layouts.template')

@section('content')
<div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-600">
    <div class="flex items-center justify-between mb-4">
        <span class="text-sm text-gray-700">Daftar Aduan dengan Status Selesai</span>
        <div class="flex gap-2">
            <a href="{{route('teknisi.perbaikan.export_excel')}}" class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                <i class="fas fa-file-excel"></i> Ekspor Excel
            </a>
            <a href="{{route('teknisi.perbaikan.export_pdf')}}" class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                <i class="fas fa-file-pdf"></i> Ekspor PDF
            </a>
        </div>
    </div>
    <hr class="border-black opacity-30 mt-4">

    <form id="filter-form" method="GET" class="flex flex-wrap gap-4 mb-4 mt-8">
        <!-- Filter Kategori -->
        <div class="flex items-center gap-2">
            <label for="id_kategori" class="text-sm font-medium text-gray-700">Filter Kategori:</label>
            <select id="id_kategori" name="id_kategori" class="w-48 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                <option value="">Semua Kategori</option>
                @foreach ($kategori as $k)
                    <option value="{{ $k->id_kategori }}" {{ request('id_kategori') == $k->id_kategori ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                @endforeach
            </select>
        </div>

        <!-- Filter Prioritas -->
        <div class="flex items-center gap-2">
            <label for="id_prioritas" class="text-sm font-medium text-gray-700">Filter Prioritas:</label>
            <select id="id_prioritas" name="id_prioritas" class="w-48 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                <option value="">Semua Prioritas</option>
                @foreach ($prioritas as $p)
                    <option value="{{ $p->id_prioritas }}" {{ request('id_prioritas') == $p->id_prioritas ? 'selected' : '' }}>{{ $p->nama_prioritas }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tombol Submit -->
        <div class="flex items-center gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-700">Terapkan Filter</button>
        </div>
    </form>

    <div id="aduan-table-body">
        @include('teknisi.riwayat.riwayat_table', ['aduan' => $aduan])
    </div>
</div>
@endsection
