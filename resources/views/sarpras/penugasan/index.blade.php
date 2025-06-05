@extends('layouts.template')

@section('content')
    <div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-600">
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm text-gray-700">Daftar Penugasan Perbaikan yang terdaftar dalam sistem</span>
        </div>
        <hr class="border-black opacity-30 mt-4">

        <!-- Filter Form -->
        <form id="filter-form" method="GET" class="flex flex-wrap gap-4 mb-4 mt-8">
            <!-- Filter Periode -->
            <div class="flex items-center gap-2">
                <label for="id_periode" class="text-sm font-medium text-gray-700">Filter Periode:</label>
                <select id="id_periode" name="id_periode" class="w-48 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                    <option value="">Semua Periode</option>
                    @foreach ($periode as $p)
                        <option value="{{ $p->id_periode }}" {{ request('id_periode') == $p->id_periode ? 'selected' : '' }}>{{ $p->kode_periode }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Filter</button>
        </form>

        <ul class="flex border-b-2 border-gray-300 mb-4 text-sm font-medium text-center" id="userTabs">
            <li><button data-role="awal"
                    class="tab-button active text-blue-600 border-b-3 border-yellow-400 px-4 py-2 cursor-pointer">DATA AWAL</button></li>
            <li><button data-role="perbaikan" class="tab-button px-4 py-2 cursor-pointer">DATA SETELAH PERBAIKAN</button></li>
        </ul>

        <!-- Tabel Data Awal -->
        <div id="aduan-table-body">
            @include('sarpras.penugasan.penugasan_table', ['aduan' => $aduan])
        </div>

        <!-- Tabel Data Setelah Perbaikan -->
        <div id="promethee-table-body" class="hidden">
            <div class="text-center text-lg font-bold">TES</div>
        </div>
    </div>

    <div id="myModal" class="fixed inset-0 z-50 hidden items-center justify-center backdrop-blur-sm bg-white/30"></div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        // Event untuk tab switching
        $('.tab-button').on('click', function() {
            // Hapus kelas aktif dari semua tab
            $('.tab-button').removeClass('active text-blue-600 border-yellow-400');
            
            // Tambahkan kelas aktif ke tab yang diklik
            $(this).addClass('active text-blue-600 border-yellow-400');
            
            // Dapatkan role dari tab yang diklik
            const role = $(this).data('role');
            
            // Sembunyikan semua tabel
            $('#aduan-table-body').addClass('hidden');
            $('#promethee-table-body').addClass('hidden');
            
            // Tampilkan tabel sesuai role
            if (role === 'awal') {
                $('#aduan-table-body').removeClass('hidden');
            } else if (role === 'perbaikan') {
                $('#promethee-table-body').removeClass('hidden');
            }
        });
    });
</script>
@endpush