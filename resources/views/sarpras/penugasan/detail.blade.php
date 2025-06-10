<!-- Modal Konten Detail -->
<div class="bg-white rounded-lg shadow-lg max-w-3xl w-full p-6 relative max-h-[85%] overflow-y-auto border-t-4 border-blue-600">

    <div class="bg-white sticky mt-0">

        <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl cursor-pointer">
            <i class="fas fa-times"></i>
        </button>
    
        <h2 class="text-xl font-semibold text-center">Detail Penugasan Kerusakan Fasilitas</h2>
        <div class="w-[340px] h-1 bg-yellow-400 mx-auto mt-3 mb-6 rounded"></div>
    </div>

    <!-- Isi Penugasan -->
    <div class="mb-6">
        <div class="flex items-center mb-4">
            <div class="bg-blue-500 text-white p-2 rounded-md mr-3 w-8 h-8 flex justify-center items-center">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Detail Fasilitas</h3>
        </div>
        <div class="w-[165px] h-0.5 bg-orange-400 mb-4"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Gambar -->
            <div class="flex justify-center">
                <div class="bg-gray-100 p-4 rounded-lg shadow-sm">
                    @if(!empty($inspeksi->fasilitas->foto_fasilitas) && file_exists(public_path($inspeksi->fasilitas->foto_fasilitas)))
                        <img src="{{ asset($inspeksi->fasilitas->foto_fasilitas) }}" alt="Foto Fasilitas"
                            class="w-48 h-32 object-cover rounded-lg shadow">
                    @else
                        <img src="{{ asset('img/no-image.svg') }}" alt="No Image"
                            class="w-48 h-32 object-cover rounded-lg shadow">
                    @endif

                    <div class="mt-3 text-center">
                        <h4 class="font-semibold text-gray-800">{{ $inspeksi->fasilitas->nama_fasilitas ?? '-' }}</h4>
                        <p class="text-sm text-gray-600">{{ $inspeksi->fasilitas->kategori->nama_kategori ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <!-- Detail Aduan -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Lokasi</label>
                    <p class="text-gray-800 font-semibold">
                       {{ $inspeksi->fasilitas->getLokasiString() }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Urgensi</label>
                    <p class="text-gray-800 font-semibold">
                        @if($inspeksi->fasilitas->urgensi)
                            <span class="px-3 py-1 px-2 rounded-full text-white text-sm
                                @if($inspeksi->fasilitas->urgensi === \App\Http\Enums\Urgensi::DARURAT)
                                    bg-red-500
                                @elseif($inspeksi->fasilitas->urgensi === \App\Http\Enums\Urgensi::PENTING)
                                    bg-yellow-500
                                @elseif($inspeksi->fasilitas->urgensi === \App\Http\Enums\Urgensi::BIASA)
                                    bg-blue-500
                                @else
                                    bg-gray-500
                                @endif
                            ">
                                {{ $inspeksi->fasilitas->urgensi->value }}
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-gray-500 text-white text-sm">-</span>
                        @endif
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                        <span class="inline-block px-4 py-1 rounded-full text-white text-sm font-medium bg-yellow-500">
                            SEDANG INSPEKSI
                        </span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Jumlah Pelapor</label>
                    <p class="font-semibold text-sm leading-relaxed">{{ $inspeksi->user_count ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <hr class="border-gray-300 my-6">

    <!-- Identitas Teknisi -->
    <div class="mb-6">
        <div class="flex items-center mb-4">
            <div class="bg-blue-500 text-white p-2 rounded-md mr-3 w-8 h-8 flex justify-center items-center">
                <i class="fas fa-user-cog"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Identitas Teknisi Yang Bertugas</h3>
        </div>
        <div class="w-[305px] h-0.5 bg-orange-400 mb-4"></div>

        <div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Nama</label>
                    <p class="text-gray-800 font-semibold">
                        {{ $inspeksi->teknisi->nama ?? '-' }}
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Jurusan</label>
                    <p class="text-gray-800 font-semibold">
                        {{ $inspeksi->teknisi->jurusan->nama_jurusan ?? '-' }}
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">NIP</label>
                    <p class="text-gray-800 font-semibold">
                        {{ $inspeksi->teknisi->pegawai->nip ?? '-' }}
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                    <p class="text-gray-800 font-semibold">
                        {{ $inspeksi->teknisi->email ?? '-' }}
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Username</label>
                    <p class="text-gray-800 font-semibold">
                        {{ $inspeksi->teknisi->username ?? '-' }}
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">No Telepon</label>
                    <p class="text-gray-800 font-semibold">
                        {{ $inspeksi->teknisi->no_hp ?? '-' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <hr class="border-gray-300 my-6">

    <!-- Hasil Inspeksi -->
    <div class="mb-6">
        <div class="flex items-center mb-4">
            <div class="bg-blue-500 text-white p-2 rounded-md mr-3 w-8 h-8 flex justify-center items-center">
                <i class="fas fa-user-cog"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Hasil Inspeksi</h3>
        </div>
        <div class="w-[150px] h-0.5 bg-orange-400 mb-4"></div>

        <div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Tingkat Kerusakan</label>
                    <p class="text-gray-800 font-semibold">
                        @if($inspeksi->tingkat_kerusakan)
                            <span class="px-4 py-1 rounded-full text-white text-sm
                                @if($inspeksi->tingkat_kerusakan === \App\Http\Enums\TingkatKerusakan::PARAH)
                                    bg-red-500
                                @elseif($inspeksi->tingkat_kerusakan === \App\Http\Enums\TingkatKerusakan::SEDANG)
                                    bg-yellow-500
                                @elseif($inspeksi->tingkat_kerusakan === \App\Http\Enums\TingkatKerusakan::RINGAN)
                                    bg-blue-500
                                @else
                                    bg-gray-500
                                @endif
                            ">
                                {{ $inspeksi->tingkat_kerusakan->value ?? '-' }}
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-gray-500 text-white text-sm">-</span>
                        @endif
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Deskripsi Pekerjaan</label>
                    <p class="text-gray-800 font-semibold">
                        {{ $inspeksi->deskripsi ?? '-' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Biaya -->
        <div class="mt-8 mb-10">
            <div class="mb-4"><h3 class="text-xl font-bold text-gray-800">Rincian Anggaran Perbaikan</h3></div>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-left border border-gray-300">
                    <thead class="text-base text-gray-700 bg-white">
                        <tr>
                            <th scope="col" class="px-5 py-4 border border-gray-300 text-gray-900 text-center w-[75px]">No</th>
                            <th scope="col" class="px-5 py-4 border border-gray-300 text-gray-900 text-left">Keterangan</th>
                            <th scope="col" class="px-5 py-4 border border-gray-300 text-gray-900 text-left">Biaya (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800">
                        @forelse ($biaya as $index => $item)
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
                            <td colspan="3" class="text-center text-gray-500 py-4 bg-gray-100">Tidak ada data biaya.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Total -->
            <div class="flex justify-end items-center align-middle gap-1 mt-5">
                <div class="text-base font-semibold px-4 py-2">Total (Rp):</div>
                <div class="bg-blue-100 px-4 py-2 rounded-md font-bold text-base">
                    {{ number_format($biaya->sum('besaran'), 0, ',', '.') }}
                </div>
            </div>
        </div>

        <hr class="border-gray-300 my-6">
        
        <div class="flex justify-end">
            <button type="button" id="modal-close" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none cursor-pointer">
                Kembali
            </button>
        </div>
    </div>
</div>


<script>
    $(document).on('click', '#modal-close', function () {
        $('#myModal').addClass('hidden').removeClass('flex').html('');
    });
</script>