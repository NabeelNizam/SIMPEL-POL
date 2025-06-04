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
                    @if($item->status)
                        <span class="px-3 py-1 rounded-full text-white text-sm
                            @if($item->status === \App\Http\Enums\Status::SELESAI)
                                bg-green-500
                            @elseif($item->status === \App\Http\Enums\Status::MENUNGGU_DIPROSES)
                                bg-blue-500
                            @elseif($item->status === \App\Http\Enums\Status::SEDANG_INSPEKSI)
                                bg-yellow-500
                            @elseif($item->status === \App\Http\Enums\Status::SEDANG_DIPERBAIKI)
                                bg-orange-500
                            @else
                                bg-gray-500
                            @endif
                        ">
                            {{ $item->status->value }}
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-gray-500 text-white text-sm">-</span>
                    @endif
                </x-table.cell>
                <x-table.cell>
                    <button onclick="modalAction('{{ route('mahasiswa.form.show_ajax', $item->id_aduan) }}')" class="text-blue-600 hover:underline text-sm">
                        <img src="{{ asset('icons/solid/Detail.svg') }}" alt="" class="h-7 w-7 inline">
                    </button>
                    <button onclick="modalAction('{{ route('mahasiswa.form.edit_ajax', $item->id_aduan) }}')" class="text-blue-600 hover:underline text-sm ml-2">
                        <img src="{{ asset('icons/solid/Edit.svg') }}" alt="" class="h-7 w-7 inline">
                    </button>
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

    