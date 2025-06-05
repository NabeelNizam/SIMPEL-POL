<x-table>
    <x-slot name="head">
        <x-table.heading>No</x-table.heading>
        <x-table.heading>Nama Fasilitas</x-table.heading>
        <x-table.heading>Kategori</x-table.heading>
        <x-table.heading>Lokasi</x-table.heading>
        <x-table.heading>Urgensi</x-table.heading>
        <x-table.heading>Jumlah Pelapor</x-table.heading>
        <x-table.heading>Tingkat Kerusakan</x-table.heading>
        <x-table.heading>Aksi</x-table.heading>
    </x-slot>

    <x-slot name="body">
        @forelse ($aduan as $index => $item)
            <x-table.row>
                <x-table.cell>{{ $index +1 }}</x-table.cell>
                <x-table.cell>{{ $item->fasilitas->nama_fasilitas ?? '-' }}</x-table.cell>
                <x-table.cell>{{ $item->fasilitas->kategori->nama_kategori ?? '-' }}</x-table.cell>
                <x-table.cell>
                    @php
                        $ruangan = $item->fasilitas->ruangan ?? null;
                        $lantai = $ruangan->lantai ?? null;
                        $gedung = $lantai->gedung ?? null;
                    @endphp
                    {{ $gedung ? $gedung->nama_gedung : '-' }}
                    {{ $lantai ? ', ' . $lantai->nama_lantai : '' }}
                    {{ $ruangan ? ', ' . $ruangan->kode_ruangan : '' }}
                </x-table.cell>
                <x-table.cell>{{ $item->fasilitas->urgensi ?? '-' }}</x-table.cell>
                <x-table.cell>{{ $item->jumlah_pelapor }}</x-table.cell>
                <x-table.cell>{{ $item->perbaikan->tingkat_kerusakan ?? '-' }}</x-table.cell>
                <x-table.cell>
                    {{-- <a href="{{ route('penugasan.show', $item->id) }}" class="text-blue-500 hover:underline">Detail</a> --}}
                </x-table.cell>
            </x-table.row>
        @empty
            <x-table.row>
                <x-table.cell colspan="8" class="text-center">Tidak ada data penugasan</x-table.cell>
            </x-table.row>
        @endforelse
    </x-slot>
</x-table>