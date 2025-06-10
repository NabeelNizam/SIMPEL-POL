<!-- Modal Konten Edit -->
<div class="bg-white rounded-lg shadow-lg max-w-3xl w-100 p-6 relative">

    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
        <i class="fas fa-times"></i>
    </button>

    <h2 class="text-xl font-semibold mb-4 text-center">Ubah Kata Sandi</h2>
    <div class="w-24 h-1 bg-yellow-400 mx-auto mt-1 mb-6 rounded"></div>
    <form action="{{ route('profil.password.update') }}" method="POST" id="form-edit-password"
        class="m-5 flex flex-col gap-6" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        {{-- konfirmasi password lama --}}
        <div class="flex flex-col gap-1">
            <label for="old_password" class="block text-sm font-medium mb-1">
                Kata Sandi Lama
            </label>
            <input type="password" name="old_password" id="old_password"
                class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Kata Sandi Lama">
            <span id="old_password-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>
        <div class="flex flex-col gap-1">
            <label for="new_password" class="block text-sm font-medium mb-1">
                Kata Sandi Baru
            </label>
            <input type="password" name="new_password" id="new_password"
                class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Kata Sandi Baru">
            <span id="new_password-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>
        <div class="flex flex-col gap-1">
            <label for="confirm_password" class="block text-sm font-medium mb-1">
                Konfirmasi Kata Sandi Baru
            </label>
            <input type="password" name="confirm_password" id="confirm_password"
                class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Konfirmasi">
            <span id="new_password-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>
        <div class="col-span-2 flex flex-row-reverse">
            <button type="submit"
                class="bg-green-600 flex flex-row hover:bg-green-700 text-white px-4 py-2 rounded-md cursor-pointer">
                <img src="{{ asset('icons/light/Check-circle.svg') }}" alt="centang" class="mr-2 w-5">
                Simpan
            </button>
        </div>
    </form>

</div>

<script>
    $.validator.addMethod("fileExtensionIfFilled", function(value, element, param) {
        if (value === "") return true;
        var ext = value.split('.').pop().toLowerCase();
        return param.split('|').includes(ext);
    }, "Format file tidak valid");

    $(document).ready(function() {
        $("#form-edit-profil").validate({
            rules: {
                old_password: {
                    required: true,
                },
                new_password: {
                    required: true,
                    minlength: 6,
                    maxlength: 100
                },
                confirm_password: {
                    required: true,
                    equalTo: "#new_password"
                },
            },
            messages: {
                old_password: {
                    required: "Kata Sandi Lama wajib diisi",
                },
                new_password: {
                    required: "Kata Sandi Baru wajib diisi",
                    minlength: "Kata Sandi Baru minimal 6 karakter",
                    maxlength: "Kata Sandi Baru maksimal 100 karakter"
                },
                confirm_password: {
                    required: "Konfirmasi Kata Sandi Baru wajib diisi",
                    equalTo: "Konfirmasi Kata Sandi Baru harus sama dengan Kata Sandi Baru"
                },
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                var id = element.attr("id");
                $("#" + id + "-error").html(error);
            },

            // ⬇️ Biarkan submitHandler tetap di bawah ⬇️
            submitHandler: function(form) {
                var formData = new FormData(form);
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    processData: false, // WAJIB untuk FormData
                    contentType: false, // WAJIB untuk FormData
                    success: function(response) {
                        $('#myModal').addClass('hidden').removeClass('flex').html('');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            // Tampilkan pesan error validasi
                            let errors = xhr.responseJSON.errors;
                            let errorText = "";
                            $.each(errors, function(key, value) {
                                errorText += value[0] + "<br>";
                            });

                            Swal.fire({
                                icon: 'error',
                                title: 'Validasi Gagal!',
                                html: errorText
                            });
                        } else {
                            // Error lain (server, dsb)
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan!',
                                text: xhr.responseJSON.message ||
                                    'Server error!'
                            });
                        }
                    }
                });
                return false;
            }
        });
    });
</script>
