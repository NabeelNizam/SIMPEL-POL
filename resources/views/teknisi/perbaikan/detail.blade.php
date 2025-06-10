<div>
    <!-- I have not failed. I've just found 10,000 ways that won't work. - Thomas Edison -->
</div>
<div class="bg-white rounded-lg shadow-lg max-w-3xl w-full p-6 relative space-y-6 border-t-4 border-blue-600 max-h-[90vh] overflow-y-auto">

    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl">
        <i class="fas fa-times"></i>
    </button>

    <h2 class="text-xl font-semibold text-center">Detail Perbaikan</h2>
    <div class="w-24 h-1 bg-yellow-400 mx-auto mb-6 rounded"></div>

    <div>
        <h2 class="font-semibold flex items-center space-x-2 mb-4 border-b pb-2">
            <i class="fa-solid fa-file" style="color: #0342b0;"></i>
            <span>Detail Fasilitas</span>
        </h2>

        <div class="flex flex-col lg:flex-row gap-6">
            <div class="lg:w-48">
                <img src="{{ asset($fasilitas->gambar ?? 'img/no-image.svg') }}"
                    alt="Gambar Fasilitas"
                    class="w-full h-32 object-cover rounded-lg border">
                <div class="mt-2">
                    <p class="font-semibold text-black-700">{{ $fasilitas->nama_fasilitas ?? '-' }}</p>
                    <p class="text-gray-700">{{ ucwords($fasilitas->kategori->nama_kategori ?? '-') }}</p>
                </div>
            </div>
            <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Lokasi</p>
                    <p class="mt-2 font-semibold">
                        {{ $fasilitas->ruangan->lantai->gedung->nama_gedung ?? '-' }},
                        {{ $fasilitas->ruangan->lantai->nama_lantai ?? '-' }},
                        {{ $fasilitas->ruangan->nama_ruangan ?? '-' }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">Urgensi</p>
                    <span class="inline-block mt-2 px-8 py-1 text-xs font-semibold text-white bg-red-600 rounded-lg">
                        {{ Str::ucfirst(Str::lower($fasilitas->urgensi->value ?? '-')) }}
                    </span>
                </div>
                <div>
                    <p class="text-gray-500">Status Aduan</p>
                    <span class="inline-block mt-2 px-8 py-1 text-xs font-semibold text-white bg-blue-600 rounded-lg">
                        {{ $statusAduan }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="border border-dashed rounded-md p-4">
        <h2 class="font-semibold flex items-center space-x-2 mb-4">
            <i class="fa-solid fa-address-card" style="color: #0342b0;"></i>
            <span>Hasil Inspeksi</span>
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700">
            <div>
                <p class="text-gray-500">Tingkat Kerusakan</p>
                <span class="inline-block mt-2 px-8 py-1 text-xs font-semibold text-white bg-yellow-600 rounded-lg">
                    {{ Str::ucfirst(Str::lower($inspeksi->tingkat_kerusakan->value ?? '-')) }}
                </span>
            </div>
            <div>
                <p class="text-gray-500">Deskripsi Pekerjaan</p>
                <p class="mt-2 font-semibold">{{ $inspeksi->deskripsi ?? '-' }}</p>
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
                    @forelse ($inspeksi->biaya as $index => $item)
                        <tr>
                            <td class="border p-2">{{ $index + 1 }}</td>
                            <td class="border p-2">{{ $item->keterangan }}</td>
                            <td class="border p-2 text-right">{{ number_format($item->besaran, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="border p-2 text-center">Tidak ada rincian anggaran.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($inspeksi->biaya->isNotEmpty())
                    <tfoot>
                        <tr class="font-bold bg-gray-50">
                            <td colspan="2" class="border p-2 text-right">Total (Rp):</td>
                            <td class="border p-2 text-right">
                                {{ number_format($biaya->sum('besaran'), 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
