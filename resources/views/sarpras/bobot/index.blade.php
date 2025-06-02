@extends('layouts.template')

@section('content')
<div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-700">
    <div class="flex items-center justify-between">
        <span class="text-sm text-gray-700">Daftar Kriteria yang terdaftar dalam sistem</span>
        <div class="flex gap-2">
            <button class="bg-blue-900 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                <i class="fas fa-file-excel"></i> Ekspor Excel
            </button>
            <button class="bg-blue-900 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                <i class="fas fa-file-pdf"></i> Ekspor PDF
            </button>
        </div>
    </div>

    <div class="py-6">
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 divide-y divide-gray-200 text-sm" id="table-kriteria">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-3 py-2 border">No</th>
                        <th class="px-3 py-2 border">Kode Kriteria</th>
                        <th class="px-3 py-2 border">Nama Kriteria</th>
                        <th class="px-3 py-2 border">Bobot</th>
                        <th class="px-3 py-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div id="myModal" class="hidden"></div>
</div>
@endsection
@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function () {
            $('#myModal').modal('show');
        });
    }

    var dataKriteria;
    $(document).ready(function () {
        dataKriteria = $('#table-kriteria').DataTable({
            paging: false,
            searching: false, 
            lengthChange: false, 
            info: false,
            processing: false,
            serverSide: true,
            ajax: {
                "url": "{{ url('kriteria/list') }}",
                "dataType": "json",
                "type": "POST",
                "data": function (d) {
                    d.filter_kriteria = $('.filter_kriteria').val();
                }
            },
            columns: [
                {
                    data: "DT_RowIndex",
                    className: "text-center",
                    width: "20%",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "kode_kriteria",
                    className: "",
                    width: "20%",
                    orderable: true,
                },
                {
                    data: "nama_kriteria",
                    className: "",
                    width: "20%",
                    orderable: true,
                },
                {
                    data: "bobot",
                    className: "",
                    width: "20%",
                    orderable: true,
                },
                {
                    data: "aksi",
                    className: "",
                    width: "20%",
                    orderable: false,
                }
            ],
        });
    });
</script>
@endpush