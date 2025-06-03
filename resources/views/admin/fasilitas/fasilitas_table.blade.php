<x-table>
    <x-slot name="head">
        <x-table.heading>No</x-table.heading>
        <x-table.heading>Kode</x-table.heading>
        <x-table.heading>Nama Fasilitas</x-table.heading>
        <x-table.heading>Kategori</x-table.heading>
        <x-table.heading>Lokasi</x-table.heading>
        <x-table.heading>Kondisi</x-table.heading>
        <x-table.heading>Aksi</x-table.heading>
    </x-slot>

    <x-slot name="body">
        @forelse ($fasilitas as $index => $f)
            <x-table.row>
                <x-table.cell>{{ $fasilitas->firstItem() + $index }}</x-table.cell>
                <x-table.cell>{{ $f->kode_fasilitas }}</x-table.cell>
                <x-table.cell>{{ $f->nama_fasilitas }}</x-table.cell>
                <x-table.cell>{{ $f->kategori->nama_kategori ?? '-' }}</x-table.cell>
                <x-table.cell>
                    {{ $f->getLokasiString() }}
                </x-table.cell>
                <x-table.cell>
                    <span
                        class="block w-full text-center px-2 py-1 rounded text-white {{ $f->kondisi->value === 'LAYAK' ? 'bg-green-500' : 'bg-red-500' }}">
                        {{ Str::ucfirst(Str::lower($f->kondisi->value)) }}
                    </span>
                </x-table.cell>
                <x-table.cell>
                    <div class="flex items-center space-x-2 min-w-[120px]">
                        <button onclick="modalAction('{{ route('admin.fasilitas.show', $f->id_fasilitas) }}')"
                            class="cursor-pointer text-blue-600 hover:underline text-sm">
                            <img src="{{ asset('icons/solid/Detail.svg') }}" alt="Detail"
                                class="h-7 w-7 min-h-[29px] min-w-[29px]">
                        </button>
                        <button onclick="modalAction('{{ route('admin.fasilitas.edit', $f->id_fasilitas) }}')"
                            class="cursor-pointer text-blue-600 hover:underline text-sm">
                            <img src="{{ asset('icons/solid/Edit.svg') }}" alt="Edit"
                                class="h-7 w-7 min-h-[29px] min-w-[29px]">
                        </button>
                        <button onclick="modalAction('{{ route('admin.fasilitas.confirm', $f->id_fasilitas) }}')"
                            class="cursor-pointer text-red-600 hover:underline text-sm">
                            <img src="{{ asset('icons/solid/Delete.svg') }}" alt="Delete"
                                class="h-7 w-7 min-h-[29px] min-w-[29px]">
                        </button>
                    </div>
                </x-table.cell>
            </x-table.row>
        @empty
            <x-table.row>
                <x-table.cell colspan="6" class="text-center text-gray-500">Tidak ada data fasilitas.</x-table.cell>
            </x-table.row>
        @endforelse
    </x-slot>
</x-table>

<div class="mt-4">
    {{ $fasilitas->links() }}
</div>
