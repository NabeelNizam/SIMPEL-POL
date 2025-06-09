<x-table>
    <x-slot name="head">
        <x-table.heading>No</x-table.heading>
        <x-table.heading>Nama Gedung</x-table.heading>
        <x-table.heading>Jumlah Lantai</x-table.heading>
        <x-table.heading>Jumlah Ruangan</x-table.heading>
        <x-table.heading>Aksi</x-table.heading>
    </x-slot>

    <x-slot name="body">
        @forelse ($lokasi as $index => $gedung)
            <x-table.row>
                <x-table.cell>{{ $index + 1 }}</x-table.cell>
                <x-table.cell>{{ $gedung->nama_gedung }}</x-table.cell>
                <x-table.cell>{{ $gedung->lantai->count() }}</x-table.cell>
                <x-table.cell>{{ $gedung->lantai->flatMap->ruangan->count() }}</x-table.cell>
                <x-table.cell>
                    <button onclick="modalAction('{{ route('admin.lokasi.show', $gedung->id_gedung) }}')" class="text-blue-600 hover:underline text-sm">
                        <img src="{{asset('icons/solid/Detail.svg')}}" alt="" class="h-7 w-7 inline"></button>
                    <button onclick="modalAction('{{ route('admin.lokasi.edit', $gedung->id_gedung) }}')" class="text-blue-600 hover:underline text-sm ml-2">
                        <img src="{{asset('icons/solid/Edit.svg')}}" alt="" class="h-7 w-7 inline"></button>
                    <button onclick="modalAction('{{ route('admin.lokasi.confirm', $gedung->id_gedung) }}')" class="text-red-600 hover:underline text-sm ml-2">
                        <img src="{{asset('icons/solid/Delete.svg')}}" alt="" class="h-7 w-7 inline"></button>
                </x-table.cell>
            </x-table.row>
        @empty
            <x-table.row>
                <x-table.cell colspan="5" class="text-center">Tidak ada data lokasi.</x-table.cell>
            </x-table.row>
        @endforelse
    </x-slot>
</x-table>