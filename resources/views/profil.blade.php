<div class="h-4 bg-blue-600 w-full mx-auto -mt-1 rounded-bottom-full"></div>
<div class="relative bg-white rounded-bottom-lg shadow-xl max-w-xl w-full mx-auto overflow-hidden">
    <div class="px-8 relative w-full text-right">
        <!-- Tombol Close (X) di kanan atas -->
            <button type="button"
                class="right-4 top-3  text-gray-600 hover:text-black text-2xl transition-colors duration-200"
                data-dismiss="modal" aria-label="Close">
                &times;
            </button>
    </div>
    <div class="relative w-full text-center">
        <!-- Judul tengah + border bawah kuning -->
        <div class="py-2 inline-block mt-4">
            <h2 class="text-lg font-semibold text-gray-800">Profil Pengguna</h2>
        </div>
    </div>

    <div class="h-1 bg-yellow-400 w-24 mx-auto -mt-1 rounded-full"></div>
    <div class="p-8">
        <div class="flex items-center pb-6 border-b border-gray-200 mb-8">
            <div>
                <img src="{{ asset('path/to/your/local/profile-picture.jpg') }}" alt="User Avatar"
                class="rounded-xl w-24 h-24 object-cover border-2 border-gray-200 mr-4">
            </div>
            <div class="mx-4">
                <h4 class="text-xl font-semibold text-gray-800">Admin Bin</h4>
                <p class="text-gray-600 text-sm">Admin</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-6 gap-x-8">
            <div>
                <p class="text-sm font-medium text-gray-500">Email</p>
                <p class="text-gray-800" id="email">adminben@gmail.com</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">NIP</p>
                <p class="text-gray-800" id="nip">1234rtyu</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Jurusan</p>
                <p class="text-gray-800" id="jurusan">Teknologi Informasi</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Username</p>
                <p class="text-gray-800" id="username">BentEn</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">No. Telepon</p>
                <p class="text-gray-800" id="telepon">1234567</p>
            </div>
            {{-- <div>
                <p class="text-sm font-medium text-gray-500">Password</p>
                <p class="text-gray-800">********</p>
            </div> --}}
        </div>
    </div>
</div>