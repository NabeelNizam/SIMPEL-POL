{{ $erril_test = env('FORM_AUTOFILL', false) }}
<!-- Isi yang dimuat oleh AJAX ke dalam #myModal -->
<div class="bg-white rounded-lg shadow-lg max-w-3xl w-full p-6 relative border-t border-blue-700">

    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
        <i class="fas fa-times"></i>
    </button>

    <h2 class="text-xl font-semibold mb-2 text-center">Tambah Pengguna</h2>
    <div class="w-24 h-1 bg-yellow-400 mx-auto mt-1 mb-6 rounded"></div>

    <form id="form-tambah-pengguna" action="{{ route('admin.pengguna.store_ajax') }}" method="POST"
        class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">Nama <span class="text-red-500">*</span></label>
            <input type="text" name="nama" id="nama" class="w-full border rounded-md px-3 py-2 text-sm"
                placeholder="Nama" @if ($erril_test) value={{ fake()->unique()->name() }} @endif>
            <span id="nama-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">No. Identifikasi (NIM atau NIP) <span
                    class="text-red-500">*</span></label>
            <input type="text" name="identifier" id="identifier" class="w-full border rounded-md px-3 py-2 text-sm"
                placeholder="No. Identifikasi"
                @if ($erril_test) value={{ fake()->unique()->numerify('####') }} @endif>
            <span id="identifier-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">No. Telepon <span class="text-red-500">*</span></label>
            <input type="text" name="telepon" id="telepon" class="w-full border rounded-md px-3 py-2 text-sm"
                placeholder="No. Telepon"
                @if ($erril_test) value={{ fake()->unique()->numerify('##########') }} @endif>
            <span id="telepon-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" id="email" class="w-full border rounded-md px-3 py-2 text-sm"
                placeholder="Email" @if ($erril_test) value={{ fake()->unique()->email() }} @endif>
            <span id="email-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Jurusan <span class="text-red-500">*</span></label>
            <select name="jurusan" id="jurusan" class="w-full border rounded-md px-3 py-2 text-sm">
                <option value="">- Pilih Jurusan -</option>
                @foreach ($jurusan as $j)
                    <option value="{{ $j->id_jurusan }}" @if ($erril_test) selected="selected" @endif>
                        {{ $j->nama_jurusan }}</option>
                @endforeach
            </select>
            <span id="jurusan-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Role <span class="text-red-500">*</span></label>
            <select name="id_role" id="id_role" class="w-full border rounded-md px-3 py-2 text-sm">
                <option value="">- Pilih Role -</option>
                @foreach ($role as $r)
                    <option value="{{ $r->id_role }}">{{ $r->nama_role }}</option>
                @endforeach
            </select>
            <span id="id_role-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Username <span class="text-red-500">*</span></label>
            <input type="text" name="username" id="username" class="w-full border rounded-md px-3 py-2 text-sm"
                placeholder="Username"
                @if ($erril_test) value={{ fake()->unique()->userName() }} @endif>
            <span id="username-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Password <span class="text-red-500">*</span></label>
            <input type="password" name="password" id="password" class="w-full border rounded-md px-3 py-2 text-sm"
                placeholder="Password" @if ($erril_test) value={{ 'password' }} @endif>
            <span id="password-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div class="col-span-2 text-right mt-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Simpan</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        $("#form-tambah-pengguna").validate({
            rules: {
                nama: "required",
                telepon: "required",
                email: {
                    required: true,
                    email: true
                },
                username: "required",
                jurusan: "required",
                id_role: "required",
                password: {
                    required: true,
                    minlength: 6
                }
            },
            messages: {
                nama: "Nama wajib diisi",
                telepon: "Telepon wajib diisi",
                email: {
                    required: "Email wajib diisi",
                    email: "Format email tidak valid"
                },
                username: "Username wajib diisi",
                jurusan: "Pilih jurusan",
                id_role: "Pilih role",
                password: {
                    required: "Password wajib diisi",
                    minlength: "Minimal 6 karakter"
                }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            dataUser.ajax.reload();
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
                    }
                });
                return false;
            }
        });
    });
</script>
