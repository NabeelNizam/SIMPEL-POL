<x-table>
    <x-slot name="head">
        <x-table.heading>No</x-table.heading>
        <x-table.heading>Kode Kriteria</x-table.heading>
        <x-table.heading>Nama Kriteria</x-table.heading>
        <x-table.heading>Jenis Kriteria</x-table.heading>
        <x-table.heading>Bobot</x-table.heading>
    </x-slot>

    <x-slot name="body">
        @forelse ($kriteria as $index => $k)
            <x-table.row>
                <x-table.cell>{{ $k->id_kriteria }}</x-table.cell>
                <x-table.cell>{{ $k->kode_kriteria }}</x-table.cell>
                <x-table.cell>{{ $k->nama_kriteria }}</x-table.cell>
                <x-table.cell>{{ Str::ucfirst(Str::lower($k->jenis_kriteria->value)) }}</x-table.cell>
                <x-table.cell>{{ $k->bobot ?? '-' }}</x-table.cell>
            </x-table.row>
        @empty
            <tr class="border-1">
                <td colspan="6" class="text-center text-gray-500 py-4">Tidak ada data kriteria.</td>
            </tr>
        @endforelse
    </x-slot>
</x-table>
