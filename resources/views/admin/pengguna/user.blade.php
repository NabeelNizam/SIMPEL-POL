@extends('layouts.template')

@section('content')
<div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-600">
    <div class="flex items-center justify-between mb-4">
        <span class="text-sm text-gray-700">Daftar Pengguna yang terdaftar dalam sistem</span>
        <div class="flex gap-2">
            <button onclick="modalAction('{{ url('/user/import') }}')" class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                <i class="fas fa-file-import"></i> Import
            </button>
            <a href="{{ url('/user/export_excel') }}" class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                <i class="fas fa-file-excel"></i> Ekspor Excel
            </a>
            <a href="{{ url('/user/export_pdf') }}" class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                <i class="fas fa-file-pdf"></i> Ekspor PDF
            </a>
            <button onclick="modalAction('{{ url('/user/create_ajax') }}')" class="bg-green-600 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-green-700">
                <i class="fas fa-plus"></i> Tambah
            </button>
        </div>
    </div>

   {{-- Filter dan Search --}}
<div class="mb-4">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div class="flex flex-wrap items-end gap-4">
            <div>
                <label for="id_role" class="block text-sm font-medium text-gray-700 mb-1">Filter Berdasarkan Role:</label>
                <select id="id_role" name="id_role" class="block w-48 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Semua Role</option>
                    @foreach ($role as $item)
                        <option value="{{ $item->id_role }}">{{ $item->nama_role }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Custom Search Box --}}
        <div>
            <label for="table-search" class="block text-sm font-medium text-gray-700 mb-1">Pencarian:</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" id="table-search" class="block w-full pl-10 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Cari pengguna...">
            </div>
        </div>
    </div>

    {{-- Pindahkan ke sini: tepat setelah filter & search --}}
    <div id="custom-length-menu" class="mt-4"></div>
</div>
    

    {{-- Tabel Pengguna --}}
    <div class="border border-gray-200 rounded-lg overflow-hidden">
        <div class="overflow-x-auto" style="-ms-overflow-style: none; scrollbar-width: none;">
           <table id="table_user" class="min-w-full border border-gray-300">
            <thead>
                <tr class="bg-white-50 border-b border-gray-200 text-black-500">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-black-300">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-black-300">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-black-300">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-black-300">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-black-300">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                {{-- Baris data dirender otomatis oleh DataTables --}}
            </tbody>
        </table>
        </div>
    </div>
    <div id="custom-pagination-menu" class="mt-4"></div>
</div>

<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection


@push('css')
<style>
    /* Override warna latar belakang dan teks */
    table.dataTable tbody tr {
        background-color: white !important;
        color: black !important;
    }

    table.dataTable thead th {
        background-color: white !important;
        color: black !important;
    }

    /* Hapus background striping dari DataTables */
    table.dataTable.stripe tbody tr.odd {
        background-color: #f9fafb !important; /* warna abu muda */
    }

    table.dataTable.stripe tbody tr.even {
        background-color: white !important;
    }
    .length-section {
        display: none !important;
    }
</style>

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function () {
            $('#myModal').modal('show');
        });
    }

    var dataUser;
    $(document).ready(function () {
        dataUser = $('#table_user').DataTable({
            serverSide: true,
            processing: true,
            responsive: true,
            language: {
                search: "",
                searchPlaceholder: "Search...",
                lengthMenu: "_MENU_ data per halaman",
                paginate: {
                    previous: "<<<",
                    next: ">>>"
                },
                info: "Menampilkan _START_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 hingga 0 dari 0 data",
                infoFiltered: "(difilter dari _MAX_ total data)"
            },
             dom: "<'hidden'f><'overflow-x-auto't><'text-sm mt-4'<'info-section'i><'pagination-section'p><'length-section'l>>",
            ajax: {
                url: "{{ url('admin/list') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function (data) {
                    data.id_role = $('#id_role').val();
                }
            },
            columns: [
                {
                    data: "DT_RowIndex",
                    className: "px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-black-300",
                    orderable: false,
                    searchable: false
                },
                { 
                    data: "nama", 
                    className: "px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-black-300",
                },
                { 
                    data: "email", 
                    className: "px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-black-300",
                },
                { 
                    data: "role", 
                    className: "px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-black-300",
                    orderable: false, 
                    searchable: false 
                },
                { 
                    data: "aksi", 
                    className: "px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-black-300",
                    orderable: false, 
                    searchable: false 
                }
            ],
        });
        dataUser.on('draw', function () {
        // Pindahkan length menu ke lokasi yang kita tentukan
        $('#custom-length-menu').html($('.length-section').html());

        $('#custom-length-menu select')
            $('#custom-length-menu select')
            .attr('class', 'px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-white text-gray-700');

        // Gaya pagination
       $('#table_user_paginate')
        .attr('class', 'flex justify-center items-center mt-6 space-x-1');

        $('#table_user_paginate a')
            .attr('class', 'px-3 py-1 border border-gray-300 rounded-md text-sm bg-white text-blue-600 hover:bg-blue-50 transition');

        $('#table_user_paginate span.current')
            .attr('class', 'px-3 py-1 rounded-md text-sm bg-blue-600 text-white border border-blue-600');

    });





        $('#table-search').keyup(function () {
            dataUser.search($(this).val()).draw();
        });

        $('#id_role').on('change', function () {
            dataUser.ajax.reload();
        });

        $('#reset-filter').on('click', function () {
            $('#id_role').val('');
            $('#table-search').val('');
            dataUser.search('').draw();
            dataUser.ajax.reload();
        });

       
    });
</script>
@endpush
