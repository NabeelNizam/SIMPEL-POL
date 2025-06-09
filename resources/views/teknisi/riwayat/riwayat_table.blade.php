<x-table>
    <x-slot name="head">
        <x-table.heading>No</x-table.heading>
        <x-table.heading>Nama Fasilitas</x-table.heading>
        <x-table.heading>Lokasi</x-table.heading>
        <x-table.heading>Kategori</x-table.heading>
        <x-table.heading>Tanggal Mulai</x-table.heading>
        <x-table.heading>Tanggal Selesai</x-table.heading>
        <x-table.heading>Aksi</x-table.heading>
    </x-slot>

    <x-slot name="body">
        @forelse ($aduan as $index => $a)
            <x-table.row>
                <x-table.cell>{{ $aduan->firstItem() + $index }}</x-table.cell>
                <x-table.cell>{{ $a->fasilitas->nama_fasilitas ?? '-' }}</x-table.cell>
                @php
                    $ruangan = $a->fasilitas->ruangan;
                    $lantai = $ruangan->lantai;
                    $gedung = $lantai->gedung;
                @endphp
                <x-table.cell>
                    {{ $gedung->nama_gedung ?? '-' }}
                    {{ $lantai ? ', ' . $lantai->nama_lantai : '' }}
                    {{ $ruangan ? ', ' . $ruangan->kode_ruangan : '' }}
                </x-table.cell>
                <x-table.cell>{{ ucwords($a->fasilitas->kategori->nama_kategori) ?? '-' }}</x-table.cell>
                <x-table.cell>{{ $a->tanggal_aduan ?? '-'}}</x-table.cell>
                <x-table.cell>{{ $a->fasilitas->inspeksi->first()->perbaikan->tanggal_selesai ?? '-' }}</x-table.cell>
                <x-table.cell>
                    <button onclick="modalAction('{{ route('teknisi.riwayat.show_ajax', $a->fasilitas->id_fasilitas) }}')"
                        class="text-blue-600 hover:underline text-sm cursor-pointer">
                        <img src="{{ asset('icons/solid/Detail.svg') }}" alt="Detail" class="h-7 w-7 inline">
                    </button>
                </x-table.cell>
            </x-table.row>
        @empty
            <tr class="border-1">
                <td colspan="5" class="text-center text-gray-500 py-4">Tidak ada data Aduan dengan status Selesai.</td>
            </tr>
        @endforelse
    </x-slot>
</x-table>

<div class="mt-4">
    {{ $aduan->links() }}
</div>