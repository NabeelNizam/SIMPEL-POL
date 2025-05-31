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
                    {{ $a->perbaikan && $a->perbaikan->tanggal_selesai ? \Carbon\Carbon::parse($a->perbaikan->tanggal_selesai)->format('d/m/Y') : '-' }}
                </x-table.cell>
                <x-table.cell>
                    @if($a->umpan_balik && $a->umpan_balik->rating)
                        <div class="flex items-center">
                            @for($i = 1; $i <= $a->umpan_balik->rating; $i++)
                                <i class="fas fa-star text-yellow-400 text-lg"></i>
                            @endfor
                        </div>
                    @else
                        <button onclick="modalAction('{{ route('mahasiswa.riwayat.edit_ajax', $a->id_aduan) }}')"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                            Beri Umpan Balik
                        </button>
                    @endif
                </x-table.cell>
                <x-table.cell>
                    <button onclick="modalAction('{{ route('mahasiswa.riwayat.show_ajax', $a->id_aduan) }}')"
                        class="text-blue-600 hover:underline text-sm">
                        <img src="{{ asset('icons/solid/Detail.svg') }}" alt="" class="h-7 w-7 inline">
                    </button>
                </x-table.cell>
            </x-table.row>
        @empty
            <x-table.row>
                <x-table.cell colspan="9" class="text-center text-gray-500">Tidak ada data Aduan.</x-table.cell>
            </x-table.row>
        @endforelse
    </x-slot>
</x-table>

<div class="mt-4">
    {{ $aduan->links() }}
</div>