<div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-auto p-6 relative">
    <h2 class="text-xl font-semibold text-gray-800 mb-4 text-center">Import Data Pengguna</h2>
    <div class="w-24 h-1 bg-yellow-400 mx-auto mt-1 mb-6 rounded"></div>

    <form action="{{ route('admin.pengguna.import_file') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-900" for="file_input">Import File</label>
            <input name="file_input" id="file_input" type="file" accept=".xlsx, .xls" required
                class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <p class="mt-1 text-sm text-gray-500" id="file_input_help">
                Format yang didukung: .xlsx, .xlsm, .xml. Ukuran maksimal: 2MB
            </p>
        </div>


        <div class="flex justify-end gap-2">
            <button type="button" id="modal-close" class="px-4 py-2 rounded bg-gray-300 text-gray-700 hover:bg-gray-400 text-sm cursor-pointer">Batal</button>
            <button type="submit" class="px-4 py-2 rounded bg-blue-700 text-white hover:bg-blue-800 text-sm cursor-pointer">Import</button>
        </div>
    </form>

    <!-- Tombol close pojok kanan atas -->
    <button id="modal-close" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 cursor-pointer">
        <i class="fas fa-times"></i>
    </button>
</div>
