<!-- Modal Konten Detail -->
<div class="bg-white rounded-lg shadow-lg max-w-3xl w-full p-6 relative max-h-[80vh] overflow-y-auto">

    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl cursor-pointer">
        <i class="fas fa-times"></i>
    </button>

    <h2 class="text-xl font-semibold text-center">Detail Aduan</h2>
    <div class="w-[150px] h-1 bg-yellow-400 mx-auto mt-1 mb-6 rounded"></div>

    <!-- Detail Fasilitas -->
    <div class="mb-6">
        <div class="flex items-center mb-4">
            <div class="bg-blue-500 text-white px-3 py-2 rounded-md mr-3">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Detail Fasilitas</h3>
        </div>
        <div class="w-16 h-0.5 bg-orange-400 mb-6"></div>

        <div class="flex gap-8">
            <!-- Gambar -->
            <div class="flex-shrink-0">
                <div class="bg-gray-100 rounded-lg shadow-sm p-4">
                    <img src="{{ asset($perbaikan->inspeksi->fasilitas->foto_fasiltias ?? 'img/no-image.svg') }}" alt="Gambar Fasilitas"
                        class="w-full h-32 object-cover rounded-lg border">
                    <div class="mt-2">
                        <p class="font-semibold text-black-700">{{ $perbaikan->inspeksi->fasilitas->nama_fasilitas ?? '-' }}</p>
                        <p class="text-gray-700">{{ ucwords($perbaikan->inspeksi->fasilitas->kategori->nama_kategori) ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Detail Information -->
            <div class="flex-1">
                <div class="grid grid-cols-2 gap-x-8 gap-y-6">
                    <!-- Lokasi -->
                    <div>
                        <label class="block text-sm leading-relaxed text-gray-500 mb-1">Lokasi</label>
                        <p class="text-gray-800 text-sm font-semibold">
                            {{ $perbaikan->inspeksi->fasilitas->lokasi ?? '-' }}
                        </p>
                    </div>

                    <!-- Tanggal Mulai Perbaikan -->
                    <div>
                        <label class="block text-sm leading-relaxed text-gray-500 mb-1">Tanggal Mulai
                            Perbaikan</label>
                        <p class="text-gray-700 text-sm font-semibold">
                            {{ $perbaikan->tanggal_mulai ?? '-' }}
                        </p>
                    </div>

                    <!-- Urgensi -->
                    <div>
                        <label class="block text-sm leading-relaxed text-gray-600 mb-1">Urgensi</label>
                        <span class="inline-block px-4 py-1 rounded text-white text-sm font-medium
                                @if($perbaikan->inspeksi->fasilitas->urgensi === \App\Http\Enums\Urgensi::DARURAT)
                                    bg-red-500
                                @elseif($perbaikan->inspeksi->fasilitas->urgensi === \App\Http\Enums\Urgensi::PENTING)
                                    bg-yellow-500
                                @else
                                    bg-blue-500
                                @endif">
                            {{ $perbaikan->inspeksi->fasilitas->urgensi->value ?? '-' }}
                        </span>
                    </div>

                    <!-- Tanggal Selesai Perbaikan -->
                    <div>
                        <label class="block text-sm leading-relaxed text-gray-500 mb-1">Tanggal Selesai
                            Perbaikan</label>
                        <p class="text-gray-700 text-sm font-semibold">
                            {{ $perbaikan->tanggal_selesai ?? '-' }}
                        </p>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm leading-relaxed text-gray-500 mb-1">Status</label>
                        <span class="inline-block px-4 py-1 rounded text-white text-sm font-medium w-32 block text-center bg-green-500">
                            SELESAI
                        </span>
                    </div>

                    <!-- Jumlah Pelapor -->
                    <div>
                        <label class="block text-sm leading-relaxed text-gray-600 mb-1">Jumlah Pelapor</label>
                        <p class="font-semibold text-sm leading-relaxed">{{ $perbaikan->jumlah_aduan_tertangani ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <hr class="border-gray-300 my-6">

        <!-- Identitas Teknisi -->
        <div class="mb-6">
            <div class="flex items-center mb-4">
                <div class="bg-blue-500 text-white p-2 rounded-md mr-3">
                    <i class="fas fa-user-cog"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Identitas Teknisi yang Bertugas</h3>
            </div>
            <div class="w-16 h-0.5 bg-orange-400 mb-4"></div>

            <div class="grid grid-cols-3 gap-4 text-sm">
                <div>
                    <label class="block text-gray-600 font-medium mb-1">Nama</label>
                    <p class="text-gray-800 font-semibold">
                        {{ $perbaikan->inspeksi->teknisi->nama ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-gray-600 font-medium mb-1">Jurusan</label>
                    <p class="text-gray-800 font-semibold">
                        {{ $perbaikan->inspeksi->teknisi->jurusan->nama_jurusan ?? '-' }}
                    </p>
                </div>
                <div>
                    <label class="block text-gray-600 font-medium mb-1">NIP</label>
                    <p class="text-gray-800 font-semibold">
                        {{ $perbaikan->inspeksi->teknisi->pegawai->nip ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-gray-600 font-medium mb-1">Username</label>
                    <p class="text-gray-800 font-semibold">
                        {{ $perbaikan->inspeksi->teknisi->username ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-gray-600 font-medium mb-1">Email</label>
                    <p class="text-gray-800 font-semibold">
                        {{ $perbaikan->inspeksi->teknisi->email ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-gray-600 font-medium mb-1">No. Telepon</label>
                    <p class="text-gray-800 font-semibold">
                        {{ $perbaikan->inspeksi->teknisi->no_hp ?? '-' }}</p>
                </div>
            </div>
        </div>

        <hr class="border-gray-300 my-6">

        <!-- Hasil Inspeksi Section -->
        <div class="mb-6">
            <div class="flex items-center mb-4">
                <div class="bg-blue-500 text-white p-2 rounded-md mr-3">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Hasil Inspeksi</h3>
            </div>
            <div class="w-16 h-0.5 bg-orange-400 mb-4"></div>

            <div class="flex space-x-4 mb-4 gap-32">
                {{-- tingkat kerusakan --}}
                <div class="text">
                    <label class=" text-sm font-medium text-gray-500 mb-1">Tingkat Kerusakan</label>
                    <span class="inline-block px-4 py-1 rounded text-white text-sm font-medium
                        @if($perbaikan->inspeksi->tingkat_kerusakan === \App\Http\Enums\TingkatKerusakan::PARAH)
                            bg-red-500
                        @elseif($perbaikan->inspeksi->tingkat_kerusakan === \App\Http\Enums\TingkatKerusakan::SEDANG)
                            bg-yellow-500
                        @elseif($perbaikan->inspeksi->tingkat_kerusakan === \App\Http\Enums\TingkatKerusakan::RINGAN)
                            bg-blue-500
                        @else
                            bg-gray-500
                        @endif ">
                        {{ $perbaikan->inspeksi->tingkat_kerusakan->value ?? '-' }}
                    </span>
                </div>
                {{-- deskripsi pekerjaan --}}
                <div class="text">
                    <label class="block text-gray-600 font-medium mb-2 text-sm">Deskripsi Pekerjaan</label>
                    <p class="text-gray-800 font-sm">{{ $perbaikan->inspeksi->deskripsi ?? '-' }}</p>
                </div>
            </div>

            <!-- Rincian Anggaran -->
            <div class="mt-6">
                <h4 class="font-semibold text-gray-800 mb-3">Rincian Anggaran Perbaikan</h4>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-3 py-2 text-left">No</th>
                                <th class="border border-gray-300 px-3 py-2 text-left">Kebutuhan</th>
                                <th class="border border-gray-300 px-3 py-2 text-right">Biaya (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($perbaikan->inspeksi->biaya)
                                @foreach($perbaikan->inspeksi->biaya as $i => $b)
                                    <tr class="hover:bg-gray-50">
                                        <td class="border border-gray-300 px-3 py-2 text-center">{{ $i + 1 }}</td>
                                        <td class="border border-gray-300 px-3 py-2">{{ $b->keterangan }}</td>
                                        <td class="border border-gray-300 px-3 py-2 text-right">
                                            {{ number_format($b->besaran, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" class="border border-gray-300 text-center text-gray-400">Tidak ada
                                        rincian
                                        anggaran.</td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-100 font-semibold">
                                <td colspan="2" class="border border-gray-300 px-3 py-2 text-right">Total (Rp):</td>
                                <td class="border border-gray-300 px-3 py-2 text-right">
                                    {{ number_format($perbaikan->inspeksi->biaya->sum('besaran'), 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <hr class="border-gray-300 my-6">

        <!-- Umpan Balik -->
        <div class="mb-6">
            <div class="flex items-center mb-4">
                <div class="bg-blue-500 text-white p-2 rounded-md mr-3">
                    <i class="fas fa-comment-dots"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Umpan Balik Pelanggan</h3>
            </div>
            <div class="w-16 h-0.5 bg-orange-400 mb-4"></div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Umpan Balik</label>
                    {{-- Rata-rata rating seluruh pelapor --}}
                    @if($perbaikan->rata_rata_rating)
                        <div class="flex items-center mb-2">
                            <i class="fas fa-star text-yellow-400 text-lg mr-1"></i>
                            <span class="text-yellow-500 font-bold text-lg mr-1">{{ number_format($perbaikan->rata_rata_rating, 1) }}</span>
                            <span class="text-gray-600 text-sm"> / 5.0</span>
                        </div>
                    @else
                        <span class="text-gray-500">Belum ada umpan balik.</span>
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
