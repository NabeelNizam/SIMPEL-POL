<!-- Modal Konten Edit -->
<div class="bg-white rounded-lg shadow-lg max-w-3xl w-full p-6 relative">

    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
        <i class="fas fa-times"></i>
    </button>

    <h2 class="text-xl font-semibold mb-4 text-center">Edit Profil</h2>
    <div class="w-24 h-1 bg-yellow-400 mx-auto mt-1 mb-6 rounded"></div>

    <form id="form-edit-profil" action="{{ url('/profil/' . $user->id_user . '/update_ajax') }}" method="POST"
        class="grid grid-cols-1 md:grid-cols-2 gap-4" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1">Nama <span class="text-red-500">*</span></label>
            <input type="text" name="nama" id="nama" value="{{ $user->nama }}"
                class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Nama">
            <span id="nama-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">No. Telepon <span class="text-red-500">*</span></label>
            <input type="text" name="telepon" id="telepon" value="{{ $user->no_hp }}"
                class="w-full border rounded-md px-3 py-2 text-sm" placeholder="No. Telepon">
            <span id="telepon-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" id="email" value="{{ $user->email }}"
                class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Email">
            <span id="email-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">{{ isset($user->pegawai->nip) ? "NIP" : 'NIM' }}<span
                    class="text-red-500">*</span></label>
            <input type="text" name="identifier" id="identifier" value="{{ $identifier }}"
                class="w-full border rounded-md px-3 py-2 text-sm"
                placeholder="{{ isset($user->pegawai->nip) ? "NIP" : 'NIM' }}">
            <span id="identifier-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Username <span class="text-red-500">*</span></label>
            <input type="text" name="username" id="username" value="{{ $user->username }}"
                class="w-full border rounded-md px-3 py-2 text-sm" placeholder="username">
            <span id="username-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Jurusan <span class="text-red-500">*</span></label>
            <select name="jurusan" id="jurusan" class="w-full border rounded-md px-3 py-2 text-sm">
                <option value="">- Pilih Jurusan -</option>
                @foreach($jurusan as $j)
                    <option value="{{ $j->id_jurusan }}" {{ $j->id_jurusan == $user->id_jurusan ? 'selected' : '' }}>
                        {{ $j->nama_jurusan }}
                    </option>
                @endforeach
            </select>
            <span id="jurusan-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div class="col-span-2">
            <label class="block text-sm font-medium mb-1">Foto Profil <span class="text-red-500">*</span></label>
            <span id="fotoprofil" class="text-xs text-gray-600">*abaikan jika tidak diganti</span>
            <input type="file" name="fotoprofil" id="fotoprofil" value="{{ $user->foto_profil }}"
                class="w-full border rounded-md px-3 text-sm" placeholder="Pilih Foto">
            <span id="fotoprofil-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div class="col-span-2 text-right mt-4 flex flex-row-reverse">
            <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md flex cursor-pointer">
                <img src="{{ asset('icons/light/Check-circle.svg') }}" alt="centang" class="mr-2 w-5">Update
            </button>
        </div>
    </form>
</div>

<script>
    $.validator.addMethod("fileExtensionIfFilled", function (value, element, param) {
        if (value === "") return true;
        var ext = value.split('.').pop().toLowerCase();
        return param.split('|').includes(ext);
    }, "Format file tidak valid");

    $(document).ready(function () {
        $("#form-edit-profil").validate({
            rules: {
                nama: "required",
                telepon: "required",
                email: {
                    required: true,
                    email: true
                },
                username: "required",
                jurusan: "required",
                identifier: "required",
                fotoprofil: {
                    required: false,
                    fileExtensionIfFilled: "jpg|jpeg|png|gif|svg"
                }
            },
            messages: {
                nama: "Nama wajib diisi",
                telepon: "Nomor Telepon wajib diisi",
                email: {
                    required: "Email wajib diisi",
                    email: "Format email tidak valid"
                },
                username: "Username wajib diisi",
                jurusan: "Pilih jurusan",
                identifier: "{{ isset($user->pegawai->nip) ? 'NIP' : 'NIM' }} wajib diisi",
                fotoprofil: {
                    required: "Foto profil wajib diisi",
                    fileExtensionIfFilled: "Format file harus jpg, jpeg, png, gif, atau svg"
                }
            },


            errorElement: 'span',
            errorPlacement: function (error, element) {
                var id = element.attr("id");
                $("#" + id + "-error").html(error);
            },

            // ⬇️ Biarkan submitHandler tetap di bawah ⬇️
            submitHandler: function (form) {
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
                        } else {                            
                            $('#myModal').addClass('hidden').removeClass('flex').html('');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });

                        }
                    },
                     error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Terjadi kesalahan pada server.'
            });
        }
                });
                return false;
            }
        });
    });
</script>