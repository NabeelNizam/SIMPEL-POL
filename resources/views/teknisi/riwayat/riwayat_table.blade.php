<x-table>
    <x-slot name="head">
        <x-table.heading>No</x-table.heading>
        <x-table.heading>Nama Fasilitas</x-table.heading>
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
                <x-table.cell>{{ $a->fasilitas->kategori->nama_kategori ?? '-' }}</x-table.cell>
                <x-table.cell>{{ $a->perbaikan->tanggal_mulai ?? '-'}}</x-table.cell>
                <x-table.cell>{{ $a->perbaikan->tanggal_selesai ?? '-' }}</x-table.cell>
                <x-table.cell>
                    <button onclick="modalAction('{{ route('teknisi.riwayat.show_ajax', $a->id_aduan) }}')" class="text-blue-600 hover:underline text-sm">
                        <img src="{{ asset('icons/solid/Detail.svg') }}" alt="Detail" class="h-7 w-7 inline">
                    </button>
                </x-table.cell>
            </x-table.row>
        @empty
            <x-table.row>
                <x-table.cell colspan="5" class="text-center text-gray-500">Tidak ada data aduan dengan status selesai.</x-table.cell>
            </x-table.row>
        @endforelse
    </x-slot>
</x-table>

<div class="mt-4">
    {{ $aduan->links() }}
</div>