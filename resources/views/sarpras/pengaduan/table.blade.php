<x-table>
    <x-slot name="head">
        <x-table.heading>No</x-table.heading>
        <x-table.heading>Kode Fasilitas</x-table.heading>
        <x-table.heading>Nama Fasilitas</x-table.heading>
        <x-table.heading>Kategori</x-table.heading>
        <x-table.heading>Lokasi</x-table.heading>
        <x-table.heading>Urgensi</x-table.heading>
        <x-table.heading>Jumlah Pelapor</x-table.heading>
        <x-table.heading>Aksi</x-table.heading>
    </x-slot>

    <x-slot name="body">
        @forelse ($pengaduan as $index => $p)
            <x-table.row>
                <x-table.cell>{{ $pengaduan->firstItem() + $index }}</x-table.cell>
                <x-table.cell>{{ strtoupper($p->kode_fasilitas) }}</x-table.cell>
                <x-table.cell>{{ $p->nama_fasilitas }}</x-table.cell>
                <x-table.cell>{{ ucfirst($p->kategori->nama_kategori ?? '-')}}</x-table.cell>
                @php
                    $ruangan = $p->ruangan;
                    $lantai = $ruangan->lantai;
                    $gedung = $lantai->gedung;
                @endphp
                <x-table.cell>
                    {{ $gedung->nama_gedung ?? '-' }}
                    {{ $lantai ? ', ' . $lantai->nama_lantai : '' }}
                    {{ $ruangan ? ', ' . $ruangan->kode_ruangan : '' }}
                </x-table.cell>
                <x-table.cell>
                    <div class="flex flex-col items-center font-semibold">
                        @if($p->urgensi)
                            <span class="py-1 rounded-full text-white text-sm
                                            @if($p->urgensi === \App\Http\Enums\Urgensi::DARURAT)
                                                px-3 bg-red-500
                                            @elseif($p->urgensi === \App\Http\Enums\Urgensi::PENTING)
                                                px-3 bg-yellow-500
                                            @elseif($p->urgensi === \App\Http\Enums\Urgensi::BIASA)
                                                px-6 bg-blue-500
                                            @else
                                                px-3 bg-gray-500
                                            @endif
                                        ">
                                {{ Str::ucfirst(Str::lower($p->urgensi->value)) }}
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-gray-500 text-white text-sm">-</span>
                        @endif
                    </div>
                </x-table.cell>
                <x-table.cell>{{ $p->aduan_count ?? '-' }}</x-table.cell>
                <x-table.cell>
                    <div class="flex items-center space-x-2 min-w-[120px]">
                    <button onclick="modalAction('{{ route('sarpras.pengaduan.show', $p->id_fasilitas) }}')"
                        class="text-blue-600 hover:underline text-sm cursor-pointer">
                        <img src="{{ asset('icons/solid/Detail.svg') }}" alt="" class="h-7 w-7 inline">
                    </button>
                    <button onclick="modalAction('{{ route('sarpras.pengaduan.edit', $p->id_fasilitas) }}')"
                        class="text-yellow-600 hover:underline text-sm cursor-pointer">
                        <img src="{{ asset('icons/crud/Case.svg') }}" alt="" class="h-7 w-7 inline">
                    </button>
                    </div>
                </x-table.cell>
            </x-table.row>
        @empty
            <tr class="border-1">
                <td colspan="9" class="text-center text-gray-500 py-4">
                    Tidak ada data Aduan{{ $pelapor }}.
                </td>
            </tr>
        @endforelse
    </x-slot>
</x-table>

<div class="mt-4">
    {{ $pengaduan->links() }}
</div>