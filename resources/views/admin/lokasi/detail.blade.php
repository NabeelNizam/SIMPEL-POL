<div class="bg-white rounded-lg shadow-lg w-[360px] p-5 relative">
    <!-- Header -->
    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl">
        <i class="fas fa-times"></i>
    </button>
    <h2 class="text-xl font-semibold text-center">Detail Lokasi</h2>
    <div class="w-24 h-1 bg-orange-400 mx-auto mt-2 rounded-full"></div>
    

    <!-- Info -->
    <div class="grid grid-cols-2 gap-y-3 mt-4 text-sm text-gray-700">
        <div>
            <div class="text-gray-500">Kode Lokasi</div>
            <div class="font-medium">{{ $gedung->kode_gedung }}</div>
        </div>
        <div>
            <div class="text-gray-500">Jumlah Lantai</div>
            <div class="font-medium">{{ $gedung->lantai->count() }}</div>
        </div>
        <div>
            <div class="text-gray-500">Nama Gedung</div>
            <div class="font-medium">{{ $gedung->nama_gedung }}</div>
        </div>
        <div>
            <div class="text-gray-500">Jumlah Ruangan</div>
            <div class="font-medium">{{ $gedung->lantai->flatMap->ruangan->count() }}</div>
        </div>
    </div>

    <!-- Detail Lantai dan Ruangan -->
    <div class="mt-6">
        <h3 class="font-semibold text-gray-800 text-center mb-4">Detail Lantai dan Ruangan</h3>
        <div id="accordion">
            @foreach ($gedung->lantai as $lantai)
                <div class="border rounded-lg mb-2">
                    <button class="w-full px-4 py-2 text-left flex justify-between items-center" onclick="toggleAccordion(this)">
                        <span class="text-blue-600 font-medium">Lantai {{ $lantai->nama_lantai }}</span>
                        <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="px-4 pb-4 hidden">
                        <ul class="text-sm list-disc list-inside text-gray-600">
                            @foreach ($lantai->ruangan as $ruangan)
                                <li>{{ $ruangan->nama_ruangan }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>