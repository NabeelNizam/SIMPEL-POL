@extends('layouts.template')

@section('content')
<div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-600">
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm text-gray-700">Daftar lokasi yang terdaftar dalam sistem</span>
            <div class="flex gap-2">
                <a href=""
                    class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                    <i class="fas fa-file-excel"></i> Ekspor Excel
                </a>
                <a href=""
                    class="bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-blue-900">
                    <i class="fas fa-file-pdf"></i> Ekspor PDF
                </a>
                <button onclick="modalAction('{{ route('admin.lokasi.create') }}')"
                    class="bg-green-600 text-white px-4 py-2 rounded flex items-center gap-2 text-sm hover:bg-green-700">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>
        </div>
        <hr class="border-black opacity-30 mt-4">
        @if (session('success'))
            <div class="text-green-600 text-sm">
                {{ session('success') }}
            </div>
        @endif


        <form id="filter-form" onsubmit="return false" class="flex flex-col gap-4 mb-4 mt-8">
            <div class="flex justify-between items-center flex-wrap">
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
                        placeholder="Cari lokasi..."
                        class="w-64 border border-gray-300 rounded-md shadow-sm sm:text-sm" />
                </div>
            </div>

        </form>


        <div id="user-table-body">
            @include('admin.lokasi.lokasi_table', ['lokasi' => $lokasi])
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
        $(document).on('click', '#modal-close', function() {
            $('#myModal').addClass('hidden').removeClass('flex').html('');
        });

        function toggleAccordion(button) {
        const content = button.nextElementSibling;
        const icon = button.querySelector('svg');

        content.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
    }
        function reloadData() {
            $.ajax({
                url: "{{ route('admin.lokasi') }}",
                method: "GET",
                data: {
                    id_role: $('#id_role').val(),
                    search: $('#search').val(),
                    per_page: $('#per_page').val(),
                    sort_column: $('#sort-column').val(),
                    sort_direction: $('#sort-direction').val()
                },
                success: function(response) {
                    $('#user-table-body').html(response.html);
                },
                error: function() {
                    Swal.fire('Error!', 'Gagal memuat data lokasi.', 'error');
                }
            });
        }

        $(document).ready(function() {
            // Event untuk filter role
            $('#id_role').on('change', function() {
                reloadData();
            });

            // Event untuk jumlah data per halaman
            $('#per_page').on('change', function() {
                reloadData();
            });

            // Event untuk pencarian (dengan debounce)
            let debounceTimer;
            $('#search').on('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function() {
                    reloadData();
                }, 300);
            });

            // Event untuk sorting jika ada
            $('#sort-column, #sort-direction').on('change', function() {
                reloadData();
            });
        });
    let lantaiCounter = 0;

    function tambahLantai() {
      const lantaiInput = document.getElementById('inputLantai');
      const lantaiNama = lantaiInput.value.trim();
      if (!lantaiNama) return alert('Nama lantai tidak boleh kosong.');

      lantaiCounter++;
      const lantaiId = `lantai-${lantaiCounter}`;
      const container = document.getElementById('lantaiContainer');

      const lantaiElement = document.createElement('div');
        lantaiElement.className = "bg-blue-500";
        lantaiElement.innerHTML = `
        <div class="flex justify-between items-center px-4 py-2 cursor-pointer bg-blue-200" onclick="toggleLantai('${lantaiId}')">
            <div class="font-medium">${lantaiNama}</div>
            <div class="flex items-center gap-2">
            <svg id="icon-${lantaiId}" class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"> 
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
            <!-- Tombol hapus -->
            <button onclick="hapusLantai(event, '${lantaiId}')" class="text-red-500 hover:text-red-700" title="Hapus Lantai">
                <i class="fa-solid fa-trash"></i>
            </button>
            </div>
        </div>
        <div class="px-4 py-3 space-y-3 hidden" id="${lantaiId}" style="background-color: #D9D9D9;">
            <div class="border-l-4 border-orange-400 pl-3">
            <label class="text-sm font-medium">Nama Ruangan <span class="text-red-500">*</span></label>
            <input type="text" name="ruangan[]" placeholder="Contoh: LPR 1" class="w-full mt-1 px-3 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-400">
            </div>
            <button type="button" onclick="tambahRuangan(this)" class="text-blue-600 text-sm hover:underline">
            <i class="fa-solid fa-square-plus"></i> Tambah Ruangan
            </button>
        </div>
        `;
        container.appendChild(lantaiElement);
        lantaiInput.value = '';
    
    }
        function toggleLantai(id) {
        const content = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);
        const isHidden = content.classList.contains('hidden');

        content.classList.toggle('hidden');
        icon.classList.toggle('rotate-180', isHidden); // Rotasi panah
        }




    function hapusLantai(event, id) {
      event.stopPropagation();
      const container = document.getElementById(id).parentElement;
      container.remove();
    }

    function tambahRuangan(button) {
      const parent = button.parentElement;
      const ruanganDiv = document.createElement('div');
      ruanganDiv.className = "border-l-4 border-orange-400 pl-3";

      ruanganDiv.innerHTML = `
        <label class="text-sm font-medium">Nama Ruangan <span class="text-red-500">*</span></label>
        <input type="text" name="ruangan[]" placeholder="Contoh: LPR 1" class="w-full mt-1 px-3 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-400 mt-2">
      `;

      parent.insertBefore(ruanganDiv, button);
    }
    </script>
@endpush