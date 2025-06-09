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

