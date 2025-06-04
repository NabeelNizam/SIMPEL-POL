@extends('layouts.template')

@section('content')
<div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-600">
    <div class="flex items-center justify-between mb-4">
        <span class="text-sm text-gray-700">Daftar Fasilitas yang terdaftar dalam sistem</span>
        <div class="flex gap-2">
            <button onclick="modalAction('{{ route('admin.fasilitas.import_ajax') }}')" class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900 cursor-pointer">
                <i class="fas fa-file-import"></i> Import
            </button>
            <a href="#" class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                <i class="fas fa-file-excel"></i> Ekspor Excel
            </a>
            <a href="#" class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                <i class="fas fa-file-pdf"></i> Ekspor PDF
            </a>
            <button onclick="modalAction('{{ route('admin.fasilitas.create') }}')" class="cursor-pointer bg-green-600 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-green-700">
                <i class="fas fa-plus"></i> Tambah
            </button>
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

    <!-- Filter Gedung -->
    <div class="flex items-center gap-2">
        <label for="id_gedung" class="text-sm font-medium text-gray-700">Filter Gedung:</label>
        <select id="id_gedung" name="id_gedung" class="w-48 border border-gray-300 rounded-md shadow-sm sm:text-sm">
            <option value="">Semua Gedung</option>
            @foreach ($gedung as $g)
                <option value="{{ $g->id_gedung }}" {{ request('id_gedung') == $g->id_gedung ? 'selected' : '' }}>{{ $g->nama_gedung }}</option>
            @endforeach
        </select>
    </div>

    <!-- Filter Kondisi -->
    <div class="flex items-center gap-2">
        <label for="kondisi" class="text-sm font-medium text-gray-700">Filter Kondisi:</label>
        <select id="kondisi" name="kondisi" class="w-48 border border-gray-300 rounded-md shadow-sm sm:text-sm">
            <option value="">Semua Kondisi</option>
            @foreach ($kondisi as $k)
                <option value="{{ $k->value }}" {{ request('kondisi') == $k->value ? 'selected' : '' }}>{{ $k->value }}</option>
            @endforeach
        </select>
    </div>

    <!-- Tombol Submit -->
    <div class="flex items-center gap-2">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-700">Terapkan Filter</button>
    </div>
</form>

<div class="flex justify-between items-center mb-4">
    <!-- Pagination -->
    <div class="flex items-center gap-2">
        <label for="per_page" class="text-sm font-medium text-gray-700">Show:</label>
        <select id="per_page" name="per_page" class="border border-gray-300 rounded-md shadow-sm sm:text-sm">
            @foreach ([10, 25, 50, 100] as $length)
                <option value="{{ $length }}" {{ request('per_page', 10) == $length ? 'selected' : '' }}>{{ $length }}</option>
            @endforeach
        </select>
        <span class="text-sm text-gray-700">entries</span>
    </div>

    <!-- Pencarian -->
    <div class="flex items-center gap-2">
        <label for="search" class="text-sm font-medium text-gray-700">Pencarian:</label>
        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari fasilitas..." class="w-64 border border-gray-300 rounded-md shadow-sm sm:text-sm" />
    </div>
</div>

    <div id="fasilitas-table-body">
        @include('admin.fasilitas.fasilitas_table', ['fasilitas' => $fasilitas])
    </div>
</div>

<div id="myModal" class="fixed inset-0 z-50 hidden items-center justify-center backdrop-blur-sm bg-white/30"></div>

@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session('success') }}',
    });
</script>
@endif

@if ($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Validasi Gagal',
        html: `{!! implode('<br>', $errors->all()) !!}`,
    });
</script>
@endif


@endsection

@push('js')
<script>
    
    function modalAction(url = '') {
        $.get(url, function(response) {
            $('#myModal').html(response).removeClass('hidden').addClass('flex');
        });
    }

    // Untuk menutup modal
    $(document).on('click', '#modal-close', function () {
        $('#myModal').addClass('hidden').removeClass('flex').html('');
    });

    function reloadData() {
    $.ajax({
        url: "{{ route('admin.fasilitas') }}",
        method: "GET",
        data: {
            search: $('#search').val(),
            per_page: $('#per_page').val(),
            id_kategori: $('#id_kategori').val(),
            id_gedung: $('#id_gedung').val(),
            kondisi: $('#kondisi').val(),
            sort_column: $('#sort-column').val(),
            sort_direction: $('#sort-direction').val()
        },
        success: function (response) {
            $('#fasilitas-table-body').html(response.html);
        },
        error: function () {
            Swal.fire('Error!', 'Gagal memuat data fasilitas.', 'error');
        }
    });
}

    $(document).ready(function () {
        // Event untuk jumlah data per halaman
        $('#per_page').on('change', function () {
            reloadData();
        });

        // Event untuk pencarian (dengan debounce)
        let debounceTimer;
        $('#search').on('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function () {
                reloadData();
            }, 300);
        });

        // Event untuk sorting jika ada
        $('#sort-column, #sort-direction').on('change', function () {
            reloadData();
        });
    });
</script>
@endpush