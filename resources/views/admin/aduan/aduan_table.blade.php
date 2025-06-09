<x-table>
    <x-slot name="head">
        <x-table.heading>No</x-table.heading>
        <x-table.heading>Periode</x-table.heading>
        <x-table.heading>Nama Fasilitas</x-table.heading>
        <x-table.heading>Kategori</x-table.heading>
        <x-table.heading>Lokasi</x-table.heading>
        <x-table.heading>Tanggal Perbaikan</x-table.heading>
        <x-table.heading>Status Perbaikan</x-table.heading>
        <x-table.heading>Umpan Balik</x-table.heading>
        <x-table.heading>Aksi</x-table.heading>
    </x-slot>

    <x-slot name="body">
        @forelse ($aduan as $index => $a)
            @php
                $fasilitas = $a->fasilitas;
                $periode = $a->id_periode;
                // Ambil semua aduan pada fasilitas & periode ini
                $aduanPeriode = \App\Models\Aduan::where('id_fasilitas', $fasilitas->id_fasilitas)
                    ->where('id_periode', $periode)
                    ->get();
                $lastAduan = $aduanPeriode->sortByDesc('tanggal_aduan')->first();
                $avgRating = \App\Models\UmpanBalik::whereHas('aduan', function ($q) use ($fasilitas, $periode) {
                    $q->where('id_fasilitas', $fasilitas->id_fasilitas)
                        ->where('id_periode', $periode);
                })->avg('rating');
                $avgRating = $avgRating ? number_format($avgRating, 1) : null;
            @endphp
            <x-table.row>
                <x-table.cell>{{ $aduan->firstItem() + $index }}</x-table.cell>
                <x-table.cell>{{ $a->periode->kode_periode ?? '-' }}</x-table.cell>
                <x-table.cell>{{ $fasilitas->nama_fasilitas }}</x-table.cell>
                <x-table.cell>{{ $fasilitas->kategori->nama_kategori ?? '-' }}</x-table.cell>
                <x-table.cell>
                    {{ $fasilitas->ruangan->lantai->gedung->nama_gedung ?? '-' }},
                    {{ $fasilitas->ruangan->lantai->nama_lantai ?? '-' }},
                    {{ $fasilitas->ruangan->nama_ruangan ?? '-' }}
                </x-table.cell>
                <x-table.cell>
                    {{ $lastAduan && $lastAduan->tanggal_perbaikan ? \Carbon\Carbon::parse($lastAduan->tanggal_perbaikan)->format('d/m/Y') : '-' }}
                </x-table.cell>
                <x-table.cell>
                    <span class="px-3 py-1 rounded text-white text-sm w-42 block text-center
                                @if($lastAduan->status === \App\Http\Enums\Status::SELESAI)
                                    bg-green-500
                                @elseif($lastAduan->status === \App\Http\Enums\Status::MENUNGGU_DIPROSES)
                                    bg-blue-500
                                @elseif($lastAduan->status === \App\Http\Enums\Status::SEDANG_INSPEKSI)
                                    bg-yellow-500
                                @else
                                    bg-orange-500
                                @endif">
                        {{ $lastAduan->status->value }}
                    </span>
                </x-table.cell>
                <x-table.cell>
                    @if($avgRating)
                        <div class="flex items-center mb-2 gap-1">
                            <i class="fas fa-star text-yellow-400 text-lg"></i>
                            <span class="text-yellow-500 font-bold text-lg mr-1">{{ $avgRating }}</span>
                            <span class="text-gray-600 text-sm">/5.0</span>
                        </div>
                    @else
                        <span class="text-gray-500">-</span>
                    @endif
                </x-table.cell>
                <x-table.cell>
                    <div class="flex gap-1">
                        <button onclick="modalAction('{{ route('admin.aduan.show_ajax', $fasilitas->id_fasilitas) }}')"
                            class="cursor-pointer text-blue-600 hover:underline text-sm">
                            <img src="{{ asset('icons/solid/Detail.svg') }}" alt="Detail"
                                class="h-7 w-7 min-h-[27px] min-w-[27px]">
                        </button>
                        <button onclick="modalAction('{{ route('admin.aduan.comment_ajax', $fasilitas->id_fasilitas) }}')"
                            class="text-blue-600 hover:underline text-sm">
                            <img src="{{ asset('icons/solid/message.svg') }}" alt="Comment"
                                class="h-7 w-7 min-h-[20px] min-w-[20px]">
                        </button>
                    </div>
                </x-table.cell>
            </x-table.row>
        @empty
            <tr class="border-1">
                <td colspan="9" class="text-center text-gray-500 py-4">Tidak ada data Aduan.</td>
            </tr>
        @endforelse
    </x-slot>
</x-table>

<div class="mt-4">
    {{ $aduan->links() }}
</div>