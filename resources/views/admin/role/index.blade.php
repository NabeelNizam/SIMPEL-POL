@extends('layouts.template')

@section('content')
<div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-600">
    <div class="flex items-center justify-between mb-4">
        <span class="text-sm text-gray-700">{{ $page->title }}</span>
        <div class="flex gap-2">
            <a href="{{ url('/user/export_excel') }}" class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                <i class="fas fa-file-excel"></i> Ekspor Excel
            </a>
            <a href="{{ url('/user/export_pdf') }}" class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                <i class="fas fa-file-pdf"></i> Ekspor PDF
            </a>
            <button onclick="modalAction('{{ route('admin.role.create_ajax') }}')" class="bg-green-600 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-green-700">
                <i class="fas fa-plus"></i> Tambah
            </button>
        </div>
    </div>
    <hr class="border-black opacity-30 mt-4">

    <form id="filter-form" onsubmit="return false" class="flex flex-col gap-4 mb-4 mt-8">
    <div class="flex justify-between items-center flex-wrap">
        <div>
            <label for="per_page" class="text-sm font-medium text-gray-700 mb-1">Show</label>
            <select id="per_page" name="per_page" class="border border-gray-300 rounded-md shadow-sm sm:text-sm">
                @foreach ([10, 25, 50, 100] as $length)
                    <option value="{{ $length }}" {{ request('per_page', 10) == $length ? 'selected' : '' }}>{{ $length }}</option>
                @endforeach
            </select>
            <span class="text-sm text-gray-700 mb-1">entries</span>
        </div>
        <div class="flex items-center gap-2">
            <label for="search" class="text-sm font-medium text-gray-700">Pencarian:</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari pengguna..." class="w-64 border border-gray-300 rounded-md shadow-sm sm:text-sm" />
        </div>
    </div>

    </form>


    <div id="role-table-body">
        @include('admin.role.role_table', ['roles' => $roles])
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
            url: "{{ route('admin.role') }}",
            method: "GET",
            data: {
                search: $('#search').val(),
                per_page: $('#per_page').val(),
            },
            success: function (response) {
                $('#role-table-body').html(response.html);
            },
            error: function () {
                Swal.fire('Error!', 'Gagal memuat data Role.', 'error');
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



