<div class="bg-white rounded-lg shadow-lg w-[400px] max-h-[90vh] overflow-y-auto p-5 relative">
    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl">
        <i class="fas fa-times"></i>
    </button>
    <h2 class="text-xl font-semibold text-center">Tambah Data Lokasi</h2>
    <div class="w-24 h-1 bg-orange-400 mx-auto mt-2 rounded-full"></div>

    <!-- Form -->
    <form action="{{ route('admin.lokasi.store') }}" method="POST">
        @csrf
        <div class="space-y-4 mt-4 text-sm text-gray-700">
            <!-- Nama Lokasi -->
            <div>
                <label class="font-medium">Nama Lokasi <span class="text-red-500">*</span></label>
                <input type="text" name="nama_gedung" placeholder="Contoh: Gedung Sipil"
                    class="w-full mt-1 px-3 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-400">
            </div>

            <!-- Lantai dan Ruangan -->
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

        <!-- Container Lantai & Ruangan -->
        <div id="lantaiContainer" class="mt-5 space-y-3"></div>

        <!-- Tombol Submit -->
        <div class="mt-6">
            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700">
                Tambah
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
        const lantaiId = `lantai-${lantaiCounter}`;
        const container = document.getElementById('lantaiContainer');

        const lantaiElement = document.createElement('div');
        lantaiElement.className = "bg-blue-500";
        lantaiElement.innerHTML = `
            <div class="flex justify-between items-center px-4 py-2 cursor-pointer bg-blue-200">
                <div class="font-medium">${lantaiNama}</div>
                <input type="hidden" name="lantai[${lantaiCounter}][nama_lantai]" value="${lantaiNama}">
                <div class="flex items-center gap-2">
                    <button onclick="hapusLantai(event, '${lantaiId}')" class="text-red-500 hover:text-red-700" title="Hapus Lantai">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="px-4 py-3 space-y-3" id="${lantaiId}" style="background-color: #D9D9D9;">
                <div class="border-l-4 border-orange-400 pl-3">
                    <label class="text-sm font-medium">Nama Ruangan <span class="text-red-500">*</span></label>
                    <input type="text" name="lantai[${lantaiCounter}][ruangan][]" placeholder="Contoh: LPR 1"
                        class="w-full mt-1 px-3 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-400">
                </div>
                <button type="button" onclick="tambahRuangan(this, ${lantaiCounter})" class="text-blue-600 text-sm hover:underline">
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

    function tambahRuangan(button, lantaiId) {
        const parent = button.parentElement;
        const ruanganDiv = document.createElement('div');
        ruanganDiv.className = "border-l-4 border-orange-400 pl-3";

        ruanganDiv.innerHTML = `
            <label class="text-sm font-medium">Nama Ruangan <span class="text-red-500">*</span></label>
            <input type="text" name="lantai[${lantaiId}][ruangan][]" placeholder="Contoh: LPR 1"
                class="w-full mt-1 px-3 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-400 mt-2">
        `;

        parent.insertBefore(ruanganDiv, button);
    }
</script>