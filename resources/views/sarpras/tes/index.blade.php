@extends('layouts.template')

@section('content')
    <div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-600">
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm text-gray-700">Tes Perhitungan SPK</span>
        </div>
        <hr class="border-black opacity-30 mt-4">


        <div id="inspeksi-table-body">
            @include('sarpras.tes.table', ['result' => $result])
        </div>
    </div>

@endsection

@push('js')
<script>

    

    function reloadData() {
    $.ajax({
        url: "{{ route('sarpras.penugasan') }}",
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
            $('#inspeksi-table-body').html(response.html);
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