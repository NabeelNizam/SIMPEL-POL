<!-- Modal Konten Edit -->
<div class="bg-white rounded-lg shadow-lg max-w-3xl w-full p-6 relative">

    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
        <i class="fas fa-times"></i>
    </button>

    <h2 class="text-xl font-semibold mb-4 text-center">Edit Pengguna</h2>
    <div class="w-24 h-1 bg-yellow-400 mx-auto mt-1 mb-6 rounded"></div>

    <form id="form-edit-pengguna" action="{{ route('admin.pengguna.update_ajax', $user->id_user) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1">Nama <span class="text-red-500">*</span></label>
            <input type="text" name="nama" id="nama" value="{{ $user->nama }}" class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Nama">
            <span id="nama-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">No. Telepon <span class="text-red-500">*</span></label>
            <input type="text" name="telepon" id="telepon" value="{{ $user->no_hp }}" class="w-full border rounded-md px-3 py-2 text-sm" placeholder="No. Telepon">
            <span id="telepon-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" id="email" value="{{ $user->email }}" class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Email">
            <span id="email-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Username <span class="text-red-500">*</span></label>
            <input type="text" name="username" id="username" value="{{ $user->username }}" class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Username">
            <span id="username-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Jurusan <span class="text-red-500">*</span></label>
            <select name="id_jurusan" id="id_jurusan" class="w-full border rounded-md px-3 py-2 text-sm">
                <option value="">- Pilih Jurusan -</option>
                @foreach($jurusan as $j)
                    <option value="{{ $j->id_jurusan }}" {{ $j->id_jurusan == $user->id_jurusan ? 'selected' : '' }}>{{ $j->nama_jurusan }}</option>
                @endforeach
            </select>
            <span id="id_jurusan-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Role <span class="text-red-500">*</span></label>
            <select name="id_role" id="id_role" class="w-full border rounded-md px-3 py-2 text-sm">
                <option value="">- Pilih Role -</option>
                @foreach($role as $r)
                    <option value="{{ $r->id_role }}" {{ $r->id_role == $user->id_role ? 'selected' : '' }}>{{ $r->nama_role }}</option>
                @endforeach
            </select>
            <span id="id_role-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div class="col-span-2 text-right mt-4">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">Update</button>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    $("#form-edit-pengguna").validate({
        rules: {
            nama: "required",
            telepon: "required",
            email: {
                required: true,
                email: true
            },
            username: "required",
            jurusan: "required",
            id_role: "required"
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
            id_role: "Pilih role"
        },
        // submitHandler: function(form) {
        //     $.ajax({
        //         url: form.action,
        //         type: 'POST',
        //         data: $(form).serialize(),
        //         success: function(response) {
        //             if (response.status) {
        //                 $('#myModal').addClass('hidden').removeClass('flex').html('');
        //                 Swal.fire({
        //                     icon: 'success',
        //                     title: 'Berhasil',
        //                     text: response.message
        //                 });
        //                 dataUser.ajax.reload();
        //             } else {
        //                 $('.error-text').text('');
        //                 $.each(response.msgField, function(prefix, val) {
        //                     $('#' + prefix + '-error').text(val[0]);
        //                 });
        //                 Swal.fire({
        //                     icon: 'error',
        //                     title: 'Terjadi Kesalahan',
        //                     text: response.message
        //                 });
        //             }
        //         }
        //     });
        //     return false;
        // }
    });

    $(document).on('click', '#modal-close', function () {
        $('#myModal').addClass('hidden').removeClass('flex').html('');
    });
});
</script>
