@extends('layouts.template')

@section('content')
<div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-600">
    <div class="flex items-center justify-between mb-4">
        <span class="text-sm text-gray-700">Daftar Pengguna yang terdaftar dalam sistem</span>
        <div class="flex gap-2">
            <button onclick="modalAction('{{ route('admin.import_ajax') }}')" class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                <i class="fas fa-file-import"></i> Import
            </button>
            <a href="{{ url('/user/export_excel') }}" class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                <i class="fas fa-file-excel"></i> Ekspor Excel
            </a>
            <a href="{{ url('/user/export_pdf') }}" class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                <i class="fas fa-file-pdf"></i> Ekspor PDF
            </a>
            <button onclick="modalAction('{{ route('admin.create_ajax') }}')" class="bg-green-600 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-green-700">
                <i class="fas fa-plus"></i> Tambah
            </button>
        </div>
    </div>
    <hr class="border-black opacity-30 mt-4">
    {{-- Filter dan Search --}}
    <form id="filter-form" onsubmit="return false" class="flex flex-col gap-4 mb-4 mt-8">

    {{-- Baris 1: Filter --}}
    <div class="flex items-center gap-2">
        <label for="id_role" class="text-sm font-medium text-gray-700">Filter Role:</label>
        <select id="id_role" name="id_role" class="w-48 border border-gray-300 rounded-md shadow-sm sm:text-sm">
            <option value="">Semua Role</option>
            @foreach ($role as $r)
                <option value="{{ $r->id_role }}" {{ request('id_role') == $r->id_role ? 'selected' : '' }}>{{ $r->nama_role }}</option>
            @endforeach
        </select>
    </div>

    <div class="flex justify-between items-center flex-wrap">
        {{-- Search kiri --}}
        <div>
            <label for="per_page" class="text-sm font-medium text-gray-700 mb-1">Show</label>
            <select id="per_page" name="per_page" class="border border-gray-300 rounded-md shadow-sm sm:text-sm">
                @foreach ([10, 25, 50, 100] as $length)
                    <option value="{{ $length }}" {{ request('per_page', 10) == $length ? 'selected' : '' }}>{{ $length }}</option>
                @endforeach
            </select>
            <span class="text-sm text-gray-700 mb-1">entries</span>
        </div>

        {{-- Show Entries kanan --}}
        <div class="flex items-center gap-2">
            <label for="search" class="text-sm font-medium text-gray-700">Pencarian:</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari pengguna..." class="w-64 border border-gray-300 rounded-md shadow-sm sm:text-sm" />
        </div>
    </div>

</form>


    {{-- Komponen Tabel --}}
<div id="user-table-body">
    @include('admin.pengguna.user_table', ['users' => $users])
</div>

</div>

<div id="myModal" class="fixed inset-0 z-50 hidden items-center justify-center backdrop-blur-sm bg-white/30">

</div>

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
            url: "{{ route('admin.pengguna') }}",
            method: "GET",
            data: {
                id_role: $('#id_role').val(),
                search: $('#search').val(),
                per_page: $('#per_page').val(),
                sort_column: $('#sort-column').val(),
                sort_direction: $('#sort-direction').val()
            },
            success: function (response) {
                $('#user-table-body').html(response.html);
            },
            error: function () {
                Swal.fire('Error!', 'Gagal memuat data pengguna.', 'error');
            }
        });
    }

    $(document).ready(function () {
        // Event untuk filter role
        $('#id_role').on('change', function () {
            reloadData();
        });

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



