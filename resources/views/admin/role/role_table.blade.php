<x-table>
    <x-slot name="head">
        <x-table.heading>No</x-table.heading>
        <x-table.heading>Kode Role</x-table.heading>
        <x-table.heading>Nama Role</x-table.heading>
        <x-table.heading>Aksi</x-table.heading>
    </x-slot>

    <x-slot name="body">
        @forelse ($roles as $index => $role)
            <x-table.row>
                <x-table.cell>{{ $roles->firstItem() + $index }}</x-table.cell>
                <x-table.cell>{{ $role->kode_role }}</x-table.cell>
                <x-table.cell>{{ $role->nama_role }}</x-table.cell>
                <x-table.cell>
                    <button onclick="modalAction('{{ route('admin.role.show_ajax', $role->id_role) }}')" class="text-blue-600 hover:underline text-sm">
                        <img src="{{ asset('icons/solid/Detail.svg') }}" alt="" class="h-7 w-7 inline">
                    </button>
                    <button onclick="modalAction('{{ route('admin.role.edit_ajax', $role->id_role) }}')" class="text-blue-600 hover:underline text-sm ml-2">
                        <img src="{{ asset('icons/solid/Edit.svg') }}" alt="" class="h-7 w-7 inline">
                    </button>
                    <button onclick="removeRole('{{ $role->id_role }}')" class="text-red-600 hover:underline text-sm ml-2">
                        <img src="{{ asset('icons/solid/Delete.svg') }}" alt="" class="h-7 w-7 inline">
                    </button>
                </x-table.cell>
            </x-table.row>
        @empty
            <x-table.row>
                <x-table.cell colspan="4" class="text-center text-gray-500">Tidak ada data role.</x-table.cell>
            </x-table.row>
        @endforelse
    </x-slot>
</x-table>

<div class="mt-4">
    {{ $roles->links() }}
</div>