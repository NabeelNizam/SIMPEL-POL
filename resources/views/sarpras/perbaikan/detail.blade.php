<!-- Modal Konten Detail -->
<div
    class="bg-white rounded-lg shadow-lg max-w-3xl w-full p-6 pt-0 relative max-h-[80vh] overflow-y-auto border-t-4 border-blue-600">
    <div class="bg-white sticky top-0 z-10 py-3 mb-3">
        <button id="modal-close"
            class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl cursor-pointer">
            <i class="fas fa-times"></i>
        </button>

        <h2 class="text-xl font-semibold text-center">Detail Perbaikan Fasilitas</h2>
        <div class="w-54 h-1 bg-yellow-400 mx-auto mt-1 mb-6 rounded"></div>
    </div>

    <!-- Isi Pengaduan -->
    <div class="mb-6">
        <div class="flex items-center mb-4">
            <div class="bg-blue-500 text-white px-3 py-2 rounded-md mr-3">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Detail Fasilitas</h3>
        </div>
        <div class="w-48 h-0.5 bg-orange-400 mb-4"></div>

        <div class="flex gap-8">
            <!-- Gambar -->
            <div class="flex-shrink-0">
                <div class="bg-gray-100 rounded-lg shadow-sm p-4">
                    @if(!empty($fasilitas->foto_fasilitas) && file_exists(public_path($fasilitas->foto_fasilitas)))
                        <img src="{{ asset('storage/' . $fasilitas->foto_fasilitas) }}" alt="Foto Aduan"
                            class="w-64 h-48 object-cover rounded-lg shadow">
                    @else
                        <img src="{{ asset('img/no-image.svg') }}" alt="No Image"
                            class="w-64 h-48 object-cover rounded-lg shadow">
                    @endif
                    <div class="mt-4">
                        <h4 class="font-bold text-gray-800 text-lg">{{ $fasilitas->nama_fasilitas ?? '-' }}</h4>
                        <p class="text-sm text-gray-600">{{ $fasilitas->kategori->nama_kategori ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Detail Information -->
            <div class="flex-1">
                <div class="grid grid-cols-2 gap-x-8 gap-y-6">
                    <!-- Lokasi -->
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

                    <!-- Tanggal Mulai Perbaikan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Mulai Perbaikan</label>
                        <p class="text-gray-800 font-semibold">
                            {{ $fasilitas->inspeksi->first()->perbaikan->tanggal_mulai ?? '-' }}
                        </p>
                    </div>

                    <!-- Urgensi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Urgensi</label>
                        <span class="inline-block px-4 py-1 rounded-full text-white text-sm font-medium
                                @if($fasilitas->urgensi === \App\Http\Enums\Urgensi::DARURAT)
                                    bg-red-500
                                @elseif($fasilitas->urgensi === \App\Http\Enums\Urgensi::PENTING)
                                    bg-yellow-500
                                @elseif($fasilitas->urgensi === \App\Http\Enums\Urgensi::BIASA)
                                    bg-blue-500
                                @else
                                    bg-gray-500
                                @endif">
                            {{ $fasilitas->urgensi->value ?? '-' }}
                        </span>
                    </div>

                    <!-- Tanggal Selesai Perbaikan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Selesai Perbaikan</label>
                        <p class="text-gray-800 font-semibold">
                            {{ $fasilitas->inspeksi->first()->perbaikan->tanggal_selesai ?? '-' }}
                        </p>
                    </div>

                    <!-- Status -->
                    <div>
                        @php
                            $isCompleted = !empty($fasilitas->inspeksi->first()->perbaikan->tanggal_selesai);
                        @endphp
                        <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                        <span
                            class="inline-block py-1 rounded-full text-white text-sm font-medium @if($isCompleted) bg-green-500 px-11 @else bg-yellow-500 px-3 @endif">
                            {{ $isCompleted ? 'Selesai' : 'Dalam Perbaikan' }}
                        </span>
                    </div>

                    <!-- Jumlah Pelapor -->
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Jumlah Pelapor</label>
                        <p class="font-semibold text-sm leading-relaxed">{{ $fasilitas->aduan_count ?? '-' }}</p>
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
            <div class="w-84 h-0.5 bg-orange-400 mb-4"></div>

            @if($fasilitas->inspeksi && $inspeksi->teknisi)
                <div class="grid grid-cols-3 gap-4 text-sm">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Nama</label>
                        <p class="text-gray-800 font-semibold">{{ $inspeksi->teknisi->nama ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Jurusan</label>
                        <p class="text-gray-800 font-semibold">
                            {{ $inspeksi->teknisi->jurusan->nama_jurusan ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">NIP</label>
                        <p class="text-gray-800 font-semibold">{{ $inspeksi->teknisi->pegawai->nip ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Username</label>
                        <p class="text-gray-800 font-semibold">{{ $inspeksi->teknisi->username ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                        <p class="text-gray-800 font-semibold">{{ $inspeksi->teknisi->email ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">No. Telepon</label>
                        <p class="text-gray-800 font-semibold">{{ $inspeksi->teknisi->no_hp ?? '-' }}</p>
                    </div>
                </div>
            @else
                <p class="text-gray-500">Belum ada teknisi yang bertugas.</p>
            @endif
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
            <div class="w-48 h-0.5 bg-orange-400 mb-4"></div>

            <div class="flex space-x-4 mb-4">
                {{-- tingkat kerusakan --}}
                <div class="text">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Tingkat Kerusakan</label>
                    <span class="inline-block px-4 py-1 rounded-full text-white text-sm font-medium
                        @if($inspeksi->tingkat_kerusakan === \App\Http\Enums\TingkatKerusakan::PARAH)
                            bg-red-500
                        @elseif($inspeksi->tingkat_kerusakan === \App\Http\Enums\TingkatKerusakan::SEDANG)
                            bg-yellow-500
                        @elseif($inspeksi->tingkat_kerusakan === \App\Http\Enums\TingkatKerusakan::RINGAN)
                            bg-blue-500
                        @else
                            bg-gray-500
                        @endif ">
                        {{ $inspeksi->tingkat_kerusakan ?? '-' }}
                    </span>
                </div>
                {{-- deskripsi pekerjaan --}}
                <div class="text">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Deskripsi Pekerjaan</label>
                    <p class="text-dark font-sm">{{ $inspeksi->deskripsi ?? '-' }}</p>
                </div>
            </div>

            <!-- Rincian Anggaran -->
            <div class="mt-6">
                <h4 class="font-semibold text-gray-800 mb-3">Rincian Anggaran Perbaikan</h4>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border border-gray-300">
                        <thead class="text-base text-gray-700 bg-white">
                            <tr>
                                <th scope="col"
                                    class="px-5 py-4 border border-gray-300 text-gray-900 text-center w-[75px]">No</th>
                                <th scope="col" class="px-5 py-4 border border-gray-300 text-gray-900 text-left">
                                    Keterangan</th>
                                <th scope="col" class="px-5 py-4 border border-gray-300 text-gray-900 text-left">Biaya
                                    (Rp)</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-800">
                            @forelse ($inspeksi->biaya as $index => $item)
                                <tr class="even:bg-white odd:bg-gray-100 border-b border-gray-300 text-md">
                                    <td class="px-5 py-4 border border-gray-300 font-medium text-center">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-5 py-4 border border-gray-300 text-left font-medium">
                                        {{ $item->keterangan }}
                                    </td>
                                    <td class="px-5 py-4 border border-gray-300 text-left font-medium">
                                        {{ number_format($item->besaran, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr class="font-medium">
                                    <td colspan="3" class="text-center text-gray-500 py-4 bg-gray-100">Tidak ada data biaya.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <!-- Total -->
                    <div class="flex justify-end items-center align-middle gap-1 mt-5">
                        <div class="text-base font-semibold px-4 py-2">Total (Rp):</div>
                        <div class="bg-blue-100 px-4 py-2 rounded-md font-bold text-base">
                            {{ number_format($inspeksi->biaya->sum('besaran'), 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="border-gray-300 my-6">
    </div>


    <script>
        $(document).on('click', '#modal-close', function () {
            $('#myModal').addClass('hidden').removeClass('flex').html('');
        });
    </script>