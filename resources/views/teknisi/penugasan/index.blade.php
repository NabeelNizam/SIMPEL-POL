@extends('layouts.template')

@section('content')
<div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-600">
    <div class="flex items-center justify-between mb-4">
        <span class="text-sm text-gray-700">Daftar Penugasan</span>
        <div class="flex gap-2">
            <a href="{{ route('teknisi.perbaikan.export_excel') }}" class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                <i class="fas fa-file-excel"></i> Ekspor Excel
            </a>
            <a href="{{ route('teknisi.perbaikan.export_pdf') }}" class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                <i class="fas fa-file-pdf"></i> Ekspor PDF
            </a>
        </div>
    </div>
    <hr class="border-black opacity-30 mt-4">

   <!-- Filter dan Pencarian -->
    <form id="filter-form" onsubmit="return false" class="flex flex-wrap justify-between items-center gap-4 mb-4 mt-8">
        <!-- Filter Periode -->
        <div class="flex items-center gap-2">
            <label for="id_periode" class="text-sm font-medium text-gray-700">Filter Periode:</label>
            <select id="id_periode" name="id_periode" class="w-48 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                <option value="">Semua Periode</option>
                @foreach ($periode as $p)
                    <option value="{{ $p->id_periode }}" {{ request('id_periode') == $p->id_periode ? 'selected' : '' }}>
                        {{ $p->kode_periode }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Input Pencarian -->
        <div class="flex items-center gap-2">
            <label for="search" class="text-sm font-medium text-gray-700">Search:</label>
            <input type="text" id="search" name="search" placeholder="Cari fasilitas..."
                class="border border-gray-300 rounded-md shadow-sm sm:text-sm" value="{{ request('search') }}">
        </div>
    </form>

    <div id="penugasan-container">
    @include('teknisi.penugasan.penugasan_table', ['penugasan' => $penugasan])
    </div>

    <div class="mt-4">
    {{ $penugasan->links() }}
</div>
</div>

<div id="myModal" class="fixed inset-0 z-50 hidden items-center justify-center backdrop-blur-sm bg-white/30"></div>
@endsection

@push('js')
<script>
    function reloadData() {
        $.ajax({
            url: "{{ route('teknisi.penugasan') }}",
            method: "GET",
            data: {
                search: $('#search').val(),
                id_periode: $('#id_periode').val()
            },
            success: function(response) {
                $('#penugasan-container').html(response.html);
            },
            error: function() {
                Swal.fire('Error!', 'Gagal memuat data penugasan.', 'error');
            }
        });
    }

    $(document).ready(function() {
        // Event untuk filter periode
        $('#id_periode').on('change', function() {
            reloadData();
        });

        // Event untuk pencarian
        $('#search').on('input', function() {
            reloadData();
        });
    });
</script>
@endpush