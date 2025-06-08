<x-table>
    <x-slot name="head">
        <x-table.heading>No</x-table.heading>
        <x-table.heading>Nama Fasilitas</x-table.heading>
        <x-table.heading>Kategori</x-table.heading>
        <x-table.heading>Lokasi</x-table.heading>
        <x-table.heading>Urgensi</x-table.heading>
        <x-table.heading>Jumlah Pelapor</x-table.heading>
        <x-table.heading>Tingkat Kerusakan</x-table.heading>
        <x-table.heading>Aksi</x-table.heading>
    </x-slot>

    <x-slot name="body">
        @forelse ($penugasan as $index => $item)
            <x-table.row>
                <x-table.cell>{{ $index + 1 }}</x-table.cell>
                <x-table.cell>{{ $item->fasilitas->nama_fasilitas ?? '-' }}</x-table.cell>
                <x-table.cell>{{ $item->fasilitas->kategori->nama_kategori ?? '-' }}</x-table.cell>
                <x-table.cell>{{ $item->fasilitas->getLokasiString() }} </x-table.cell>
                <x-table.cell>
                    <div class="flex flex-col items-center">
                    @if($item->fasilitas->urgensi)
                        <span class="w-[90px] text-center py-1 px-2 rounded-full text-white text-sm
                                    @if($item->fasilitas->urgensi === \App\Http\Enums\Urgensi::DARURAT)
                                        px-3 bg-red-600
                                    @elseif($item->fasilitas->urgensi === \App\Http\Enums\Urgensi::PENTING)
                                        px-3 bg-yellow-500
                                    @elseif($item->fasilitas->urgensi === \App\Http\Enums\Urgensi::BIASA)
                                        px-6 bg-blue-600
                                    @else
                                        px-3 bg-gray-600
                                    @endif
                                ">
                            {{ $item->fasilitas->urgensi->value ?? '-'}}
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-gray-500 text-white text-sm">-</span>
                    @endif
                    </div>
                </x-table.cell>
                <x-table.cell>
                    <div class="text-center">
                        {{ $item->user_count }}
                    </div>
                </x-table.cell>
                <x-table.cell>
                    <div class="flex flex-col items-center">
                    @if($item->tingkat_kerusakan)
                        <span class="w-[90px] text-center py-1 px-2 rounded-full text-white text-sm
                                    @if($item->tingkat_kerusakan === \App\Http\Enums\TingkatKerusakan::PARAH)
                                        px-3 bg-red-600
                                    @elseif($item->tingkat_kerusakan === \App\Http\Enums\TingkatKerusakan::SEDANG)
                                        px-3 bg-yellow-500
                                    @elseif($item->tingkat_kerusakan === \App\Http\Enums\TingkatKerusakan::RINGAN)
                                        px-3 bg-blue-600
                                    @else
                                        px-3 bg-gray-600
                                    @endif
                                ">
                            {{ $item->tingkat_kerusakan->value ?? '-'}}
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-gray-500 text-white text-sm">-</span>
                    @endif
                    </div>
                </x-table.cell>
                <x-table.cell>
                    <div class="flex items-center space-x-2 min-w-[90px]">
                        <button onclick="modalAction('{{ route('sarpras.penugasan.show', $item->id_inspeksi) }}')"
                            class="text-blue-600 hover:underline text-sm cursor-pointer">
                            <img src="{{ asset('icons/solid/Detail.svg') }}" alt="" class="h-7 w-7 min-h-[25px] min-w-[25px]">
                        </button>
                        <button onclick="modalAction('{{ route('sarpras.penugasan.edit', $item->id_inspeksi) }}')" class="text-yellow-600 hover:underline text-sm cursor-pointer">
                            <img src="{{ asset('icons/solid/Acc.svg') }}" alt="" class="h-7 w-7 min-h-[25px] min-w-[25px]">
                        </button>
                    </div>
                </x-table.cell>
            </x-table.row>
        @empty
            <x-table.row>
                <x-table.cell colspan="8" class="text-center">Tidak ada data penugasan</x-table.cell>
            </x-table.row>
        @endforelse
    </x-slot>
</x-table>