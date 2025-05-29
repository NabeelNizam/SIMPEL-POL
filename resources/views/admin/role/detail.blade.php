<!-- Modal Konten Detail -->
<div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 relative">

    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl">
        <i class="fas fa-times"></i>
    </button>

    <h2 class="text-xl font-semibold text-center">Detail Pengguna</h2>
    <div class="w-12 h-1 bg-yellow-400 mx-auto mt-1 mb-6 rounded"></div>

    <!-- Profil Horizontal -->
    <div class="flex items-center mb-6 gap-4">
        <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.21 0 4.291.535 6.121 1.483M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-gray-800">{{ $user->nama }}</h3>
            <p class="text-sm text-gray-500">{{ $user->role->nama_role ?? '-' }}</p>
        </div>
    </div>

    <hr class="my-4">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-sm text-gray-700">
        <div>
            <p class="font-semibold">Email</p>
            <p class="break-words whitespace-normal">{{ $user->email ?? '-' }}</p>
        </div>
        <div>
            <p class="font-semibold">Username</p>
            <p>{{ $user->username ?? '-' }}</p>
        </div>
        <div>
            <p class="font-semibold">No. Telepon</p>
            <p>{{ $user->no_hp ?? '-' }}</p>
        </div>
        <div>
            <p class="font-semibold">Jurusan</p>
            <p>{{ $user->jurusan->nama_jurusan ?? '-' }}</p>
        </div>
        <div>
            <p class="font-semibold">NIP/NIM</p>
            <p>{{ $user->nip ?? '-' }}</p>
        </div>
    </div>
</div>


<script>
    $(document).on('click', '#modal-close', function () {
        $('#myModal').addClass('hidden').removeClass('flex').html('');
    });
</script>
