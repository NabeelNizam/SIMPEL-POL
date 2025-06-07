<!-- Isi yang dimuat oleh AJAX ke dalam #myModal -->
<div class="bg-white rounded-lg shadow-lg max-w-3xl w-full p-6 relative border-t border-blue-700">

    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
        <i class="fas fa-times"></i>
    </button>

    <h2 class="text-xl font-semibold mb-2 text-center">Tambah Data Role</h2>
    <div class="w-[205px] h-1 bg-yellow-400 mx-auto mt-1 mb-6 rounded"></div>

    <form id="form-tambah-role" action="{{ route('admin.role.store_ajax') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4" enctype="multipart/form-data">
        @csrf

        <div class="col-span-2">
            <label class="block text-sm font-medium mb-1">Kode Role<span class="text-red-500">*</span></label>
            <input type="text" name="kode_role" id="kode_role" class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Isi Kode Role..." required>
            <span id="kode_role-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>
        
        <div class="col-span-2">
            <label class="block text-sm font-medium mb-1">Nama Role<span class="text-red-500">*</span></label>
            <input type="text" name="nama_role" id="nama_role" class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Isi Nama Role..." required>
            <span id="nama_role-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div class="col-span-2 text-right mt-4">
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md cursor-pointer">
                <div class="flex justify-center items-center gap-[10px]">
                    <img src="{{ asset('icons/light/Check-circle.svg') }}" alt="Simpan" class="w-6 h-6">
                    <p>Simpan</p>
                </div>
            </button>
        </div>
    </form>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: `{!! implode('<br>', $errors->all()) !!}`,
            });
        </script>
    @endif
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
<script>
$(document).ready(function() {

    $("#form-tambah-role").validate({
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
            }
            nama_role: {
                required: "Nama Role wajib diisi",
                minlength: "Nama Role minimal 2 karakter",
                maxlength: "Nama Role maksimal 35 karakter"
            }
        },
    });
});
</script>

