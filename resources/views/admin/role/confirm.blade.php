<div class="bg-white rounded-lg shadow-lg max-w-xl w-full p-6 relative border-t-4 border-blue-700">

    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 cursor-pointer">
        <i class="fas fa-times"></i>
    </button>

    <h2 class="text-xl font-semibold mb-2 text-center">Hapus Role</h2>
    <div class="w-[145px] h-1 bg-red-400 mx-auto mt-1 mb-6 rounded"></div>
    <div class="p-4 md:p-5 text-center">
        <svg class="mx-auto mb-4 text-blue-600 w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <h3 class="mb-5 text-lg font-semibold text-gray-700">Apakah Anda yakin ingin menghapus role ini?</h3>
        <div class="flex justify-center items-center gap-4 flex-row-reverse">
            <form action="{{ route('admin.role.destroy_ajax', $role->id_role) }}" method="POST">
                @csrf
                @method('DELETE')
                <button id="confirm-delete"
                    class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center cursor-pointer">
                    Ya, Hapus
                </button>
            </form>

            <button type="button" id="modal-close"
                class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-300 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 cursor-pointer">Batal
            </button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('form').on('submit', function (e) {
            e.preventDefault(); // mencegah reload halaman

            const form = this;

            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function (response) {
                    if (response.status) {
                        $('#myModal').addClass('hidden').removeClass('flex').html('');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        reloadData();
                    } else {
                        $('.error-text').text('');
                        $.each(response.msgField, function (prefix, val) {
                            $('#' + prefix + '-error').text(val[0]);
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message
                        });
                        $('#myModal').addClass('hidden').removeClass('flex').html('');
                    }
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: response.message
                    });
                    $('#myModal').addClass('hidden').removeClass('flex').html('');
                }
            });

            return false;
        });
    });
</script>