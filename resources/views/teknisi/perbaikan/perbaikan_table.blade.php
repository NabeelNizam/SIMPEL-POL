<x-table>
    <x-slot name="head">
        <x-table.heading>No</x-table.heading>
        <x-table.heading>Nama Fasilitas</x-table.heading>
        <x-table.heading>Kategori</x-table.heading>
        <x-table.heading>Lokasi</x-table.heading>
        <x-table.heading>Tanggal Mulai</x-table.heading>
        <x-table.heading>Tanggal Selesai</x-table.heading>
        <x-table.heading>Status</x-table.heading>
        <x-table.heading>Aksi</x-table.heading>
    </x-slot>

    <x-slot name="body">
        @forelse ($perbaikan as $index => $a)
            <x-table.row>
                <x-table.cell>{{ $perbaikan->firstItem() + $index }}</x-table.cell>
                <x-table.cell>{{ $a->inspeksi->fasilitas->nama_fasilitas ?? '-' }}</x-table.cell>
                <x-table.cell>{{ $a->inspeksi->fasilitas->kategori->nama_kategori ?? '-' }}</x-table.cell>
                <x-table.cell>{{ $a->inspeksi->fasilitas->lokasi ?? '-' }}</x-table.cell>
                <x-table.cell>{{ $a->tanggal_mulai ?? '-' }}</x-table.cell>
                <x-table.cell>{{ $a->tanggal_selesai ?? '-' }}</x-table.cell>
                <x-table.cell>
                    <span
                        class="px-2 py-1 rounded text-white {{ $a->status === 'SELESAI' ? 'bg-green-500' : 'bg-yellow-500' }}">
                        {{ $a->status }}
                    </span>
                </x-table.cell>
                <x-table.cell class="px-4 py-2">
                    <button onclick="modalAction('{{ route('teknisi.perbaikan.show', $a->id_perbaikan) }}')"
                        class="text-blue-600 hover:underline text-sm">
                        <img src="{{ asset('icons/solid/Detail.svg') }}" alt="" class="h-7 w-7 inline"></button>
                    <button onclick="modalAction('{{ route('teknisi.perbaikan.approve', $a->id_perbaikan) }}')"
                        class="text-green-600 hover:underline ml-2">
                        <img src="{{ asset('icons/solid/Acc.svg') }}" alt="Approve" class="h-7 w-7 inline">
                    </button>
                </x-table.cell>
            </x-table.row>
        @empty
            <tr class="border-1">
                <td colspan="9" class="text-center text-gray-500">Tidak ada data perbaikan.</td>
            </tr>
        @endforelse
    </x-slot>
</x-table>

<div class="mt-4">
    {{ $perbaikan->links() }}
</div>
