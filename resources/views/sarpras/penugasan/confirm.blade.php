<div class="bg-white rounded-lg shadow-lg max-w-xl w-full p-6 relative border-t border-blue-700">

    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 cursor-pointer">
        <i class="fas fa-times"></i>
    </button>

    <h2 class="text-xl font-semibold mb-2 text-center">Tugaskan Teknisi</h2>
    <div class="w-[145px] h-1 bg-blue-400 mx-auto mt-1 mb-2 rounded"></div>
    <div class="p-4 md:p-5 text-center">
        <svg class="mx-auto mb-4 text-blue-600 w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <h3 class="mb-5 text-lg font-semibold text-gray-700">Apakah Anda yakin ingin menugaskan teknisi untuk memperbaiki fasilitas ini?</h3>

        <!-- Info Teknisi -->
        <div class="mb-5 text-left bg-gray-50 rounded p-4 border border-blue-100">
            <h4 class="font-medium text-blue-700 mb-2">Teknisi yang akan ditugaskan:</h4>
            <p class="text-gray-700 font-normal"><strong class="font-semibold">Nama:</strong> {{ $inspeksi->teknisi->nama }}</p>
            <p class="text-gray-700 font-normal"><strong class="font-semibold">NIP:</strong> {{ $inspeksi->teknisi->pegawai->nip }}</p>
        </div>

        <div class="flex justify-center items-center gap-4 flex-row-reverse">
            <form action="{{ route('sarpras.penugasan.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id_inspeksi" value="{{ $inspeksi->id_inspeksi }}">
                <button id="confirm-delete"
                    class="text-white bg-blue-600 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center cursor-pointer">
                    Ya, Tugaskan
                </button>
            </form>
    
            <button type="button" id="modal-close"
                class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-300 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 cursor-pointer">Batal
            </button>
        </div>
    </div>
</div>
