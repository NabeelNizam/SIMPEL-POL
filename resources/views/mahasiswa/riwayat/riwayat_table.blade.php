<x-table>
    <x-slot name="head">
        <x-table.heading>No</x-table.heading>
        <x-table.heading>Nama Fasilitas</x-table.heading>
        <x-table.heading>Kategori</x-table.heading>
        <x-table.heading>Lokasi</x-table.heading>
        <x-table.heading>Tanggal Lapor</x-table.heading>
        <x-table.heading>Tanggal Perbaikan</x-table.heading>
        <x-table.heading>Umpan Balik</x-table.heading>
        <x-table.heading>Aksi</x-table.heading>
    </x-slot>

    <x-slot name="body">
        @forelse ($aduan as $index => $a)
            <x-table.row>
                <x-table.cell>{{ $aduan->firstItem() + $index }}</x-table.cell>
                <x-table.cell>{{ $a->fasilitas->nama_fasilitas }}</x-table.cell>
                <x-table.cell>{{ $a->fasilitas->kategori->nama_kategori ?? '-' }}</x-table.cell>
                @php
                    $ruangan = $a->fasilitas->ruangan;
                    $lantai = $ruangan->lantai;
                    $gedung = $lantai->gedung;
                @endphp
                <x-table.cell>
                    {{ $gedung->nama_gedung ?? '-' }}
                    {{ $lantai ? ', Lt. ' . $lantai->nama_lantai : '' }}
                    {{ $ruangan ? ', ' . $ruangan->nama_ruangan : '' }}
                </x-table.cell>
                <x-table.cell>
                    {{ $a->tanggal_aduan ? \Carbon\Carbon::parse($a->tanggal_aduan)->format('d/m/Y') : '-'}}
                </x-table.cell>
                <x-table.cell>
                    {{ $a->tanggal_perbaikan ? \Carbon\Carbon::parse($a->tanggal_perbaikan)->format('d/m/Y') : '-' }}
                </x-table.cell>
                <x-table.cell>
                    @if($a->umpan_balik && $a->umpan_balik->rating)
                        <div class="flex flex-col items-center">
                            <div class="flex items-center">
                                @for($i = 1; $i <= $a->umpan_balik->rating; $i++)
                                    <i class="fas fa-star text-yellow-400 text-lg"></i>
                                @endfor
                            </div>
                            <div class="text-xs text-gray-700 mt-1 font-semibold">
                                {{ number_format($a->umpan_balik->rating, 0) }} / 5
                            </div>
                        </div>
                    @else
                        -
                    @endif
                </x-table.cell>
                <x-table.cell>
                    <div class="flex gap-2">
                        <button onclick="modalAction('{{ route('mahasiswa.riwayat.show_ajax', $a->id_aduan) }}')"
                            class="text-blue-600 hover:underline text-sm cursor-pointer">
                            <img src="{{ asset('icons/solid/Detail.svg') }}" alt="" class="h-7 w-7 min-h-[23px] min-w-[23px] inline">
                        </button>

                        @if($a->umpan_balik && $a->umpan_balik->rating)
                            <button disabled class="text-gray-400 cursor-not-allowed text-sm opacity-60 cursor-not-allowed cursor-pointer">
                                <img src="{{ asset('icons/solid/message.svg') }}" alt=""
                                    class="h-7 w-7 min-h-[23px] min-w-[23px] inline filter grayscale brightness-75">
                            </button>
                        @else
                            <button onclick="modalAction('{{ route('mahasiswa.riwayat.edit', $a->id_aduan) }}')"
                                class="text-blue-600 hover:underline text-sm cursor-pointer">
                                <img src="{{ asset('icons/solid/message.svg') }}" alt="" class="h-7 w-7 min-h-[23px] min-w-[23px] inline">
                            </button>
                        @endif
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