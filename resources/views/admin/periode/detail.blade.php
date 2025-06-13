<!-- Modal Konten Detail -->
<div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 relative">

    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl cursor-pointer">
        <i class="fas fa-times"></i>
    </button>

    <h2 class="text-xl font-semibold text-center">Detail Periode</h2>
    <div class="w-12 h-1 bg-yellow-400 mx-auto mt-1 mb-6 rounded"></div>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-8 text-sm text-gray-700">
        <div>
            <p class="font-semibold">Kode Periode</p>
            <p class="break-words whitespace-normal font-bold ">{{ $periode->kode_periode ?? '-' }}</p>
        </div>
        <div class="grid grid-cols-2 gap-16">
            <div>
                <p class="font-semibold">Tanggal Mulai</p>
                <p>{{ $periode->tanggal_mulai ?? '-' }}</p>
            </div>
            <div>
                <p class="font-semibold">Tanggal Selesai</p>
                <p>{{ $periode->tanggal_selesai ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).on('click', '#modal-close', function () {
        $('#myModal').addClass('hidden').removeClass('flex').html('');
    });
</script>