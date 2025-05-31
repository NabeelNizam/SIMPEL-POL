@extends('layouts.template')

@section('content')
    <div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-600">
        {{-- <div class="w-full max-w-sm p-6 bg-white border-t-4 border-blue-600 rounded-lg shadow-sm"> --}}
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-700">Daftar Gedung yang terdaftar dalam sistem</span>
                <div class="flex gap-2">
                    <button
                        class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                        <i class="fas fa-file-excel"></i> Ekspor Excel
                    </button>
                    <button
                        class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                        <i class="fas fa-file-pdf"></i> Ekspor PDF
                    </button>
                    <button
                        class="bg-green-600 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-green-700">
                        <i class="fas fa-plus"></i> Tambah
                    </button>
                </div>
            </div>

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
                <table class="w-full border-collapse border text-left text-sm" id="table_gedung">
                    <thead>
                        <tr>
                            <th class="bg-gray-100 border px-4 py-2 font-semibold">ID</th>
                            <th class="bg-gray-100 border px-4 py-2 font-semibold">Kode</th>
                            <th class="bg-gray-100 border px-4 py-2 font-semibold">Nama Lokasi</th>
                            <th class="bg-gray-100 border px-4 py-2 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" databackdrop="static"
            data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection
@push('css')
<style>
  .dataTables_wrapper .dataTables_length,
  .dataTables_wrapper .dataTables_filter {
    @apply mb-4;
  }
  .dataTables_wrapper .dataTables_paginate {
    @apply flex justify-end gap-2 mt-4;
  }
  .dataTables_wrapper .dataTables_paginate .paginate_button {
    @apply px-3 py-1 border rounded hover:bg-gray-100;
  }
</style>
@endpush
    @push('js')
        <script>
            function modalAction(url = '') {
                $('#myModal').load(url, function () {
                    $('#myModal').modal('show');
                });
            }
            var dataGedung;
            $(document).ready(function () {
                dataGedung = $('#table_gedung').DataTable({
                    // serverSide: true, jika ingin menggunakan server side processing
                    serverSide: true,
                    ajax: {
                        "url": "{{ url('/admin/listGedung') }}",
                        "dataType": "json",
                        "type": "POST",
                        "headers": {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        }
                    },
                    columns: [
                        {
                            // nomor urut dari laravel datatable addIndexColumn()
                            data: "DT_RowIndex",
                            className: "",
                            orderable: false,
                            searchable: false
                        }, {
                            data: "kode_jurusan",
                            className: "",
                            // orderable: true, jika ingin kolom ini bisa diurutkan
                            orderable: true,
                            // searchable: true, jika ingin kolom ini bisa dicari
                            searchable: true
                        }, {
                            data: "nama_jurusan",
                            className: "",
                            orderable: true,
                            searchable: true
                        }, {
                            data: "aksi",
                            className: "",
                            orderable: false,
                            searchable: false
                        }
                    ]
                });
            });
        </script>
    @endpush