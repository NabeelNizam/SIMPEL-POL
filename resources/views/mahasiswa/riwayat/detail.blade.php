<!-- Modal Konten Detail -->
<div class="bg-white rounded-lg shadow-lg max-w-3xl w-full p-6 relative max-h-[80vh] overflow-y-auto">

    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl cursor-pointer">
        <i class="fas fa-times"></i>
    </button>

    <h2 class="text-xl font-semibold text-center">Detail Riwayat Aduan</h2>
    <div class="w-[198px] h-1 bg-yellow-400 mx-auto mt-1 mb-6 rounded"></div>

    <!-- Isi pengaduan -->
    <div class="mb-6">
        <div class="flex items-center mb-4">
            <div class="bg-blue-500 text-white p-2 rounded-md mr-3 w-8 h-8 flex justify-center items-center">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Isi Pengaduan</h3>
        </div>
        <div class="w-[160px] h-0.5 bg-orange-400 mb-4"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Gambar -->
            <div class="flex justify-center">
                <div class="bg-gray-100 p-4 rounded-lg shadow-sm">
                    @if(!empty($aduan->bukti_foto) && file_exists(public_path($aduan->bukti_foto)))
                        <img src="{{ asset('storage/' . $aduan->bukti_foto) }}" alt="Foto Aduan"
                            class="w-48 h-32 object-cover rounded-lg shadow">
                    @else
                        <img src="{{ asset('img/no-image.svg') }}" alt="No Image"
                            class="w-48 h-32 object-cover rounded-lg shadow">
                    @endif
                    <div class="mt-3 text-center">
                        <h4 class="font-semibold text-gray-800">{{ $aduan->fasilitas->nama_fasilitas ?? '-' }}</h4>
                        <p class="text-sm text-gray-600">{{ $aduan->fasilitas->kategori->nama_kategori ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <!-- Detail Aduan -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm leading-relaxed text-gray-600 mb-1">Lokasi</label>
                    @php
                        $ruangan = $aduan->fasilitas->ruangan;
                        $lantai = $ruangan->lantai;
                        $gedung = $lantai->gedung;
                    @endphp
                    <p class="text-gray-700 text-sm font-semibold">
                        {{ $gedung->nama_gedung ?? '-' }}{{ $lantai ? ', ' . $lantai->nama_lantai : '' }}{{ $ruangan ? ', ' . $ruangan->nama_ruangan : '' }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm leading-relaxed text-gray-600 mb-1">Deskripsi Kerusakan</label>
                    <p class="text-gray-700 text-sm font-semibold">{{ $aduan->deskripsi ?? '-' }}</p>
                </div>

                <div class="flex items-center gap-16">
                    <div>
                        <label class="block text-sm leading-relaxed text-gray-600 mb-1">Tanggal Lapor</label>
                        <p class="text-gray-700 text-sm font-semibold">
                            {{ $aduan->tanggal_aduan ? \Carbon\Carbon::parse($aduan->tanggal_aduan)->format('d/m/Y') : '-' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm leading-relaxed text-gray-600 mb-1">Tanggal Perbaikan</label>
                        <p class="text-gray-700 text-sm font-semibold">
                            {{ $aduan->tanggal_perbaikan ? \Carbon\Carbon::parse($aduan->tanggal_perbaikan)->format('d/m/Y') : '-' }}
                        </p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm leading-relaxed text-gray-600 mb-1">Status</label>
                    <span class="px-3 py-1 rounded text-white text-sm bg-green-500 w-32 text-center block">
                        {{ $aduan->status->value }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Umpan Balik -->
    <div class="mb-6">
        <div class="flex items-center mb-4">
            <div class="bg-blue-500 text-white p-2 rounded-md mr-3 w-8 h-8 flex justify-center items-center">
                <i class="fas fa-comment-dots"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Umpan Balik Pelapor</h3>
        </div>
        <div class="w-[220px] h-0.5 bg-orange-400 mb-4"></div>

        <div class="space-y-4">
            <div>
                <label class="block text-sm leading-relaxed text-gray-600 mb-1">Umpan Balik</label>
                @if($aduan->umpan_balik && $aduan->umpan_balik->rating)
                    <div class="flex items-center space-x-1 mb-2">
                        @for($i = 1; $i <= $aduan->umpan_balik->rating; $i++)
                            <i class="fas fa-star text-yellow-400 text-lg"></i>
                        @endfor
                        <span class="pl-1 text-[13px] font-medium">{{ $aduan->umpan_balik->rating }}/5</span>
                    </div>
                    <p class="text-gray-700 text-sm font-semibold">{{ $aduan->umpan_balik->komentar ?? '-' }}</p>
                @else
                    <span class="text-gray-700 text-sm font-semibold">Belum ada umpan balik.</span>
                @endif
            </div>
        </div>
    </div>
</div>


<script>
    $(document).on('click', '#modal-close', function () {
        $('#myModal').addClass('hidden').removeClass('flex').html('');
    });
</script>