@extends('layouts.template')

@section('content')
    <div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-600">
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm text-gray-700">Daftar Riwayat Perbaikan yang terdaftar dalam sistem</span>
        </div>
        <hr class="border-black opacity-30 mt-4">

        <form id="filter-form" method="GET" class="flex flex-wrap gap-4 mb-4 mt-8">
            <!-- Filter Periode -->
            <div class="flex items-center gap-2">
                <label for="id_periode" class="text-sm font-medium text-gray-700">Filter Periode:</label>
                <select id="id_periode" name="id_periode" class="w-48 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                    <option value="">Semua Periode</option>
                    @foreach ($periode as $p)
                        <option value="{{ $p->id_periode }}" {{ request('id_periode') == $p->id_periode ? 'selected' : '' }}>
                            {{ $p->nama_periode }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Status -->
            <div class="flex items-center gap-2">
                <label for="status" class="text-sm font-medium text-gray-700">Filter Status:</label>
                <select id="status" name="status" class="w-48 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                    <option value="">Semua Status</option>
                    @foreach (\App\Http\Enums\Status::cases() as $status)
                        <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                            {{ $status->value }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        <div id="perbaikan-table-body">
            @include('sarpras.perbaikan.perbaikan_table', ['aduan' => $aduan])
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
            url: "{{ route('sarpras.perbaikan') }}",
            method: 'GET',
            data: {
                id_periode: $('#id_periode').val(),
                status: $('#status').val(),
                search: $('#search').val(),
                per_page: $('#per_page').val(),
                sort_column: $('#sort-column').val(),
                sort_direction: $('#sort-direction').val()
            },
            success: function (response) {
                $('#perbaikan-table-body').html(response.html);
            },
            error: function () {
                Swal.fire('Error', 'Gagal memuat data perbaikan', 'error');
            }
        });
    }

    $(document).ready(function () {
        // Event untuk filter periode
        $('#id_periode').on('change', function () {
            reloadData();
        });

        // Event untuk filter status
        $('#status').on('change', function () {
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