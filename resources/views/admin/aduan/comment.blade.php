<!-- Modal Konten Detail -->
<div class="bg-white rounded-lg shadow-lg max-w-2xl w-full p-6 relative max-h-[80vh] overflow-y-auto">

    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl">
        <i class="fas fa-times"></i>
    </button>

    <h2 class="text-xl font-semibold text-center">Detail Masukkan Pelapor</h2>
    <div class="w-24 h-1 bg-yellow-400 mx-auto mt-1 mb-6 rounded"></div>

    <div class="p-6">
        {{-- Tabs Navigations --}}
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8">
                <button id="tab-aduan"
                    class="tab-button active py-2 px-1 border-b-2 border-orange-500 font-medium text-sm text-blue-600 hover:border-gray-300">
                    Aduan
                </button>
                <button id="tab-ulasan"
                    class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:border-gray-300">
                    Ulasan
                </button>
            </nav>
        </div>

        {{-- Tab Content --}}
        <div id="content-aduan" class="tab-content">
            <div class="mb-6">
                @if($pelaporFasilitas->isNotEmpty())
                    @foreach ($pelaporFasilitas as $pelapor)
                        <div class="mb-2">
                            <span class="font-semibold">{{ $pelapor->nama }}</span>
                            <span class="text-gray-500 text-xs">({{ $pelapor->email }})</span>
                            {{-- id_fasilitas, status yang sama , periode saat ini --}}
                        </div>
                    @endforeach
                @else
                    <div class="text-center text-gray-400">Tidak ada data pelapor.</div>
                @endif
            </div>
        </div>

        <div id="content-ulasan" class="tab-content hidden">
            <div class="mb-6">
                @if($aduan->perbaikan->umpan_balik)
                    @foreach ($aduan->perbaikan->umpan_balik as $item => $umpanBalik)


                    @endforeach
                @else
                    <tr>
                        <td colspan="3" class="text-center text-gray-400">Tidak ada Umpann balik.</td>
                    </tr>
                @endif
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const tabId = this.id.replace('tab-', '');

                    // Reset all tabs
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active', 'border-orange-500', 'text-blue-600');
                        btn.classList.add('border-transparent', 'text-gray-500');
                    });

                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });

                    // Activate clicked tab
                    this.classList.add('active', 'border-orange-500', 'text-blue-600');
                    this.classList.remove('border-transparent', 'text-gray-500');

                    document.getElementById(`content-${tabId}`).classList.remove('hidden');
                });
            });
        });
    </script>
@endpush