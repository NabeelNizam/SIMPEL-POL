<x-table>
    <x-slot name="head">
        <x-table.heading>No</x-table.heading>
        <x-table.heading>Nama Fasilitas</x-table.heading>
        <x-table.heading>Kategori</x-table.heading>
        <x-table.heading>Lokasi</x-table.heading>
        <x-table.heading>Tanggal Lapor</x-table.heading>
        <x-table.heading>Tanggal Perbaikan</x-table.heading>
        <x-table.heading>Status Perbaikan</x-table.heading>
        <x-table.heading>Umpan Balik</x-table.heading>
        <x-table.heading>Aksi</x-table.heading>
    </x-slot>

    <x-slot name="body">
        @forelse ($aduan as $index => $a)
            <x-table.row>
                <x-table.cell>{{ $aduan->firstItem() + $index }}</x-table.cell>
                <x-table.cell>{{ $a->fasilitas->nama_fasilitas }}</x-table.cell>
                <x-table.cell>{{ $a->fasilitas->kategori->nama_kategori ?? '-' }}</x-table.cell>
                @php
                    $ruangan = $a->fasilitas->ruangan;
                    $lantai = $ruangan->lantai;
                    $gedung = $lantai->gedung;
                @endphp
                <x-table.cell>
                    {{ $gedung->nama_gedung ?? '-' }}
                    {{ $lantai ? ', Lt. ' . $lantai->nama_lantai : '' }}
                    {{ $ruangan ? ', ' . $ruangan->nama_ruangan : '' }}
                </x-table.cell>
                <x-table.cell>
                    {{ $a->tanggal_aduan ? \Carbon\Carbon::parse($a->tanggal_aduan)->format('d/m/Y') : '-'}}
                </x-table.cell>
                <x-table.cell>
                    {{ $a->perbaikan && $a->perbaikan->tanggal_selesai ? \Carbon\Carbon::parse($a->perbaikan->tanggal_selesai)->format('d/m/Y') : '-' }}
                </x-table.cell>
                <x-table.cell>
                    @if($a->status)
                        <span class="px-3 py-1 rounded-full text-white text-sm
                            @if($a->status === \App\Http\Enums\Status::SELESAI)
                                bg-green-500
                            @elseif($a->status === \App\Http\Enums\Status::MENUNGGU_DIPROSES)
                                bg-blue-500
                            @elseif($a->status === \App\Http\Enums\Status::SEDANG_INSPEKSI)
                                bg-yellow-500
                            @elseif($a->status === \App\Http\Enums\Status::SEDANG_DIPERBAIKI)
                                bg-orange-500
                            @endif   ">
                            {{ $a->status->value }}
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-gray-500 text-white text-sm">-</span>
                    @endif
                </x-table.cell>
                @php
                    $avgRating = \App\Models\UmpanBalik::whereHas('aduan', function ($q) use ($a) {
                        $q->where('id_fasilitas', $a->id_fasilitas)
                            ->where('tanggal_aduan', $a->tanggal_aduan);
                    })->avg('rating');
                    $avgRating = $avgRating ? number_format($avgRating, 1) : null;
                @endphp
                <x-table.cell>
                    @if($avgRating)
                        <div class="flex items-center mb-2">
                            <i class="fas fa-star text-yellow-400 text-lg"></i>
                            <span class="text-yellow-500 font-bold text-lg mr-1">{{ $avgRating }}</span>
                            <span class="text-gray-600 text-sm">/ 5.0</span>
                        </div>
                    @else
                        <span class="text-gray-500">-</span>
                    @endif
                </x-table.cell>
                <x-table.cell>
                    <div class="flex gap-2">
                        <button onclick="modalAction('{{ route('admin.aduan.show_ajax', $a->id_aduan) }}')"
                            class="text-blue-600 hover:underline text-sm">
                            <img src="{{ asset('icons/solid/Detail.svg') }}" alt="" class="h-7 w-7 inline">
                        </button>
                        <button onclick="modalAction('{{ route('admin.aduan.comment_ajax', $a->id_aduan) }}')"
                            class="text-blue-600 hover:underline text-sm">
                            <img src="{{ asset('icons/solid/message.svg') }}" alt="" class="h-7 w-7 inline">
                        </button>
                    </div>
                </x-table.cell>
            </x-table.row>
        @empty
            <x-table.row>
                <x-table.cell colspan="9" class="text-center text-gray-500">Tidak ada data Aduan.</x-table.cell>
            </x-table.row>
        @endforelse
    </x-slot>
</x-table>

<div class="mt-4">
    {{ $aduan->links() }}
</div>