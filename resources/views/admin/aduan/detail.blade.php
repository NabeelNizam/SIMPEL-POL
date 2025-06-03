<!-- Modal Konten Detail -->
<div class="bg-white rounded-lg shadow-lg max-w-3xl w-full p-6 relative max-h-[80vh] overflow-y-auto">

    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl">
        <i class="fas fa-times"></i>
    </button>

    <h2 class="text-xl font-semibold text-center">Detail Aduan</h2>
    <div class="w-24 h-1 bg-yellow-400 mx-auto mt-1 mb-6 rounded"></div>

    <!-- Isi pengaduan -->
    <div class="mb-6">
        <div class="flex items-center mb-4">
            <div class="bg-blue-500 text-white px-3 py-2 rounded-md mr-3">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Isi Pengaduan</h3>
        </div>
        <div class="w-16 h-0.5 bg-orange-400 mb-4"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Gambar -->
            <div class="flex justify-center">
                <div class="bg-gray-100 p-4 rounded-lg shadow-sm">
                    @if($aduan->fasilitas->foto_fasilitas)
                        <img src="{{ asset('storage/' . $aduan->foto) }}" alt="Foto Aduan"
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
                    <label class="block text-sm font-medium text-gray-600 mb-1">Lokasi</label>
                    @php
                        $ruangan = $aduan->fasilitas->ruangan;
                        $lantai = $ruangan->lantai;
                        $gedung = $lantai->gedung;
                    @endphp
                    <p class="text-gray-800 font-semibold">
                        {{ $gedung->nama_gedung ?? '-' }}{{ $lantai ? ', ' . $lantai->nama_lantai : '' }}{{ $ruangan ? ', ' . $ruangan->nama_ruangan : '' }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Lapor</label>
                    <p class="text-gray-800 font-semibold">
                        {{ $aduan->tanggal_aduan ? \Carbon\Carbon::parse($aduan->tanggal_aduan)->format('d/m/Y') : '-' }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Deskripsi Kerusakan</label>
                    <p class="text-gray-700 text-sm leading-relaxed">{{ $aduan->deskripsi ?? '-' }}</p>
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

        @if($aduan->perbaikan && $aduan->perbaikan->teknisi)
            <div class="grid grid-cols-3 gap-4 text-sm">
                <div>
                    <label class="block text-gray-600 font-medium mb-1">Nama</label>
                    <p class="text-gray-800 font-semibold">{{ $aduan->perbaikan->teknisi->nama ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-gray-600 font-medium mb-1">NIP</label>
                    <p class="text-gray-800 font-semibold">{{ $aduan->perbaikan->teknisi->pegawai->nip ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-gray-600 font-medium mb-1">Email</label>
                    <p class="text-gray-800 font-semibold">{{ $aduan->perbaikan->teknisi->email ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-gray-600 font-medium mb-1">No. Telepon</label>
                    <p class="text-gray-800 font-semibold">{{ $aduan->perbaikan->teknisi->no_hp ?? '-' }}</p>
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
            <div class="bg-blue-500 text-white px-3 py-2 rounded-md mr-3">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Hasil Inspeksi</h3>
        </div>
        <div class="w-16 h-0.5 bg-orange-400 mb-4"></div>

        <!-- Status Badges -->
        <div class="flex space-x-4 mb-4">
            <div class="text-center">
                <label class="block text-gray-600 font-medium mb-2 text-sm">Status</label>
                @if($aduan->status)
                    <span class="px-3 py-1 rounded-full text-white text-sm
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
                                @endif
                            ">
                        {{ $aduan->status->value }}
                    </span>
                @else
                    <span class="px-3 py-1 rounded-full bg-gray-500 text-white text-sm">-</span>
                @endif
            </div>
            <div class="text-center">
                <label class="block text-gray-600 font-medium mb-2 text-sm">Tingkat Kerusakan</label>
                @php
                    $tk = $aduan->perbaikan->tingkat_kerusakan ?? null;
                    $warna = 'bg-gray-400';
                    if ($tk === \App\Http\Enums\TingkatKerusakan::PARAH->value)
                        $warna = 'bg-red-500';
                    elseif ($tk === \App\Http\Enums\TingkatKerusakan::SEDANG->value)
                        $warna = 'bg-orange-500';
                    elseif ($tk === \App\Http\Enums\TingkatKerusakan::RINGAN->value)
                        $warna = 'bg-blue-500';
                @endphp
                <span class="{{ $warna }} text-white px-4 py-1 rounded-full text-sm font-semibold">
                    {{ $tk ?? '-' }}
                </span>
            </div>
            <div class="text-center">
                <label class="block text-gray-600 font-medium mb-2 text-sm">Urgensi</label>
                <span class="bg-orange-500 text-white px-4 py-1 rounded-full text-sm font-semibold">Menengah</span>
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
                        @if($aduan->perbaikan && $aduan->perbaikan->biaya && $aduan->perbaikan->biaya->count())
                            @foreach($aduan->perbaikan->biaya as $i => $biaya)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-300 px-3 py-2 text-center">{{ $i + 1 }}</td>
                                    <td class="border border-gray-300 px-3 py-2">{{ $biaya->keterangan }}</td>
                                    <td class="border border-gray-300 px-3 py-2 text-right">
                                        {{ number_format($biaya->besaran, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" class="text-center text-gray-400">Tidak ada rincian anggaran.</td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-100 font-semibold">
                            <td colspan="2" class="border border-gray-300 px-3 py-2 text-right">Total (Rp):</td>
                            <td class="border border-gray-300 px-3 py-2 text-right">
                                {{ $aduan->perbaikan && $aduan->perbaikan->biaya ? number_format($aduan->perbaikan->biaya->sum('besaran'), 0, ',', '.') : 0 }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <hr class="border-gray-300 my-6">

    <!-- Hasil Perbaikan -->
    <div class="mb-6">
        <div class="flex items-center mb-4">
            <div class="bg-blue-500 text-white px-3 py-2 rounded-md mr-3">
                <i class="fas fa-tools"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Hasil Perbaikan</h3>
        </div>
        <div class="w-16 h-0.5 bg-orange-400 mb-4"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Gambar Hasil Perbaikan -->
            <div class="flex justify-center">
                @if($aduan->perbaikan && $aduan->perbaikan->foto)
                    <img src="{{ asset('storage/' . $aduan->perbaikan->foto) }}" alt="Foto Perbaikan"
                        class="w-48 h-32 object-cover rounded-lg shadow">
                @else
                    <img src="{{ asset('img/no-image.svg') }}" alt="No Image"
                        class="w-48 h-32 object-cover rounded-lg shadow">
                @endif
            </div>

            <!-- Detail Hasil Perbaikan -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Selesai Perbaikan</label>
                    <p class="text-gray-800 font-semibold">
                        {{ $aduan->perbaikan && $aduan->perbaikan->tanggal_selesai ? \Carbon\Carbon::parse($aduan->perbaikan->tanggal_selesai)->format('d/m/Y') : '-' }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Deskripsi Perbaikan</label>
                    <p class="text-gray-700 text-sm leading-relaxed">
                        {{ $aduan->perbaikan->deskripsi_perbaikan ?? '-' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <hr class="border-gray-300 my-6">

    <!-- Umpan Balik -->
    <div class="mb-6">
        <div class="flex items-center mb-4">
            <div class="bg-blue-500 text-white px-3 py-2 rounded-md mr-3">
                <i class="fas fa-comment-dots"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Umpan Balik Pelanggan</h3>
        </div>
        <div class="w-16 h-0.5 bg-orange-400 mb-4"></div>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-2">Umpan Balik</label>
                @if($aduan->umpan_balik && $aduan->umpan_balik->rating)
                    <div class="flex items-center space-x-1 mb-2">
                        @for($i = 1; $i <= $a->umpan_balik->rating; $i++)
                            <i class="fas fa-star text-yellow-400 text-lg"></i>
                        @endfor
                    </div>
                    <p class="text-gray-800 font-semibold">{{ $aduan->umpan_balik->keterangan ?? '-' }}</p>
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