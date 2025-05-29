<x-table>
    <x-slot name="head">
        <x-table.heading>No</x-table.heading>
        <x-table.heading>Kode Role</x-table.heading>
        <x-table.heading>Nama Role</x-table.heading>
        <x-table.heading>Aksi</x-table.heading>
    </x-slot>

    <x-slot name="body">
        @forelse ($jurusan as $index => $j)
            <x-table.row>
                <x-table.cell>{{ $jurusan->firstItem() + $index }}</x-table.cell>
                <x-table.cell>{{ $j->kode_jurusan }}</x-table.cell>
                <x-table.cell>{{ $j->nama_jurusan }}</x-table.cell>
                <x-table.cell>
                    <button onclick="modalAction('{{ route('admin.jurusan.show_ajax', $j->id_jurusan) }}')" class="text-blue-600 hover:underline text-sm">
                        <img src="{{ asset('icons/solid/Detail.svg') }}" alt="" class="h-7 w-7 inline">
                    </button>
                    <button onclick="modalAction('{{ route('admin.jurusan.edit_ajax', $j->id_jurusan) }}')" class="text-blue-600 hover:underline text-sm ml-2">
                        <img src="{{ asset('icons/solid/Edit.svg') }}" alt="" class="h-7 w-7 inline">
                    </button>
                    <button onclick="removeJurusan('{{ $j->id_jurusan }}')" class="text-red-600 hover:underline text-sm ml-2">
                        <img src="{{ asset('icons/solid/Delete.svg') }}" alt="" class="h-7 w-7 inline">
                    </button>
                </x-table.cell>
            </x-table.row>
        @empty
            <x-table.row>
                <x-table.cell colspan="4" class="text-center text-gray-500">Tidak ada data jurusan.</x-table.cell>
            </x-table.row>
        @endforelse
    </x-slot>
</x-table>

<div class="mt-4">
    {{ $jurusan->links() }}
</div>