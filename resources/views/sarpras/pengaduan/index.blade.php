@extends('layouts.template')

@section('content')
    <div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-600">
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm text-gray-700">Daftar Pengaduan yang terdaftar dalam sistem</span>
            {{-- <div class="flex gap-2">
                <a href="{{route('sarpras.pengaduan.export_excel')}}"
                    class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                    <i class="fas fa-file-excel"></i> Ekspor Excel
                </a>
                <a href="{{route('sarpras.pengaduan.export_pdf')}}"
                    class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                    <i class="fas fa-file-pdf"></i> Ekspor PDF
                </a>
            </div> --}}
        </div>
        <hr class="border-black opacity-30 mt-4">

        {{-- <form id="filter-form" method="GET" class="flex flex-wrap gap-4 mb-4 mt-8">
            <!-- Filter Periode -->
            <div class="flex items-center gap-2">
                <label for="id_periode" class="text-sm font-medium text-gray-700">Filter Periode:</label>
                <select id="id_periode" name="id_periode"
                    class="w-48 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                    <option value="">Semua Periode</option>
                    @foreach ($periode as $p)
                    <option value="{{ $p->id_periode }}" {{ request('id_periode')==$p->id_periode ? 'selected' : '' }}>
                        Periode {{ $p->kode_periode }}
                    </option>
                    @endforeach
                </select>
            </div>
        </form> --}}

        <div class="flex justify-between items-center mb-4">
            <!-- Pencarian -->
            <div class="flex items-center gap-2 mt-4">
                <label for="search" class="text-sm font-medium text-gray-700">Pencarian: {{$pelapor}}</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari Fasilitas..."
                    class="w-64 border border-gray-300 rounded-md shadow-sm sm:text-sm" />
            </div>
        </div>

        <!-- Navigasi Tab -->
        <ul class="flex border-b-2 border-gray-300 mb-4 text-sm font-medium text-center" id="userTabs">
            <li><button data-role="all"
                    class="tab-button active text-blue-600 border-b-3 border-yellow-400 px-4 py-2 cursor-pointer">Semua</button>
            </li>
            <li><button data-role="1" class="tab-button px-4 py-2 cursor-pointer">Mahasiswa</button></li>
            <li><button data-role="5" class="tab-button px-4 py-2 cursor-pointer">Dosen</button></li>
            <li><button data-role="6" class="tab-button px-4 py-2 cursor-pointer">Tendik</button></li>
        </ul>

        <!-- Kontainer tabel -->
        <div id="pengaduan-table-body">
            @include('sarpras.pengaduan.table', ['pengaduan' => $pengaduan, 'filter_user' => 'all', 'pelapor' => $pelapor])
        </div>

    </div>

    <div id="myModal" class="fixed inset-0 z-50 hidden items-center justify-center backdrop-blur-sm bg-white/30"></div>

@endsection

@push('js')
    <script>
        function modalAction(url = '') {
            $.get(url, function (response) {
                $('#myModal').html(response).removeClass('hidden').addClass('flex');
            });
        }

        // Untuk menutup modal
        $(document).on('click', '#modal-close', function () {
            $('#myModal').addClass('hidden').removeClass('flex').html('');
        });

        function reloadData(filter_user = 'all') {
            $.ajax({
                url: "{{ route('sarpras.pengaduan') }}",
                method: 'GET',
                data: {
                    search: $('#search').val(),
                    per_page: $('#per_page').val(),
                    id_periode: $('#id_periode').val(),
                    filter_user: filter_user
                },
                success: function (response) {
                    $('#pengaduan-table-body').html(response.html);
                },
                error: function () {
                    Swal.fire('Error', 'Gagal memuat data pengaduan', 'error');
                }
            });
        }

        $(document).ready(function () {
            let activeFilter = 'all';

            // Event klik tab
            $('.tab-button').on('click', function () {
                $('.tab-button').removeClass('text-blue-600 border-b-3 border-yellow-500 active');
                $(this).addClass('text-blue-600 border-b-3 border-yellow-500 active');
                activeFilter = $(this).data('role');
                reloadData(activeFilter);
            });

            // Event untuk jumlah data per halaman
            $('#per_page, #id_periode').on('change', function () {
                reloadData(activeFilter);
            });

            // Event untuk pencarian (dengan debounce)
            let debounceTimer;
            $('#search').on('input', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function () {
                    reloadData();
                }, 300);
            });

        });

    </script>
@endpush