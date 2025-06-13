@extends('layouts.template')

@section('content')
<div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-600">
    <div class="flex items-center justify-between mb-4">
        <span class="text-sm text-gray-700">Daftar Kategori yang terdaftar dalam sistem</span>
        <div class="flex gap-2">
            <button onclick="modalAction('{{ route('admin.kategori.import') }}')" class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900 cursor-pointer">
                <i class="fas fa-file-import"></i> Import
            </button>
            <a href="{{ route('admin.kategori.export_excel') }}" class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                <i class="fas fa-file-excel"></i> Ekspor Excel
            </a>
            <a href="{{ route('admin.kategori.export_pdf') }}" class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                <i class="fas fa-file-pdf"></i> Ekspor PDF
            </a>
            <button onclick="modalAction('{{ route('admin.kategori.create') }}')" class="cursor-pointer bg-green-600 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-green-700">
                <i class="fas fa-plus"></i> Tambah
            </button>
        </div>
    </div>
    <hr class="border-black opacity-30 mt-4">

<div class="flex justify-between items-center mb-4 mt-4">
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
        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari kategori..." class="w-64 border border-gray-300 rounded-md shadow-sm sm:text-sm" />
    </div>
</div>

    <div id="kategori-table-body">
        @include('admin.kategori.kategori_table', ['kategori' => $kategori])
    </div>
</div>

<div id="myModal" class="fixed inset-0 z-50 hidden items-center justify-center backdrop-blur-sm bg-white/30"></div>

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
        url: "{{ route('admin.kategori') }}",
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
            $('#kategori-table-body').html(response.html);
        },
        error: function () {
            Swal.fire('Error!', 'Gagal memuat data kategori.', 'error');
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
