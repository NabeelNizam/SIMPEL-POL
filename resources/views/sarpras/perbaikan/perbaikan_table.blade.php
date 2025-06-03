<x-table>
    <x-slot name="head">
        <tr>
            <th class="px-4 py-2 text-left">ID Aduan</th>
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
        @forelse ($aduan as $index => $a)
            <tr>
                <td class="px-4 py-2">{{ $a->id_aduan }}</td>
                <td class="px-4 py-2">{{ $a->fasilitas->nama_fasilitas ?? '-' }}</td>
                <td class="px-4 py-2">{{ $a->fasilitas->kategori->nama_kategori ?? '-' }}</td>
                <td class="px-4 py-2">{{ $a->fasilitas->lokasi ?? '-' }}</td>
                <td class="px-4 py-2">{{ $a->perbaikan->teknisi->nama ?? '-' }}</td>
                <td class="px-4 py-2">{{ $a->perbaikan->tanggal_mulai ?? '-' }}</td>
                <td class="px-4 py-2">{{ $a->perbaikan->tanggal_selesai ?? '-' }}</td>
                <td class="px-4 py-2">
                    <span class="px-2 py-1 rounded text-white {{ $a->status->value === 'SELESAI' ? 'bg-green-500' : 'bg-yellow-500' }}">
                        {{ $a->status->value }}
                    </span>
                </td>
                <td class="px-4 py-2">
                    {{-- <button onclick="modalAction('{{ route('sarpras.perbaikan.show', $a->id_aduan) }}')" class="text-blue-600 hover:underline">
                        <img src="{{ asset('icons/solid/Document.svg') }}" alt="Detail" class="h-7 w-7 inline">
                    </button>
                    <button onclick="modalAction('{{ route('sarpras.perbaikan.approve', $a->id_aduan) }}')" class="text-green-600 hover:underline ml-2">
                        <img src="{{ asset('icons/solid/Acc.svg') }}" alt="Approve" class="h-7 w-7 inline">
                    </button> --}}
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
    {{ $aduan->links() }}
</div>