@extends('layouts.template')

@section('content')
<div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-600">
    <div class="flex items-center justify-between mb-4">
        <span class="text-sm text-gray-700">Daftar Kriteria yang terdaftar dalam sistem</span>
        <div class="flex gap-2">
            <a href="{{ route('sarpras.bobot.export_excel') }}" class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                <i class="fas fa-file-excel"></i> Ekspor Excel
            </a>
            <a href="{{ route('sarpras.bobot.export_pdf') }}" class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                <i class="fas fa-file-pdf"></i> Ekspor PDF
            </a>
        </div>
    </div>
    <hr class="border-black opacity-30 mt-4">

<div class="flex justify-between items-center my-4">
    <!-- Pencarian -->
    <div class="flex items-center gap-2">
        <label for="search" class="text-sm font-medium text-gray-700">Pencarian: </label>
        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari kriteria..." class="w-64 border border-gray-300 rounded-md shadow-sm sm:text-sm" />
    </div>

    <div>
        <button onclick="modalAction('{{ route('sarpras.bobot.edit') }}')"
            class="cursor-pointer text-red-600 hover:underline text-sm">
            <img src="{{ asset('icons/solid/Edit.svg') }}" alt="Edit"
                class="h-8 w-8 min-h-[29px] min-w-[29px]">
        </button>
    </div>
</div>

    <div id="kriteria-table-body">
        @include('sarpras.bobot.kriteria_table', ['kriteria' => $kriteria])
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
        url: "{{ route('sarpras.bobot') }}",
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
            $('#kriteria-table-body').html(response.html);
        },
        error: function () {
            Swal.fire('Error!', 'Gagal memuat data kriteria.', 'error');
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
