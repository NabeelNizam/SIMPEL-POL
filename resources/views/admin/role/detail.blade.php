<!-- Modal Konten Detail Role -->
<div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 relative border-t-4 border-blue-600">

    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl cursor-pointer">
        <i class="fas fa-times"></i>
    </button>

    <h2 class="text-xl font-semibold text-center">Detail Data Role</h2>
    <div class="w-[185px] h-1 bg-yellow-400 mx-auto mt-1 mb-6 rounded"></div>

    <!-- Detail Informasi -->
    
        <div class="ms-8 my-16">
            <p class="font-semibold text-gray-500">Kode Role</p>
            <p>{{ $role->kode_role ?? '-' }}</p>
        </div>
        <div class="ms-8 mt-12 mb-20">
            <p class="font-semibold text-gray-500">Nama Role</p>
            <p>{{ $role->nama_role ?? '-' }}</p>
        </div>
</div>

<script>
    $(document).on('click', '#modal-close', function () {
        $('#myModal').addClass('hidden').removeClass('flex').html('');
    });
</script>
