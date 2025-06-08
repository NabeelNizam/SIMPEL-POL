<x-table>
    <x-slot name="head">
        <tr>
            <x-table.heading>No</x-table.heading>
            <x-table.heading>Nama Fasilitas</x-table.heading>
            <x-table.heading>Kategori</x-table.heading>
            <x-table.heading>Lokasi</x-table.heading>
            <x-table.heading>Nama Teknisi</x-table.heading>
            <x-table.heading>Tanggal Mulai</x-table.heading>
            <x-table.heading>Tanggal Selesai</x-table.heading>
            <x-table.heading>Status Perbaikan</x-table.heading>
            <x-table.heading>Aksi</x-table.heading>
        </tr>
    </x-slot>

    <x-slot name="body">
        @forelse ($perbaikan as $index => $p)
            <x-table.row>
                <x-table.cell>{{ $perbaikan->firstItem() + $index }}</x-table.cell>
                <x-table.cell>{{ $p->nama_fasilitas ?? '-' }}</x-table.cell>
                <x-table.cell>{{ ucfirst($p->kategori->nama_kategori) ?? '-' }}</x-table.cell>
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
                <x-table.cell>{{ $p->inspeksi->first()->teknisi->nama ?? '-' }}</x-table.cell>
                <x-table.cell>
                    <div class="flex flex-col items-center">
                        {{ $p->inspeksi->first()->perbaikan->tanggal_mulai ?? '-' }}
                    </div>
                </x-table.cell>
                <x-table.cell>
                    <div class="flex flex-col items-center">
                        {{ $p->inspeksi->first()->perbaikan->tanggal_selesai ?? '-' }}
                    </div>

                </x-table.cell>
                @php
                    $isCompleted = !empty($p->inspeksi->first()->perbaikan->tanggal_selesai);
                @endphp
                <x-table.cell class="text-center">
                    <div class="flex text-center space-x-2 min-w-[130px]">
                        <span
                            class="py-1 rounded-lg text-white text-sm font-semibold {{ $isCompleted ? 'bg-green-500 px-11' : 'bg-yellow-500 px-3' }}">
                            {{ $isCompleted ? 'Selesai' : 'Dalam Perbaikan' }}
                        </span>
                    </div>
                </x-table.cell>
                <x-table.cell>
                    <div class="flex items-center space-x-2 min-w-[80px]">
                        <button onclick="modalAction('{{ route('sarpras.perbaikan.show', $p->id_fasilitas) }}')"
                            class="text-blue-600 hover:underline cursor-pointer">
                            <img src="{{ asset('icons/solid/Detail.svg') }}" alt="Detail" class="h-7 w-7 inline">
                        </button>
                        <button onclick="modalAction('{{ route('sarpras.perbaikan.confirm_approval', $p->id_fasilitas) }}')"
                            class="text-gray-600 hover:underline {{ $isCompleted ? 'cursor-pointer' : 'cursor-not-allowed' }}">
                            <img src="{{ asset($isCompleted ? 'icons/solid/Acc.svg' : 'icons/light/Acc.svg') }}"
                                alt="Approve" class="h-7 w-7 inline">
                        </button>
                    </div>
                </x-table.cell>
            </x-table.row>
        @empty
            <tr class="border-1">
                <td colspan="9" class="text-center text-gray-500 py-4">Tidak ada Data Perbaikan yang Sedang Diperbaiki.</td>
            </tr>
        @endforelse
    </x-slot>
</x-table>

<div class="mt-4">
    {{ $perbaikan->links() }}
</div>