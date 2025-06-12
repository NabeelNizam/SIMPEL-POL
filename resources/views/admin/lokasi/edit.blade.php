<div class="bg-white rounded-lg shadow-lg w-[400px] p-5 relative max-h-screen overflow-y-auto">
    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl">
        <i class="fas fa-times"></i>
    </button>
    <h2 class="text-xl font-semibold text-center">Edit Data Lokasi</h2>
    <div class="w-24 h-1 bg-orange-400 mx-auto mt-2 rounded-full"></div>

    <!-- Form -->
    <form action="{{ route('admin.lokasi.update', $gedung->id_gedung) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-4 mt-4 text-sm text-gray-700">
            <div>
                <label class="font-medium">Nama Gedung <span class="text-red-500">*</span></label>
                <input type="text" name="nama_gedung" value="{{ $gedung->nama_gedung }}"
                    placeholder="Contoh: Gedung Sipil"
                    class="w-full mt-1 px-3 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-400">
            </div>

            <div>
                <label class="font-medium">Lantai dan Ruangan <span class="text-red-500">*</span></label>
                <input id="inputLantai" type="text" placeholder="Input nomor lantai manual"
                    class="w-full mt-1 px-3 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-400">
                <button onclick="tambahLantai()" type="button"
                    class="mt-2 w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-plus"></i> Tambah Lantai
                </button>
            </div>
        </div>

        <div id="lantaiContainer" class="mt-5 space-y-3">
            @foreach ($gedung->lantai as $lantai)
                <div class="bg-blue-500">
                    <div class="flex justify-between items-center px-4 py-2 cursor-pointer bg-blue-200"
                        onclick="toggleLantai('lantai-{{ $lantai->id_lantai }}')">
                        <div class="font-medium">{{ $lantai->nama_lantai }}</div>
                        <input hidden="hidden" name="lantai[{{ $lantai->id_lantai }}][nama_lantai]"
                            value="{{ $lantai->nama_lantai }}">
                        <div class="flex items-center gap-2">
                            <svg id="icon-lantai-{{ $lantai->id_lantai }}"
                                class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                            <!-- Tombol hapus -->
                            <button onclick="hapusLantai(event, 'lantai-{{ $lantai->id_lantai }}')"
                                class="text-red-500 hover:text-red-700" title="Hapus Lantai">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="px-4 py-3 space-y-3 hidden" id="lantai-{{ $lantai->id_lantai }}"
                        style="background-color: #D9D9D9;">
                        @foreach ($lantai->ruangan as $ruangan)
                            <div class="border-l-4 border-orange-400 pl-3 flex justify-between items-center">
                                <div class="w-full">
                                    <label class="text-sm font-medium">Nama Ruangan <span class="text-red-500">*</span></label>
                                    <input type="text" name="lantai[{{ $lantai->id_lantai }}][ruangan][{{ $ruangan->id_ruangan }}][nama_ruangan]"
                                        value="{{ $ruangan->nama_ruangan }}" placeholder="Contoh: Ruang Teori 01"
                                        class="w-full mt-1 px-3 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-400">
                                </div>
                                <button type="button" onclick="hapusRuangan(this)" class="text-red-500 hover:text-red-700 ml-3">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                            <script>
                                if ({{ $ruangan->id_ruangan }} > lastRuanganId) {
                                    lastRuanganId = {{ $ruangan->id_ruangan }};
                                }
                            </script>
                        @endforeach
                        <button type="button" onclick="tambahRuangan(this, {{ $lantai->id_lantai }})"
                            class="text-blue-600 text-sm hover:underline">
                            <i class="fa-solid fa-square-plus"></i> Tambah Ruangan
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Tombol Simpan -->
        <div class="mt-6">
            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
<script>
    let lantaiCounter = 0;

    function tambahLantai() {
        const lantaiInput = document.getElementById('inputLantai');
        const lantaiNama = lantaiInput.value.trim();
        if (!lantaiNama) return alert('Nama lantai tidak boleh kosong.');

        lantaiCounter++;
        const lantaiId = lantaiCounter; // Gunakan lantaiCounter sebagai ID lantai
        const container = document.getElementById('lantaiContainer');

        const lantaiElement = document.createElement('div');
        lantaiElement.className = "bg-blue-500";
        lantaiElement.innerHTML = `
            <div class="flex justify-between items-center px-4 py-2 cursor-pointer bg-blue-200">
                <div class="font-medium">${lantaiNama}</div>
                <input type="hidden" name="lantai[${lantaiId}][nama_lantai]" value="${lantaiNama}">
                <div class="flex items-center gap-2">
                    <button onclick="hapusLantai(event, 'lantai-${lantaiId}')" class="text-red-500 hover:text-red-700" title="Hapus Lantai">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="px-4 py-3 space-y-3" id="lantai-${lantaiId}" style="background-color: #D9D9D9;">
                <div class="border-l-4 border-orange-400 pl-3 flex justify-between items-center">
                    <div class="w-full">
                        <label class="text-sm font-medium">Nama Ruangan <span class="text-red-500">*</span></label>
                        <input type="text" name="lantai[${lantaiId}][ruangan][1][nama_ruangan]" placeholder="Contoh: LPR 1"
                            class="w-full mt-1 px-3 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-400">
                    </div>
                    <button type="button" onclick="hapusRuangan(this)" class="text-red-500 hover:text-red-700 ml-3">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
                <button type="button" onclick="tambahRuangan(this, ${lantaiId})" class="text-blue-600 text-sm hover:underline">
                    <i class="fa-solid fa-square-plus"></i> Tambah Ruangan
                </button>
            </div>
        `;
        container.appendChild(lantaiElement);
        lantaiInput.value = '';
    }

    function hapusLantai(event, id) {
        event.stopPropagation();
        const container = document.getElementById(id).parentElement;
        container.remove();
    }

    function toggleLantai(lantaiId) {
        const content = document.getElementById(lantaiId);
        const icon = document.getElementById(`icon-${lantaiId}`);

        // Toggle visibility
        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            icon.classList.add('rotate-180'); // Tambahkan rotasi pada ikon
        } else {
            content.classList.add('hidden');
            icon.classList.remove('rotate-180'); // Hapus rotasi pada ikon
        }
    }

       let ruanganSekarang = null; // Variabel global untuk menyimpan ID ruangan saat ini

function tambahRuangan(button, lantaiId) {
    const parent = button.parentElement;
    const ruanganDiv = document.createElement('div');
    ruanganDiv.className = "border-l-4 border-orange-400 pl-3 flex justify-between items-center";

    // Jika ruanganSekarang belum diinisialisasi, ambil dari backend
    if (ruanganSekarang === null) {
        fetch('{{ route('ruangan.last-id') }}')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                ruanganSekarang = data.lastId || 0; // Simpan ID ruangan terakhir dari backend
                console.log('ID ruangan terakhir:', ruanganSekarang); // Debug untuk memastikan nilai awal

                // Tambahkan ruangan setelah fetch selesai
                tambahRuanganSetelahFetch(parent, lantaiId, ruanganDiv);
            })
            .catch(error => console.error('Error fetching last ruangan ID:', error));
    } else {
        // Jika ruanganSekarang sudah diinisialisasi, langsung tambahkan ruangan
        tambahRuanganSetelahFetch(parent, lantaiId, ruanganDiv);
    }
}

function tambahRuanganSetelahFetch(parent, lantaiId, ruanganDiv) {
    // Increment ID ruangan secara global
    ruanganSekarang++;

    // Buat elemen ruangan baru
    ruanganDiv.innerHTML = `
        <div class="w-full">
            <label class="text-sm font-medium">Nama Ruangan <span class="text-red-500">*</span></label>
            <input type="text" name="lantai[${lantaiId}][ruangan][${ruanganSekarang}][nama_ruangan]" placeholder="Contoh: Ruang Teori 01"
                class="w-full mt-1 px-3 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-400">
        </div>
        <button type="button" onclick="hapusRuangan(this)" class="text-red-500 hover:text-red-700 ml-3">
            <i class="fa-solid fa-trash"></i>
        </button>
    `;

    // Tambahkan elemen ruangan baru ke DOM
    parent.insertBefore(ruanganDiv, parent.lastElementChild);

    console.log('Ruangan baru ditambahkan dengan ID:', ruanganSekarang); // Debug untuk memastikan ID increment
}

function hapusRuangan(button) {
    // Hapus elemen ruangan
    const ruanganElement = button.closest('.border-l-4');
    ruanganElement.remove();
}
</script>
