<x-table>
    <x-slot name="head">
        <x-table.heading>ID</x-table.heading>
        <x-table.heading>Nama Fasilitas</x-table.heading>
        <x-table.heading>Kategori</x-table.heading>
        <x-table.heading>Lokasi</x-table.heading>
        <x-table.heading>Urgensi</x-table.heading>
        <x-table.heading>Tingkat Kerusakan</x-table.heading>
        <x-table.heading>Aksi</x-table.heading>
    </x-slot>

    <x-slot name="body">
        @forelse ($penugasan as $item)
            <x-table.row>
                <x-table.cell>{{ $item->id_inspeksi }}</x-table.cell>
                <x-table.cell>{{ $item->fasilitas->nama_fasilitas ?? '-' }}</x-table.cell>
                <x-table.cell>{{ $item->fasilitas->kategori->nama_kategori ?? '-' }}</x-table.cell>
                <x-table.cell>
                    {{ $item->fasilitas->ruangan->lantai->gedung->nama_gedung ?? '-' }},
                    {{ $item->fasilitas->ruangan->lantai->nama_lantai ?? '-' }},
                    {{ $item->fasilitas->ruangan->nama_ruangan ?? '-' }}
                </x-table.cell>
                <x-table.cell>
                    <span class="px-2 py-1 rounded text-white               
                        @if ($item->fasilitas->urgensi === \App\Http\Enums\URGENSI :: DARURAT) bg-red-500
                        @elseif ($item->fasilitas->urgensi === \App\Http\Enums\URGENSI :: PENTING) bg-yellow-500
                        @elseif ($item->fasilitas->urgensi === \App\Http\Enums\URGENSI :: BIASA ) bg-blue-500
                        @endif">
                        {{ $item->fasilitas->urgensi }}
                    </span>
                </x-table.cell>
                <x-table.cell>
                    <span class="px-2 py-1 rounded text-white
                        @if ($item->tingkat_kerusakan === \App\Http\Enums\TingkatKerusakan :: PARAH) bg-red-500
                        @elseif ($item->tingkat_kerusakan === \App\Http\Enums\TingkatKerusakan :: SEDANG) bg-yellow-500
                        @elseif ($item->tingkat_kerusakan === \App\Http\Enums\TingkatKerusakan :: RINGAN) bg-blue-500
                        @endif">
                        {{ $item->tingkat_kerusakan }}
                    </span>
                </x-table.cell>
                <x-table.cell>
                    <button onclick="modalAction('{{ route('teknisi.penugasan.show_ajax', $item->id_inspeksi) }}')"
                        class="cursor-pointer text-blue-600 hover:underline text-sm">
                        <img src="{{ asset('icons/solid/Detail.svg') }}" alt="Detail"
                            class="h-7 w-7 min-h-[29px] min-w-[29px]">
                    </button>
                    <!-- Tombol Edit -->
                    <button onclick="modalAction('{{ route('teknisi.penugasan.edit_ajax', $item->id_inspeksi) }}')"
                        class="cursor-pointer text-blue-600 hover:underline text-sm">
                        <img src="{{ asset('icons/solid/Edit.svg') }}" alt="Edit"
                            class="h-7 w-7 min-h-[29px] min-w-[29px]">
                    </button>
                </x-table.cell>
            </x-table.row>
        @empty
            <x-table.row>
                <x-table.cell colspan="7" class="text-center">Tidak ada data penugasan.</x-table.cell>
            </x-table.row>
        @endforelse
    </x-slot>
</x-table>