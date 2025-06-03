<x-table>
    <x-slot name="head">
        <x-table.heading>No</x-table.heading>
        <x-table.heading>Kode Periode</x-table.heading>
        <x-table.heading>Tanggal Mulai</x-table.heading>
        <x-table.heading>Tanggal Selesai</x-table.heading>
        <x-table.heading>Aksi</x-table.heading>
    </x-slot>

    <x-slot name="body">
        @forelse ($periode as $index => $p)
            <x-table.row>
                <x-table.cell>{{ $periode->firstItem() + $index }}</x-table.cell>
                <x-table.cell>{{ $p->kode_periode }}</x-table.cell>
                <x-table.cell>{{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d-m-Y') }}</x-table.cell>
                <x-table.cell>{{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d-m-Y') }}</x-table.cell>
                <x-table.cell>
                    <button onclick="modalAction('{{ route('admin.periode.show_ajax', $p->id_periode) }}')"
                        class="text-blue-600 hover:underline text-sm">
                        <img src="{{asset('icons/solid/Detail.svg')}}" alt="" class="h-7 w-7 inline"></button>
                    <button onclick="modalAction('{{ route('admin.periode.edit_ajax', $p->id_periode) }}')"
                        class="text-blue-600 hover:underline text-sm ml-2">
                        <img src="{{asset('icons/solid/Edit.svg')}}" alt="" class="h-7 w-7 inline"></button>
                    <button onclick="modalAction('{{ route('admin.periode.confirm_ajax', $p->id_periode) }}')"
                        class="text-red-600 hover:underline text-sm ml-2">
                        <img src="{{asset('icons/solid/Delete.svg')}}" alt="" class="h-7 w-7 inline"></button>
                </x-table.cell>
            </x-table.row>
        @empty
            <x-table.row>
                <x-table.cell colspan="5" class="text-center text-gray-500">Tidak ada data.</x-table.cell>
            </x-table.row>
        @endforelse
    </x-slot>
</x-table>