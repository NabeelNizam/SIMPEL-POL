<div
    class="bg-white rounded-lg shadow-lg max-w-3xl w-full p-6 pt-0 relative  max-h-[80vh] overflow-y-auto space-y-6 border-t-4 border-blue-600">

    {{-- header modal --}}
    <div class="bg-white sticky top-0 z-10 py-3 mb-3">
        {{-- Tombol Close --}}
        <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl cursor-pointer">
            <i class="fas fa-times"></i>
        </button>

        <h2 class="text-xl font-semibold text-center">Detail Laporan</h2>
        <div class="w-24 h-1 bg-yellow-400 mx-auto mt-3 mb-6 rounded"></div>
    </div>
    {{-- 1. Detail Fasilitas --}}
    <div>
        <h2 class="font-semibold flex items-center space-x-2 mb-4 border-b pb-2">
            <i class="fa-solid fa-file" style="color: #0342b0;"></i>
            <span>Detail Fasilitas</span>
        </h2>

        <div class="flex flex-col lg:flex-row gap-6">
            <div class="lg:w-48">
                <img src="{{ asset($aduan->fasilitas->gambar ?? 'img/no-image.svg') }}" alt="Gambar Fasilitas"
                    class="w-full h-32 object-cover rounded-lg border">
                <div class="mt-2">
                    <p class="font-semibold text-black-700">{{ $aduan->fasilitas->nama_fasilitas ?? '-' }}</p>
                    <p class="text-gray-700">{{ ucwords($aduan->fasilitas->kategori->nama_kategori) ?? '-' }}</p>
                </div>
            </div>
            <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Lokasi</p>
                    <p class="mt-2 font-semibold">
                        {{ $aduan->fasilitas->ruangan->lantai->gedung->nama_gedung ?? '-' }},
                        {{ $aduan->fasilitas->ruangan->lantai->nama_lantai ?? '-' }},
                        {{ $aduan->fasilitas->ruangan->nama_ruangan ?? '-' }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">Tanggal Mulai Perbaikan</p>
                    <p class="mt-2 font-semibold">
                        {{ $perbaikan->tanggal_mulai ? \Carbon\Carbon::parse($perbaikan->tanggal_mulai)->format('d-m-Y') : '-' }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">Urgensi</p>
                    <span class="inline-block mt-2 px-8 py-1 text-xs font-semibold text-white bg-red-600 rounded-lg">
                        {{ Str::ucfirst(Str::lower($aduan->fasilitas->urgensi->value)) ?? '-' }}
                    </span>
                </div>
                <div>
                    <p class="text-gray-500">Tanggal Selesai Perbaikan</p>
                    <p class="mt-2 font-semibold">
                        {{ $perbaikan->tanggal_selesai ? \Carbon\Carbon::parse($perbaikan->tanggal_selesai)->format('d-m-Y') : '-' }}
                    </p>
                </div>
                <div class="sm:col-span-2">
                    <p class="text-gray-500">Status</p>
                    <span class="inline-block mt-2 px-10 py-1 text-xs font-semibold text-white bg-green-600 rounded-lg">
                        {{ $aduan->status->value ?? '-' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Hasil Inspeksi --}}
    <div class="border border-dashed rounded-md p-4">
        <h2 class="font-semibold flex items-center space-x-2 mb-4">
            <i class="fa-solid fa-address-card" style="color: #0342b0;"></i>
            <span>Hasil Inspeksi</span>
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700">
            <div>
                <p class="text-gray-500">Tingkat Kerusakan</p>
                <span
                    class="inline-block mt-2 px-8 py-1 text-xs font-semibold text-white bg-red-600 rounded-lg">{{ $aduan->fasilitas->inspeksi->first()->tingkat_kerusakan ?? '-'}}</span>
            </div>
            <div>
                <p class="text-gray-500">Deskripsi Pekerjaan</p>
                <p class="mt-2 font-semibold">{{ $aduan->fasilitas->inspeksi->first()->deskripsi ?? '*Tidak diisi'}}</p>
            </div>
        </div>
    </div>

    {{-- 3. Rincian Anggaran Perbaikan --}}
    <div>
        <h2 class="font-semibold flex items-center space-x-2 mb-4">
            <span>Rincian Anggaran Perbaikan</span>
        </h2>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border border-gray-200">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="border p-2 text-left">No</th>
                        <th class="border p-2 text-left">Keterangan</th>
                        <th class="border p-2 text-right">Biaya (Rp)</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800">
                    @foreach ($biaya as $index => $cost)
                        <tr>
                            <td class="border p-2">{{ $index + 1 }}</td>
                            <td class="border p-2">{{ $cost->keterangan }}</td>
                            <td class="border p-2 text-right">{{ number_format($cost->besaran, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="font-bold bg-gray-50">
                        <td colspan="2" class="border p-2 text-right">Total (Rp):</td>
                        <td class="border p-2 text-right">
                            {{ number_format($biaya->sum('besaran'), 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>