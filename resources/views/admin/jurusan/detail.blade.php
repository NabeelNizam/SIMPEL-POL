<!-- Modal Konten Detail Fasilitas -->
<div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 relative">

    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl cursor-pointer">
        <i class="fas fa-times"></i>
    </button>

    <h2 class="text-xl font-semibold text-center">Detail Data Jurusan</h2>
    <div class="w-[180px] h-1 bg-yellow-400 mx-auto mt-1 mb-6 rounded"></div>

    <!-- Detail Informasi -->
    <div class="grid grid-cols-1 gap-3 text-sm text-gray-700 mb-3">
        <div class="mt-2">
            <p class="font-semibold text-gray-500">Kode Jurusan</p>
            <p>{{ $jurusan->kode_jurusan ?? '-' }}</p>
        </div>
        <div class="mt-2">
            <p class="font-semibold text-gray-500">Nama Jurusan</p>
            <p>{{ $jurusan->nama_jurusan ?? '-' }}</p>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '#modal-close', function () {
        $('#myModal').addClass('hidden').removeClass('flex').html('');
    });
</script>
