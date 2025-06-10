<x-table>
    <x-slot name="head">
        <x-table.heading>No</x-table.heading>
        <x-table.heading>Nama Fasilitas</x-table.heading>
        <x-table.heading>Leaving Flow</x-table.heading>
        <x-table.heading>Entering Flow</x-table.heading>
        <x-table.heading>Net Flow</x-table.heading>
        <x-table.heading>Ranking</x-table.heading>
    </x-slot>

    <x-slot name="body">
        @forelse ($result as $index => $item)
            <x-table.row>
                <x-table.cell>{{ $index + 1 }}</x-table.cell>
                <x-table.cell>{{ $item['id'] ?? '-' }}</x-table.cell>
                <x-table.cell>{{ $item['leaving_flow'] ?? '-' }}</x-table.cell>
                <x-table.cell>{{ $item['entering_flow'] ?? '-' }}</x-table.cell>
                <x-table.cell>{{ $item['net_flow'] ?? '-' }}</x-table.cell>
                <x-table.cell>{{ $item['ranking'] ?? '-' }}</x-table.cell>
            </x-table.row>
        @empty
            <x-table.row>
                <x-table.cell colspan="8" class="text-center">Tidak ada data penugasan</x-table.cell>
            </x-table.row>
        @endforelse
    </x-slot>
</x-table>