<!-- Modal Konten Edit -->
<div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6 relative border-t-4 border-blue-600">

    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
        <i class="fas fa-times"></i>
    </button>

    <h2 class="text-xl font-semibold mb-4 text-center">Edit Role</h2>
    <div class="w-12 h-1 bg-yellow-400 mx-auto mt-1 mb-6 rounded"></div>

    <form id="form-edit-role" action="{{ route('admin.role.update_ajax', $role->id_role) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="ms-8 me-8 my-16">
            <label class="block text-sm font-medium mb-1">Kode Role <span class="text-red-500">*</span></label>
            <input type="text" name="kode_role" id="kode_role" value="{{ $role->kode_role }}" class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Nama">
            <span id="kode_role-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>
        <div class="ms-8 me-8 mt-12 mb-20">
            <label class="block text-sm font-medium mb-1">Nama Role <span class="text-red-500">*</span></label>
            <input type="text" name="nama_role" id="nama_role" value="{{ $role->nama_role }}" class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Nama">
            <span id="nama_role-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div class="col-span-2 text-right mt-4">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md cursor-pointer">Update</button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>

<script>
$(document).ready(function() {
    $("#form-edit-role").validate({
        errorElement: 'span',
        errorClass: 'text-xs text-red-500 mt-1 error-text',
        highlight: function(element) {},
        unhighlight: function(element) {},
        errorPlacement: function(error, element) {
            var errorContainer = element.next('.error-text');
            if (errorContainer.length) {
                errorContainer.replaceWith(error);
            } else {
                error.insertAfter(element);
            }
        },
        rules: {
            kode_role: {
                required: true,
                minlength: 2,
            },
            nama_role: {
                required: true,
                minlength: 2,
                maxlength: 35,
            },
        },
        messages: {
            kode_role: {
                required: "Kode Role wajib diisi",
                minlength: "Kode Role minimal 2 karakter",
            },
            nama_role: {
                required: "Nama Role wajib diisi",
                minlength: "Nama Role minimal 2 karakter",
                maxlength: "Nama Role maksimal 35 karakter"
            }
        },
        submitHandler: function(form) {
        const formData = new FormData(form);

            $.ajax({
                url: form.action,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
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
                        $.each(response.msgField, function(prefix, val) {
                            $('#' + prefix + '-error').text(val[0]);
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: xhr.responseJSON?.message || 'Gagal menyimpan data'
                    });
                    $('#myModal').addClass('hidden').removeClass('flex').html('');
                }
            });
            return false;
        }
    });
});
</script>
