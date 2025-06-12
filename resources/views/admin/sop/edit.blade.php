<div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6 relative border-t-4 border-blue-600">

    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
        <i class="fas fa-times"></i>
    </button>

    <h2 class="text-xl font-semibold mb-4 text-center">Edit SOP</h2>
    <div class="w-12 h-1 bg-yellow-400 mx-auto mt-1 mb-6 rounded"></div>

    <form action="{{ route('sop.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @foreach ($files as $role => $file)
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 capitalize">{{ $role }} SOP</label>
                @if ($file)
                    <div class="flex items-center mt-2">
                        <svg class="w-6 h-6 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 00-1.414 0L9 11.586 6.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l7-7a1 1 0 000-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-sm text-gray-700">{{ $file }}</span>
                        <button type="button" onclick="deleteFile('{{ $role }}')"
                            class="ml-4 px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600 focus:outline-none">
                            Hapus File
                        </button>
                    </div>
                @else
                    <input type="file" name="files[{{ $role }}]" class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer focus:outline-none">
                @endif
                <!-- Input tersembunyi untuk menyimpan role -->
                <input type="hidden" name="roles[]" value="{{ $role }}">
            </div>
        @endforeach

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700">
            Simpan Perubahan
        </button>
    </form>
</div>

<script>
    function deleteFile(role) {
        if (confirm('Apakah Anda yakin ingin menghapus file ini?')) {
            // Kirim permintaan untuk menghapus file
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ route('sop.delete', ':role') }}`.replace(':role', role);
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>