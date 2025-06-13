<!-- Isi yang dimuat oleh AJAX ke dalam #myModal -->
<div class="bg-white rounded-lg shadow-lg max-w-3xl w-full p-6 relative border-t border-blue-700">

    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 cursor-pointer">
        <i class="fas fa-times"></i>
    </button>

    <h2 class="text-xl font-semibold mb-2 text-center">Tambah Periode</h2>
    <div class="w-24 h-1 bg-yellow-400 mx-auto mt-1 mb-6 rounded"></div>

    <form id="form-tambah-periode" action="{{ route('admin.periode.store_ajax') }}" method="POST"
        class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">Kode Periode <span class="text-red-500">*</span></label>
            <input type="text" name="kode_periode" id="kode_periode" class="w-full border rounded-md px-3 py-2 text-sm"
                placeholder="Kode periode">
            <span id="kode_periode-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Tanggal Mulai <span class="text-red-500">*</span></label>
            <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Tanggal mulai">
            <span id="tanggal_mulai-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Tanggal Selesai <span class="text-red-500">*</span></label>
            <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Tanggal selesai">
            <span id="tanggal_selesai-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div class="col-span-2 text-right mt-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md cursor-pointer">Simpan</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        $("#form-tambah-periode").validate({
            rules: {
                kode_periode: "required",
                tannggal_mulai: {
                    required: true,
                    date: true
                },
                tanggal_selesai: {
                    required: true,
                    date: true
                }
            },
            messages: {
                kode_periode: "Kode Periode wajib diisi",
                tannggal_mulai: {
                    required: "Tanggal Mulai wajib diisi",
                    date: "Format tanggal tidak valid"
                },
                tanggal_selesai: {
                    required: "Tanggal Selesai wajib diisi",
                    date: "Format tanggal tidak valid"
                }
            },
            submitHandler: function (form) {
                $.ajax({
                    url: form.action,
                    type: 'POST',
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
                        }
                    }
                });
                return false;
            }
        });

        $(document).on('click', '#modal-close', function () {
            $('#myModal').addClass('hidden').removeClass('flex').html('');
        });
    });
</script>