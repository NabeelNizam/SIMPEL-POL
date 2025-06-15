<x-table>
    <x-slot name="head">
        <x-table.heading>No</x-table.heading>
        <x-table.heading>Periode</x-table.heading>
        <x-table.heading>Kode Fasilitas</x-table.heading>
        <x-table.heading>Nama Fasilitas</x-table.heading>
        <x-table.heading>Lokasi</x-table.heading>
        <x-table.heading>Tanggal Perbaikan</x-table.heading>
        <x-table.heading>Umpan Balik</x-table.heading>
        <x-table.heading>Aksi</x-table.heading>
    </x-slot>

    <x-slot name="body">
        @forelse ($perbaikan as $index => $p)
            <x-table.row>
                <x-table.cell>{{ $perbaikan->firstItem() + $index }}</x-table.cell>
                <x-table.cell>{{ $p->periode->kode_periode}}</x-table.cell>
                <x-table.cell>{{ $p->inspeksi->fasilitas->kode_fasilitas}}</x-table.cell>
                <x-table.cell>{{ $p->inspeksi->fasilitas->nama_fasilitas }}</x-table.cell>
                <x-table.cell>
                    {{ $p->inspeksi->fasilitas->lokasi ?? '-' }}
                </x-table.cell>
                <x-table.cell>{{ $p->tanggal_selesai ?? '-' }}</x-table.cell>
                <x-table.cell>
                    @if($p->rata_rata_rating)
                        <div class="flex items-center mb-2 gap-1">
                            <i class="fas fa-star text-yellow-400 text-lg"></i>
                            <span class="text-yellow-500 font-bold text-lg">{{ number_format($p->rata_rata_rating, 1) }}</span>
                            <span class="text-gray-600 text-sm"> / 5.0</span>
                        </div>
                    @else
                        <span class="text-gray-500">-</span>
                    @endif
                </x-table.cell>
                <x-table.cell>
                    <div class="flex gap-1">
                        <button onclick="modalAction('{{ route('sarpras.riwayat.show_ajax', $p->id_perbaikan) }}')"
                            class="cursor-pointer text-blue-600 hover:underline text-sm">
                            <img src="{{ asset('icons/solid/Detail.svg') }}" alt="Detail"
                                class="h-7 w-7 min-h-[27px] min-w-[27px]">
                        </button>
                        <button onclick="modalAction('{{ route('sarpras.riwayat.comment_ajax', $p->id_perbaikan) }}')"
                            class="cursor-pointer text-blue-600 hover:underline text-sm">
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
    {{ $perbaikan->links() }}
</div>