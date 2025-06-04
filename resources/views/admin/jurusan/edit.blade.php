<!-- Isi yang dimuat oleh AJAX ke dalam #myModal -->
<div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 relative border-t border-blue-700">

    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 cursor-pointer">
        <i class="fas fa-times"></i>
    </button>

    <h2 class="text-xl font-semibold mb-2 text-center">Edit Data Jurusan</h2>
    <div class="w-[170px] h-1 bg-yellow-400 mx-auto mt-1 mb-6 rounded"></div>

    <form id="form-tambah-jurusan" action="{{ route('admin.jurusan.update', $jurusan->id_jurusan) }}" method="POST" class="grid grid-cols-1 gap-4" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <div class="mt-1">
            <label class="block text-sm font-medium mb-1">Kode Jurusan<span class="text-red-500">*</span></label>
            <input type="text" name="kode_jurusan" id="kode_jurusan" class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Kode" required value="{{ $jurusan->kode_jurusan }}">
            <span id="kode_jurusan-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Nama Jurusan<span class="text-red-500">*</span></label>
            <input type="text" name="nama_jurusan" id="nama_jurusan" class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Nama" required value="{{ $jurusan->nama_jurusan }}">
            <span id="nama_jurusan-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div class="text-right mt-4">
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md cursor-pointer">
                <div class="flex justify-center items-center gap-[10px]">
                    <img src="{{ asset('icons/light/Check-circle.svg') }}" alt="Simpan" class="w-6 h-6">
                    <p>Simpan</p>
                </div>
            </button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
<script>
$(document).ready(function() {
    $("#form-tambah-jurusan").validate({
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
            kode_jurusan: {
                required: true,
                minlength: 2,
                maxlength: 5,
            },
            nama_jurusan: {
                required: true,
                minlength: 8,
                maxlength: 45,
            },
        },
        messages: {
            kode_jurusan: {
                required: "Kode jurusan wajib diisi",
                minlength: "Kode jurusan minimal 2 karakter",
                maxlength: "Kode jurusan maksimal 5 karakter"
            },
            nama_jurusan: {
                required: "Nama jurusan wajib diisi",
                minlength: "Nama jurusan minimal 8 karakter",
                maxlength: "Nama jurusan maksimal 45 karakter"
            },
        },
    });
});
</script>

