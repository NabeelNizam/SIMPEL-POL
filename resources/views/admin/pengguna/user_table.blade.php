<x-table>
    <x-slot name="head">
        <x-table.heading>No</x-table.heading>
        <x-table.heading>Nama</x-table.heading>
        <x-table.heading>Email</x-table.heading>
        <x-table.heading>Role</x-table.heading>
        <x-table.heading>Aksi</x-table.heading>
    </x-slot>

    <x-slot name="body">
        @forelse ($users as $index => $user)
            <x-table.row>
                <x-table.cell>{{ $users->firstItem() + $index }}</x-table.cell>
                <x-table.cell>{{ $user->nama }}</x-table.cell>
                <x-table.cell>{{ $user->email }}</x-table.cell>
                <x-table.cell>{{ $user->role->nama_role ?? '-' }}</x-table.cell>
                <x-table.cell>
                    <button onclick="modalAction('{{ route('admin.pengguna.show_ajax', $user->id_user) }}')" class="text-blue-600 hover:underline text-sm">
                        <img src="{{asset('icons/solid/Detail.svg')}}" alt="" class="h-7 w-7 inline"></button>
                    <button onclick="modalAction('{{ route('admin.pengguna.edit_ajax', $user->id_user) }}')" class="text-blue-600 hover:underline text-sm ml-2">
                        <img src="{{asset('icons/solid/Edit.svg')}}" alt="" class="h-7 w-7 inline"></button>
                    <button onclick="modalAction('{{ route('admin.pengguna.confirm_ajax', $user->id_user) }}')" class="text-red-600 hover:underline text-sm ml-2">
                        <img src="{{asset('icons/solid/Delete.svg')}}" alt="" class="h-7 w-7 inline"></button>
                </x-table.cell>
            </x-table.row>
        @empty
            <tr class="border-1">
                <td colspan="5" class="text-center text-gray-500 py-4">Tidak ada data.</td>
            </tr>
        @endforelse
    </x-slot>
</x-table>

<div class="mt-4">
    {{ $users->links() }}
</div>
