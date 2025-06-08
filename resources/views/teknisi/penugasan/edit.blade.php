<div class="bg-white rounded-lg shadow-lg max-w-3xl w-full p-6 relative space-y-6 border-t-4 border-blue-600 max-h-screen overflow-y-auto">
    {{-- Tombol Close --}}
    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl">
        <i class="fas fa-times"></i>
    </button>

    {{-- Judul --}}
    <h2 class="text-xl font-semibold text-center">Edit Penugasan</h2>
    <div class="w-24 h-1 bg-yellow-400 mx-auto mb-6 rounded"></div>

    <form action="{{ route('teknisi.penugasan.update_ajax', $inspeksi->id_inspeksi) }}" method="POST">
    @csrf
    @method('PUT')

        {{-- 1. Detail Fasilitas --}}
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

        {{-- 2. Hasil Inspeksi --}}
        <div class="border border-dashed rounded-md p-4">
            <h2 class="font-semibold flex items-center space-x-2 mb-4">
                <i class="fa-solid fa-address-card" style="color: #0342b0;"></i>
                <span>Hasil Inspeksi</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 text-sm text-gray-700">
                <div>
                    <p class="text-gray-500">Tingkat Kerusakan</p>
                    <select name="tingkat_kerusakan" id="tingkat_kerusakan" class="w-full mt-2 p-2 border rounded">
                        <option value="PARAH" {{ $inspeksi->tingkat_kerusakan === 'PARAH' ? 'selected' : '' }}>Parah</option>
                        <option value="SEDANG" {{ $inspeksi->tingkat_kerusakan === 'SEDANG' ? 'selected' : '' }}>Sedang</option>
                        <option value="RINGAN" {{ $inspeksi->tingkat_kerusakan === 'RINGAN' ? 'selected' : '' }}>Ringan</option>
                    </select>
                </div>
                <div>
                    <p class="text-gray-500">Deskripsi Pekerjaan</p>
                    <textarea name="deskripsi_pekerjaan" id="deskripsi_pekerjaan" rows="4"
                              class="w-full mt-2 p-2 border rounded">{{ $inspeksi->perbaikan->deskripsi ?? '' }}</textarea>
                </div>
            </div>
        </div>

        {{-- 3. Rincian Anggaran Perbaikan --}}
        <div class="border border-dashed rounded-md p-4 mt-6">
            <h2 class="font-semibold flex items-center space-x-2 mb-4">
                <i class="fa-solid fa-money-bill" style="color: #0342b0;"></i>
                <span>Rincian Anggaran Perbaikan</span>
            </h2>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border border-gray-300 rounded-lg">
                    <thead class="bg-blue-50 text-blue-800">
                        <tr>
                            <th class="border p-2 text-left">No</th>
                            <th class="border p-2 text-left">Keterangan</th>
                            <th class="border p-2 text-right">Biaya (Rp)</th>
                            <th class="border p-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="anggaran-body">
                        @forelse ($biaya as $index => $item)
                            <tr>
                                <td class="border p-2">{{ $index + 1 }}</td>
                                <td class="border p-2">
                                    <input type="text" name="biaya[{{ $index }}][keterangan]" value="{{ $item->keterangan }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                                </td>
                                <td class="border p-2 text-right">
                                    <input type="number" name="biaya[{{ $index }}][besaran]" value="{{ $item->besaran }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded text-right focus:outline-none focus:ring-1 focus:ring-blue-500">
                                </td>
                                <td class="border p-2 text-center">
                                    <button type="button" onclick="removeRow(this)" class="text-red-600 hover:underline">Hapus</button>
                                </td>
                            </tr>
                        @empty
                            <tr id="empty-row">
                                <td colspan="4" class="border p-4 text-center text-gray-500">Tidak ada rincian anggaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="border p-2 text-center">
                                <button type="button" onclick="addRow()" class="text-blue-600 hover:underline font-semibold">+ Tambah Baris</button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="text-right mt-4 font-semibold text-blue-600">
                Total: Rp <span id="total-anggaran">{{ number_format($biaya->sum('besaran'), 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Tombol Simpan --}}
        <div class="text-right mt-6">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Simpan</button>
        </div>
    </form>
</div>
<script>
    function formatRupiah(number) {
        return number.toLocaleString('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).replace('Rp', 'Rp ');
    }

    function formatInputRupiah(input) {
        let value = input.value.replace(/[^,\d]/g, '');
        let number = parseInt(value) || 0;
        input.value = 'Rp ' + number.toLocaleString('id-ID');
        hitungTotalAnggaran(); // Pastikan total juga diperbarui
    }

    function hitungTotalAnggaran() {
        let total = 0;
        document.querySelectorAll('input[name*="[besaran]"]').forEach(input => {
            total += parseFloat(input.value.replace(/[^0-9]/g, '')) || 0;
        });
        document.getElementById('total-anggaran').textContent = formatRupiah(total);
    }

    function addRow() {
        const tbody = document.getElementById('anggaran-body');
        const index = tbody.children.length;
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="border p-2">${index + 1}</td>
            <td class="border p-2">
                <input type="text" name="biaya[${index}][keterangan]" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
            </td>
            <td class="border p-2 text-right">
                <input type="text" name="biaya[${index}][besaran]" class="biaya-input w-full px-3 py-2 border rounded text-right focus:outline-none focus:ring-1 focus:ring-blue-500" oninput="formatInputRupiah(this)">
            </td>
            <td class="border p-2 text-center">
                <button type="button" onclick="removeRow(this)" class="text-red-600 hover:underline">Hapus</button>
            </td>
        `;
        document.getElementById('empty-row')?.remove();
        tbody.appendChild(tr);
    }

    function removeRow(button) {
        const row = button.closest('tr');
        row.remove();
        hitungTotalAnggaran();
        // Optional: perbarui nomor baris
        document.querySelectorAll('#anggaran-body tr').forEach((tr, i) => {
            tr.children[0].textContent = i + 1;
        });
    }

    // Hitung total awal saat halaman dimuat
    document.addEventListener('DOMContentLoaded', hitungTotalAnggaran);
</script>
