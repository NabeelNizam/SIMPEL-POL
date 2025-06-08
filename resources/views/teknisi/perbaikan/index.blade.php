@extends('layouts.template')

@section('content')
    <div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-600">
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm text-gray-700">Daftar penugasan perbaikan fasilitas yang terdaftar dalam sistem</span>
            <div class="flex gap-2">
                <a href="{{ route('teknisi.perbaikan.export_excel') }}"
                    class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                    <i class="fas fa-file-excel"></i> Ekspor Excel
                </a>
                <a href="{{ route('teknisi.perbaikan.export_pdf') }}"
                    class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                    <i class="fas fa-file-pdf"></i> Ekspor PDF
                </a>
            </div>
        </div>
        <hr class="border-black opacity-30 mt-4">

        <form id="filter-form" onsubmit="return false" class="flex flex-col gap-4 mb-4 mt-8">
            <!-- Filter Periode -->
            <span class="flex items-center gap-2">
                <div class="flex items-center gap-2">
                    <label for="id_periode" class="text-sm font-medium text-gray-700">Filter Periode:</label>
                    <select id="id_periode" name="id_periode"
                        class="w-48 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                        <option value="">Semua Periode</option>
                        @foreach ($periode as $p)
                            <option value="{{ $p->id_periode }}"
                                {{ request('id_periode') == $p->id_periode ? 'selected' : '' }}>{{ $p->kode_periode }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <label for="id_status" class="text-sm font-medium text-gray-700">Filter Status:</label>
                    <select id="id_status" name="id_status"
                        class="w-48 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                        <option value="">Semua Status</option>
                        @foreach ($status as $item)
                            <option value="{{ $item }}" {{ request('id_status') == $item ? 'selected' : '' }}>
                                {{ $item->label() }}</option>
                        @endforeach
                    </select>
                </div>
            </span>
            <span class="flex justify-between items-center flex-wrap">
                <div>
                    <label for="per_page" class="text-sm font-medium text-gray-700 mb-1">Show</label>
                    <select id="per_page" name="per_page" class="border border-gray-300 rounded-md shadow-sm sm:text-sm">
                        @foreach ([10, 25, 50, 100] as $length)
                            <option value="{{ $length }}" {{ request('per_page', 10) == $length ? 'selected' : '' }}>
                                {{ $length }}</option>
                        @endforeach
                    </select>
                    <span class="text-sm text-gray-700 mb-1">entries</span>
                </div>
                <div class="flex items-center gap-2">
                    <label for="search" class="text-sm font-medium text-gray-700">Pencarian:</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Cari pengguna..."
                        class="w-64 border border-gray-300 rounded-md shadow-sm sm:text-sm" />
                </div>
            </span>
        </form>

    <div id="perbaikan-table-body">
        @include('teknisi.perbaikan.perbaikan_table',compact('perbaikan'))
    </div>
</div>

        <div id="myModal" class="fixed inset-0 z-50 hidden items-center justify-center backdrop-blur-sm bg-white/30">
        </div>
    @endsection


    @push('js')
        <script>
            // Function to open modal with content from URL
            function modalAction(url = '') {
                $.get(url, function(response) {
                    $('#myModal').html(response).removeClass('hidden').addClass('flex');
                });
            }

            // Event listener for modal close button
            $(document).on('click', '#modal-close', function() {
                $('#myModal').addClass('hidden').removeClass('flex').html('');
            });

            // Function to reload data based on filters and search
            function reloadData() {
                $.ajax({
                    url: {{ route('teknisi.perbaikan') }},
                    method: "GET",
                    data: {
                        search: $('#search').val(),
                        id_periode: $('#id_periode').val(),
                        per_page: $('#per_page').val(),
                        sort_column: $('#sort-column').val(),
                        sort_direction: $('#sort-direction').val()
                    },
                    success: function(response) {
                        $('#aduan-table-body').html(response.html);
                    },
                    error: function() {
                        Swal.fire('Error!', 'Gagal memuat data riwayat.', 'error');
                    }
                });
            }

            $(document).ready(function() {
                // Event untuk filter
                $('#id_kategori, #id_prioritas, #id_periode').on('change', function() {
                    reloadData();
                });

                // Event untuk pencarian
                $('#search').on('input', function() {
                    reloadData();
                });

                // Event untuk jumlah data per halaman
                $('#per_page').on('change', function() {
                    reloadData();
                });

                // Event untuk sorting
                $('#sort-column, #sort-direction').on('change', function() {
                    reloadData();
                });
            });
        </script>
    @endpush
