<!-- Modal Konten Detail -->
<div class="bg-white rounded-lg shadow-lg max-w-3xl w-full p-6 relative max-h-[80vh] overflow-y-auto border-t-4 border-blue-600">

    <div class="bg-white sticky mt-0">

        <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl cursor-pointer">
            <i class="fas fa-times"></i>
        </button>
    
        <h2 class="text-xl font-semibold text-center">Detail Pengaduan</h2>
        <div class="w-24 h-1 bg-yellow-400 mx-auto mt-1 mb-6 rounded"></div>
    </div>

    <!-- Isi Pengaduan -->
    <div class="mb-6">
        <div class="flex items-center mb-4">
            <div class="bg-blue-500 text-white px-3 py-2 rounded-md mr-3">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Detail Fasilitas</h3>
        </div>
        <div class="w-16 h-0.5 bg-orange-400 mb-4"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Gambar -->
            <div class="flex justify-center">
                <div class="bg-gray-100 p-4 rounded-lg shadow-sm">
                    @if(!empty($fasilitas->foto_fasilitas) && file_exists(public_path($fasilitas->foto_fasilitas)))
                        <img src="{{ asset($fasilitas->foto_fasilitas) }}" alt="Foto Fasilitas"
                            class="w-48 h-32 object-cover rounded-lg shadow">
                    @else
                        <img src="{{ asset('img/no-image.svg') }}" alt="No Image"
                            class="w-48 h-32 object-cover rounded-lg shadow">
                    @endif

                    <div class="mt-3 text-center">
                        <h4 class="font-semibold text-gray-800">{{ $fasilitas->nama_fasilitas ?? '-' }}</h4>
                        <p class="text-sm text-gray-600">{{ $fasilitas->kategori->nama_kategori ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <!-- Detail Aduan -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Lokasi</label>
                    @php
                        $ruangan = $fasilitas->ruangan;
                        $lantai = $ruangan->lantai;
                        $gedung = $lantai->gedung;
                    @endphp
                    <p class="text-gray-800 font-semibold">
                        {{ $gedung->nama_gedung ?? '-' }}{{ $lantai ? ', ' . $lantai->nama_lantai : '' }}{{ $ruangan ? ', ' . $ruangan->nama_ruangan : '' }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Urgensi</label>
                    <p class="text-gray-800 font-semibold">
                        @if($fasilitas->urgensi)
                            <span class="px-3 py-1 rounded-full text-white text-sm
                                                                @if($fasilitas->urgensi === \App\Http\Enums\Urgensi::DARURAT)
                                                                    bg-red-500
                                                                @elseif($fasilitas->urgensi === \App\Http\Enums\Urgensi::PENTING)
                                                                    bg-yellow-500
                                                                @elseif($fasilitas->urgensi === \App\Http\Enums\Urgensi::BIASA)
                                                                    bg-blue-500
                                                                @else
                                                                    bg-gray-500
                                                                @endif
                                                            ">
                                {{ $fasilitas->urgensi->value }}
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-gray-500 text-white text-sm">-</span>
                        @endif
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                        <span class="inline-block px-4 py-1 rounded-full text-white text-sm font-medium
                            @if($aduan->status === \App\Http\Enums\Status::SELESAI)
                                bg-green-500
                            @elseif($aduan->status === \App\Http\Enums\Status::MENUNGGU_DIPROSES)
                                bg-blue-500
                            @elseif($aduan->status === \App\Http\Enums\Status::SEDANG_INSPEKSI)
                                bg-yellow-500
                            @elseif($aduan->status === \App\Http\Enums\Status::SEDANG_DIPERBAIKI)
                                bg-orange-500
                            @else
                                bg-gray-500
                            @endif ">
                            {{ $aduan->status ?? '-' }}
                        </span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Jumlah Pelapor</label>
                    <p class="font-semibold text-sm leading-relaxed">{{ $fasilitas->aduan_count ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <hr class="border-gray-300 my-6">

    <!-- Laporan dari Pelapor -->
    <div class="mb-6">
        <div class="flex items-center mb-4">
            <div class="bg-blue-500 text-white p-2 rounded-md mr-3">
                <i class="fas fa-user-cog"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Detail Laporan</h3>
        </div>
        <div class="w-16 h-0.5 bg-orange-400 mb-4"></div>

        <!-- Scrollable Container -->
        <div class="max-h-104 overflow-y-auto pr-2">
            @foreach($fasilitas->aduan as $aduan)
                <div class="mb-6 pb-4">
                    <p class="text-base font-semibold text-gray-900">{{ $aduan->pelapor->nama ?? 'Nama Tidak Diketahui' }}
                    </p>
                    <p class="text-sm text-gray-600 my-2">{{ ucwords(strtolower($aduan->pelapor->role->nama_role)) ?? '-' }}
                    </p>
                    <p class="text-gray-900 text-sm mb-2">{{ $aduan->deskripsi }}</p>

                    @if(!empty($aduan->bukti_foto) && file_exists(public_path($aduan->bukti_foto)))
                        <img src="{{ asset($aduan->bukti_foto) }}" alt="Foto Aduan" class="w-48 h-auto mb-2 rounded-md">
                    @else
                        <img src="{{ asset('img/ac-rusak.svg') }}" alt="Foto Aduan" class="w-48 h-auto mb-2 rounded-md">
                        <p class="text-sm text-gray-500">*Gambar Hanya untuk Testing</p>
                    @endif

                    <p class="text-sm text-gray-500 font-medium">
                        {{ \Carbon\Carbon::parse($aduan->created_at)->format('d/m/Y') }}
                    </p>
                </div>
                <hr class="border-gray-300 my-6">
            @endforeach
        </div>
    </div>



</div>


<script>
    $(document).on('click', '#modal-close', function () {
        $('#myModal').addClass('hidden').removeClass('flex').html('');
    });
</script>