<!-- Tabel Data Setelah Perbaikan -->
<div id="promethee-table-body" class="hidden">
    <x-table>
        <x-slot name="head">
            <x-table.heading>No</x-table.heading>
            <x-table.heading>Nama Fasilitas</x-table.heading>
            <x-table.heading>Biaya</x-table.heading>
            <x-table.heading>Tingkat Kerusakan</x-table.heading>
            <x-table.heading>Waktu</x-table.heading>
            <x-table.heading>Score</x-table.heading>
        </x-slot>

        <x-slot name="body">
            @forelse ($prometheeResults as $index => $result)
                <x-table.row>
                    <x-table.cell>{{ $index + 1 }}</x-table.cell>
                    <x-table.cell>{{ $result['name'] }}</x-table.cell>
                    <x-table.cell>{{ $result['criteria']['biaya'] }}</x-table.cell>
                    <x-table.cell>{{ $result['criteria']['tingkat_kerusakan'] }}</x-table.cell>
                    <x-table.cell>{{ date('Y-m-d', $result['criteria']['waktu']) }}</x-table.cell>
                    <x-table.cell>{{ $result['score'] }}</x-table.cell>
                </x-table.row>
            @empty
                <x-table.row>
                    <x-table.cell colspan="6" class="text-center">Tidak ada hasil perhitungan PROMETHEE</x-table.cell>
                </x-table.row>
            @endforelse
        </x-slot>
    </x-table>
</div>