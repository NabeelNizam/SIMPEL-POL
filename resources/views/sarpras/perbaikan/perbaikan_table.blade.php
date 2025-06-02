<x-table>
    <x-slot name="head">
        <tr>
            <th class="px-4 py-2 text-left">ID</th>
            <th class="px-4 py-2 text-left">Nama Fasilitas</th>
            <th class="px-4 py-2 text-left">Kategori</th>
            <th class="px-4 py-2 text-left">Lokasi</th>
            <th class="px-4 py-2 text-left">Nama Teknisi</th>
            <th class="px-4 py-2 text-left">Tanggal Mulai</th>
            <th class="px-4 py-2 text-left">Tanggal Selesai</th>
            <th class="px-4 py-2 text-left">Status</th>
            <th class="px-4 py-2 text-left">Aksi</th>
        </tr>
    </x-slot>

    <x-slot name="body">
        @forelse ($perbaikan as $index => $p)
            <tr>
                <td class="px-4 py-2">{{ $p->id_perbaikan }}</td>
                <td class="px-4 py-2">{{ $p->aduan->fasilitas->nama_fasilitas ?? '-' }}</td>
                <td class="px-4 py-2">{{ $p->aduan->fasilitas->kategori->nama_kategori ?? '-' }}</td>
                <td class="px-4 py-2">{{ $p->aduan->fasilitas->lokasi ?? '-' }}</td>
                <td class="px-4 py-2">{{ $p->teknisi->nama ?? '-' }}</td>
                <td class="px-4 py-2">{{ $p->tanggal_mulai ?? '-' }}</td>
                <td class="px-4 py-2">{{ $p->tanggal_selesai ?? '-' }}</td>
                <td class="px-4 py-2">
                    <span class="px-2 py-1 rounded text-white {{ $p->aduan->status->value === 'Selesai' ? 'bg-green-500' : 'bg-yellow-500' }}">
                        {{ $p->aduan->status->value }}
                    </span>
                </td>
                <td class="px-4 py-2">
                    <button onclick="modalAction('{{ route('sarpras.perbaikan.show', $p->id_perbaikan) }}')" class="text-blue-600 hover:underline">
                        <img src="{{ asset('icons/solid/Document.svg') }}" alt="Detail" class="h-7 w-7 inline">
                    </button>
                    <button onclick="modalAction('{{ route('sarpras.perbaikan.approve', $p->id_perbaikan) }}')" class="text-green-600 hover:underline ml-2">
                        <img src="{{ asset('icons/solid/Acc.svg') }}" alt="Approve" class="h-7 w-7 inline">
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center text-gray-500">Tidak ada data perbaikan.</td>
            </tr>
        @endforelse
    </x-slot>
</x-table>

<div class="mt-4">
    {{ $perbaikan->links() }}
</div>