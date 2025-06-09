<x-table>
    <x-slot name="head">
        <x-table.heading>No</x-table.heading>
        <x-table.heading>Nama Fasilitas</x-table.heading>
        <x-table.heading>Kategori</x-table.heading>
        <x-table.heading>Lokasi</x-table.heading>
        <x-table.heading>Tanggal Lapor</x-table.heading>
        <x-table.heading>Status Perbaikan</x-table.heading>
        <x-table.heading>Aksi</x-table.heading>
    </x-slot>

    <x-slot name="body">
        @forelse ($aduan as $index => $item)
            <x-table.row>
                <x-table.cell>{{ $aduan->firstItem() + $index }}</x-table.cell>
                <x-table.cell>{{ $item->fasilitas->nama_fasilitas }}</x-table.cell>
                <x-table.cell>{{ $item->fasilitas->kategori->nama_kategori }}</x-table.cell>
                @php
                    $ruangan = $item->fasilitas->ruangan;
                    $lantai = $ruangan->lantai;
                    $gedung = $lantai->gedung;
                @endphp
                <x-table.cell>
                    {{ $gedung->nama_gedung ?? '-' }}
                    {{ $lantai ? ', Lt. ' . $lantai->nama_lantai : '' }}
                    {{ $ruangan ? ', ' . $ruangan->nama_ruangan : '' }}
                </x-table.cell>
                <x-table.cell>{{ $item->tanggal_aduan ? \Carbon\Carbon::parse($item->tanggal_aduan)->format('d/m/Y') : '-'}}</x-table.cell>
                <x-table.cell>
                    <span class="px-3 py-1 rounded text-white text-sm text-center w-42 block
                                @if($item->status === \App\Http\Enums\Status::MENUNGGU_DIPROSES)
                                    bg-blue-500
                                @elseif($item->status === \App\Http\Enums\Status::SEDANG_INSPEKSI)
                                    bg-yellow-500
                                @else
                                    bg-orange-500
                                @endif
                                    ">
                        {{ $item->status->value }}
                    </span>
                </x-table.cell>
                <x-table.cell>
                    <div class="flex gap-2 min-w-[80px]">
                        <button onclick="modalAction('{{ route('mahasiswa.form.show_ajax', $item->id_aduan) }}')"
                            class="text-blue-600 hover:underline text-sm cursor-pointer">
                            <img src="{{ asset('icons/solid/Detail.svg') }}" alt="" class="h-7 w-7 min-h-[28px] min-w-[28px] inline">
                        </button>
                        @if($item->status === \App\Http\Enums\Status::MENUNGGU_DIPROSES)
                            <button onclick="modalAction('{{ route('mahasiswa.form.edit', $item->id_aduan) }}')"
                                class="text-blue-600 hover:underline text-sm ml-2 cursor-pointer">
                                <img src="{{ asset('icons/solid/Edit.svg') }}" alt="" class="h-7 w-7 min-h-[28px] min-w-[28px] inline">
                            </button>
                        @else
                            <button disabled class="text-gray-400 cursor-not-allowed text-sm ml-2 opacity-60">
                                <img src="{{ asset('icons/solid/Edit.svg') }}" alt=""
                                    class="h-7 w-7 min-h-[28px] min-w-[28px] inline filter grayscale brightness-75">
                            </button>
                        @endif
                    </div>
                </x-table.cell>
            </x-table.row>
        @empty
            <tr class="border-1">
                <td colspan="6" class="text-center text-gray-500 py-4">Tidak ada data aduan.</td>
            </tr>
        @endforelse
    </x-slot>
</x-table>

<div class="mt-4">
    {{ $aduan->links() }}
</div>