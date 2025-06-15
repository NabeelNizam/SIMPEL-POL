<!-- Modal Konten Detail -->
<div class="bg-white rounded-lg shadow-lg max-w-2xl w-full p-6 relative max-h-[80vh] overflow-y-auto">

    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl cursor-pointer">
        <i class="fas fa-times"></i>
    </button>

    <h2 class="text-xl font-semibold text-center">Detail Masukkan Pelapor</h2>
    <div class="w-24 h-1 bg-yellow-400 mx-auto mt-1 mb-6 rounded"></div>

    <div class="p-6">
        {{-- Tabs Navigations --}}
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8">
                <button id="tab-aduan"
                    class="tab-button active py-2 px-1 border-b-2 border-orange-500 font-medium text-sm text-blue-600 hover:border-gray-300 cursor-pointer">
                    Aduan ({{ $perbaikan->jumlah_aduan_tertangani }})
                </button>
                <button id="tab-ulasan"
                    class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:border-gray-300 cursor-pointer">
                    Ulasan ({{ $perbaikan->jumlah_umpan_balik_tertangani }})
                </button>
            </nav>
        </div>

        {{-- Tab Content Aduan --}}
        <div id="content-aduan" class="tab-content">
            <div class="mb-6">
                @if($perbaikan->jumlah_aduan_tertangani)
                    <div>
                        @foreach($aduan as $item)
                            <div class="mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="font-semibold text-gray-800">{{ $item->pelapor->nama ?? '-' }}</div>
                                    <span class="text-xs text-gray-500 bg-white px-2 py-1 rounded">
                                        {{ \Carbon\Carbon::parse($item->tanggal_aduan)->format('d/m/Y') }}
                                    </span>
                                </div>

                                <div class="text-gray-700 mb-2">{{ $item->deskripsi ?? '-' }}</div>

                                @if($item->bukti_foto)
                                    <div class="mb-2">
                                        <img src="{{ asset($item->bukti_foto) }}" alt="Bukti Foto"
                                            class="w-32 h-24 object-cover rounded shadow cursor-pointer hover:shadow-lg transition-shadow"
                                            onclick="showImageModal('{{ asset($item->bukti_foto) }}')">
                                    </div>

                                @endif

                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <span>{{ $item->fasilitas->nama_fasilitas ?? '-' }}</span>
                                    @if($item->pelapor->email)
                                        <span>{{ $item->pelapor->email }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-400 py-8">
                        <i class="fas fa-inbox text-3xl mb-2"></i>
                        <p>Tidak ada aduan pada fasilitas ini.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Tab Content Ulasan --}}
        <div id="content-ulasan" class="tab-content hidden">
            <div class="mb-6">
                @if($perbaikan->jumlah_umpan_balik_tertangani)
                    <div class="space-y-4">
                        @foreach($perbaikan->umpan_balik_tertangani as $umpanBalik)
                            <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="font-semibold text-gray-800">{{ $umpanBalik->aduan->pelapor->nama ?? '-' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($umpanBalik->created_at)->format('d/m/Y') }}
                                    </div>
                                </div>

                                <div class="flex items-center mb-2">
                                    <span class="text-sm text-gray-600 mr-2">Rating:</span>
                                    <div class="flex items-center">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i
                                                class="fas fa-star {{ $i <= ($umpanBalik->rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                                        @endfor
                                        <span class="ml-2 text-sm text-gray-600">{{ $umpanBalik->rating ?? 0 }} / 5</span>
                                    </div>
                                </div>

                                @if($umpanBalik->komentar)
                                    <div class="text-gray-700">
                                        <span class="text-sm text-gray-600">Komentar:</span>
                                        <p class="mt-1">{{ $umpanBalik->komentar }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-400 py-8">
                        <i class="fas fa-comment-slash text-3xl mb-2"></i>
                        <p>Belum ada umpan balik pada fasilitas ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        (function () {
            console.log('Initializing tabs...');

            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            console.log('Found buttons:', tabButtons.length);
            console.log('Found contents:', tabContents.length);

            tabButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    console.log('Tab clicked:', this.id);

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

                    const targetContent = document.getElementById(`content-${tabId}`);
                    if (targetContent) {
                        targetContent.classList.remove('hidden');
                        console.log('Showing content for:', tabId);
                    } else {
                        console.error('Content not found:', `content-${tabId}`);
                    }
                });
            });
        })();

        // Image modal functions
        function showImageModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModal').classList.remove('hidden');
            document.getElementById('imageModal').classList.add('flex');
        }

        function hideImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.getElementById('imageModal').classList.remove('flex');
        }

        // Close modal when clicking outside
        document.getElementById('imageModal').addEventListener('click', function (e) {
            if (e.target === this) {
                hideImageModal();
            }
        });

        // Modal close functionality
        $(document).on('click', '#modal-close', function () {
            $('#myModal').addClass('hidden').removeClass('flex').html('');
        });
    </script>
</div>
