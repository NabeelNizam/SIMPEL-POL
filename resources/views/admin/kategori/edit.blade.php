<!-- Isi yang dimuat oleh AJAX ke dalam #myModal -->
<div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 relative border-t border-blue-700">

    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 cursor-pointer">
        <i class="fas fa-times"></i>
    </button>

    <h2 class="text-xl font-semibold mb-2 text-center">Edit Data Kategori</h2>
    <div class="w-[175px] h-1 bg-yellow-400 mx-auto mt-1 mb-6 rounded"></div>

    <form id="form-tambah-kategori" action="{{ route('admin.kategori.update', $kategori->id_kategori) }}" method="POST" class="grid grid-cols-1 gap-4" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <div class="mt-1">
            <label class="block text-sm font-medium mb-1">Kode Kategori<span class="text-red-500">*</span></label>
            <input type="text" name="kode_kategori" id="kode_kategori" class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Kode" required value="{{ $kategori->kode_kategori }}">
            <span id="kode_kategori-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Nama Kategori<span class="text-red-500">*</span></label>
            <input type="text" name="nama_kategori" id="nama_kategori" class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Nama" required value="{{ $kategori->nama_kategori }}">
            <span id="nama_kategori-error" class="text-xs text-red-500 mt-1 error-text"></span>
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
    $("#form-tambah-kategori").validate({
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
            kode_kategori: {
                required: true,
                minlength: 2,
                maxlength: 5,
            },
            nama_kategori: {
                required: true,
                minlength: 5,
                maxlength: 30,
            },
        },
        messages: {
            kode_kategori: {
                required: "Kode kategori wajib diisi",
                minlength: "Kode kategori minimal 2 karakter",
                maxlength: "Kode kategori maksimal 5 karakter"
            },
            nama_kategori: {
                required: "Nama kategori wajib diisi",
                minlength: "Nama kategori minimal 5 karakter",
                maxlength: "Nama kategori maksimal 30 karakter"
            },
        },
    });
});
</script>

