<!-- Modal Konten Detail Fasilitas -->
<div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 relative max-h-[70%] overflow-y-auto">

    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl cursor-pointer">
        <i class="fas fa-times"></i>
    </button>

    <h2 class="text-xl font-semibold text-center">Detail Data Fasilitas</h2>
    <div class="w-[185px] h-1 bg-yellow-400 mx-auto mt-1 mb-6 rounded"></div>

    <!-- Gambar Fasilitas -->
    <div class="w-full flex justify-center mb-6">
        <img src="{{ $fasilitas->foto_fasilitas === '0' ? asset('img/no-image.svg') : asset('storage/uploads/img/foto_fasilitas/' . $fasilitas->foto_fasilitas) }}" alt="Foto Fasilitas" class="rounded-lg w-64 h-auto object-cover">
    </div>

    <!-- Detail Informasi -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-700">
        <div class="mt-2">
            <p class="font-semibold text-gray-500">Kode Fasilitas</p>
            <p>{{ $fasilitas->kode_fasilitas ?? '-' }}</p>
        </div>
        <div class="mt-2">
            <p class="font-semibold text-gray-500">Nama Fasilitas</p>
            <p>{{ $fasilitas->nama_fasilitas ?? '-' }}</p>
        </div>
        <div class="mt-2">
            <p class="font-semibold text-gray-500">Lokasi</p>
            <p>{{ $fasilitas->ruangan->lantai->gedung->nama_gedung . ', '. $fasilitas->ruangan->lantai->nama_lantai . ', ' . $fasilitas->ruangan->nama_ruangan ?? '-' }}
            </p>
        </div>
        <div class="mt-2">
            <p class="font-semibold text-gray-500">Kondisi Fasilitas</p>
            <p>
                <span class="inline-block text-white text-xs font-semibold px-3 py-1 rounded {{ $fasilitas->kondisi->value === 'LAYAK' ? 'bg-green-500' : 'bg-red-500' }}">
                    {{ $fasilitas->kondisi->value ?? '-' }}
                </span>
            </p>
        </div>
        <div class="mt-2 mb-5">
            <p class="font-semibold text-gray-500">Deskripsi</p>
            <p>{{ $fasilitas->deskripsi ?? '-' }}</p>
        </div>
        <div class="mt-2 mb-5">
            <p class="font-semibold text-gray-500">Urgensi</p>
            <p>
                <span class="inline-block text-white text-xs font-semibold px-3 py-1 rounded {{ $fasilitas->urgensi->value === 'DARURAT' ? 'bg-red-500' : ($fasilitas->urgensi->value === 'PENTING' ? 'bg-yellow-500' : 'bg-blue-500') }}">
                    {{ $fasilitas->urgensi->value ?? '-' }}
                </span>
            </p>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '#modal-close', function () {
        $('#myModal').addClass('hidden').removeClass('flex').html('');
    });
</script>
