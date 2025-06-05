<x-table>
    <x-slot name="head">
        <x-table.heading>No</x-table.heading>
        <x-table.heading>Kode Jurusan</x-table.heading>
        <x-table.heading>Nama Jurusan</x-table.heading>
        <x-table.heading>Aksi</x-table.heading>
    </x-slot>

    <x-slot name="body">
        @forelse ($jurusan as $index => $j)
            <x-table.row>
                <x-table.cell>{{ $jurusan->firstItem() + $index }}</x-table.cell>
                <x-table.cell>{{ $j->kode_jurusan }}</x-table.cell>
                <x-table.cell>{{ $j->nama_jurusan }}</x-table.cell>
                <x-table.cell>
                    <div class="flex items-center space-x-2 min-w-[120px]">
                        <button onclick="modalAction('{{ route('admin.jurusan.show', $j->id_jurusan) }}')"
                            class="cursor-pointer text-blue-600 hover:underline text-sm">
                            <img src="{{ asset('icons/solid/Detail.svg') }}" alt="Detail"
                                class="h-7 w-7 min-h-[29px] min-w-[29px]">
                        </button>
                        <button onclick="modalAction('{{ route('admin.jurusan.edit', $j->id_jurusan) }}')"
                            class="cursor-pointer text-blue-600 hover:underline text-sm">
                            <img src="{{ asset('icons/solid/Edit.svg') }}" alt="Edit"
                                class="h-7 w-7 min-h-[29px] min-w-[29px]">
                        </button>
                        <button onclick="modalAction('{{ route('admin.jurusan.confirm', $j->id_jurusan) }}')"
                            class="cursor-pointer text-red-600 hover:underline text-sm">
                            <img src="{{ asset('icons/solid/Delete.svg') }}" alt="Delete"
                                class="h-7 w-7 min-h-[29px] min-w-[29px]">
                        </button>
                    </div>
                </x-table.cell>
            </x-table.row>
        @empty
            <tr class="border-1">
                <td colspan="6" class="text-center text-gray-500 py-4">Tidak ada data jurusan.</td>
            </tr>
        @endforelse
    </x-slot>
</x-table>

<div class="mt-4">
    {{ $jurusan->links() }}
</div>
