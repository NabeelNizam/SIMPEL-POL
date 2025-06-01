<!-- Isi yang dimuat oleh AJAX ke dalam #myModal -->
<div class="bg-white rounded-lg shadow-lg max-w-3xl w-full p-6 relative border-t border-blue-700">

    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
        <i class="fas fa-times"></i>
    </button>

    <h2 class="text-xl font-semibold mb-2 text-center">Tambah Pengguna</h2>
    <div class="w-24 h-1 bg-yellow-400 mx-auto mt-1 mb-6 rounded"></div>

    <form id="form-tambah-pengguna" action="{{ route('admin.fasilitas.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">Gedung <span class="text-red-500">*</span></label>
            <select required name="gedung" id="gedung" class="cursor-pointer w-full border rounded-md px-3 py-2 text-sm">
                <option value="">- Pilih Gedung -</option>
                @foreach($gedung as $g)
                    <option value="{{ $g->id_gedung }}">{{ $g->nama_gedung }}</option>
                @endforeach
            </select>
            <span id="gedung-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Lantai <span class="text-red-500">*</span></label>
            <select required name="lantai" id="lantai" class="w-full border rounded-md px-3 py-2 text-sm bg-gray-100 cursor-not-allowed" disabled>
                <option value="">- Pilih Lantai -</option>
            </select>
            <span id="lantai-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Ruangan<span class="text-red-500">*</span></label>
            <select required name="ruangan" id="ruangan" class="w-full border rounded-md px-3 py-2 text-sm bg-gray-100 cursor-not-allowed" disabled>
                <option value="">- Pilih Ruangan -</option>
            </select>
            <span id="ruangan-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div class="col-span-2">
            <label class="block text-sm font-medium mb-1">Nama Fasilitas<span class="text-red-500">*</span></label>
            <input type="text" name="nama_fasilitas" id="nama_fasilitas" class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Nama" required>
            <span id="nama_fasilitas-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Kategori Fasilitas <span class="text-red-500">*</span></label>
            <select required name="kategori" id="kategori" class="cursor-pointer w-full border rounded-md px-3 py-2 text-sm">
                <option value="">- Kategori Fasilitas -</option>
                @foreach($kategori as $k)
                    <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                @endforeach
            </select>
            <span id="kategori-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Kondisi Fasilitas<span class="text-red-500">*</span></label>
            <select required name="kondisi_fasilitas" id="kondisi_fasilitas" class="cursor-pointer w-full border rounded-md px-3 py-2 text-sm">
                <option value="">- Kondisi Fasilitas -</option>
                <option value="BAIK">Baik</option>
                <option value="RUSAK">Rusak</option>
                <option value="RUSAK BERAT">Rusak Berat</option>
            </select>
            <span id="kondisi_fasilitas-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div class="col-span-2">
            <label class="block text-sm font-medium mb-1">
                Deskripsi Fasilitas <span class="text-red-500">*</span>
            </label>
            <textarea name="deskripsi" id="deskripsi" rows="4" class="w-full border rounded-md px-3 py-2 text-sm resize-y" placeholder="Masukkan deskripsi fasilitas" required></textarea>
            <span id="deskripsi-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div class="col-span-2 text-right mt-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md cursor-pointer">Simpan</button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
$(document).ready(function() {
    $('#gedung').on('change', function () {
        let gedungID = $(this).val();
        
        // Reset dan disable lantai + ruangan
        $('#lantai').html('<option value="">- Pilih Lantai -</option>')
                    .prop('disabled', true)
                    .removeClass('cursor-pointer')
                    .addClass('cursor-not-allowed bg-gray-100');

        $('#ruangan').html('<option value="">- Pilih Ruangan -</option>')
                    .prop('disabled', true)
                    .removeClass('cursor-pointer')
                    .addClass('cursor-not-allowed bg-gray-100');

        if (gedungID) {
            $.get('/admin/fasilitas/get-lantai/' + gedungID, function (data) {
                $.each(data, function (key, val) {
                    $('#lantai').append(`<option value="${val.id_lantai}">${val.nama_lantai}</option>`);
                });

                // Enable lantai + style
                $('#lantai').prop('disabled', false)
                            .removeClass('cursor-not-allowed bg-gray-100')
                            .addClass('cursor-pointer');
            });
        }
    });

    $('#lantai').on('change', function () {
        let lantaiID = $(this).val();

        $('#ruangan').html('<option value="">- Pilih Ruangan -</option>')
                    .prop('disabled', true)
                    .removeClass('cursor-pointer')
                    .addClass('cursor-not-allowed bg-gray-100');

        if (lantaiID) {
            $.get('/admin/fasilitas/get-ruangan/' + lantaiID, function (data) {
                $.each(data, function (key, val) {
                    $('#ruangan').append(`<option value="${val.id_ruangan}">${val.nama_ruangan}</option>`);
                });

                // Enable ruangan + style
                $('#ruangan').prop('disabled', false)
                            .removeClass('cursor-not-allowed bg-gray-100')
                            .addClass('cursor-pointer');
            });
        }
    });
    
    $("#form-tambah-pengguna").validate({
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
            gedung: {
                required: true,
                digits: true
            },
            lantai: {
                required: true,
                digits: true
            },
            ruangan: {
                required: true,
                digits: true
            },
            nama_fasilitas: {
                required: true,
                minlength: 2,
                maxlength: 35,
            },
            kategori: {
                required: true,
                digits: true
            },
            kondisi_fasilitas: "required",
            deskripsi: {
                required: true,
                minlength: 10,
                maxlength: 255,
            },
        },
        messages: {
            gedung: {
                required: "Gedung wajib dipilih",
                digits: "Gedung tidak valid"
            },
            lantai: {
                required: "Lantai wajib dipilih",
                digits: "Lantai tidak valid"
            },
            ruangan: {
                required: "Ruangan wajib dipilih",
                digits: "Ruangan tidak valid"
            },
            nama_fasilitas: {
                required: "Nama fasilitas wajib diisi",
                minlength: "Nama fasilitas minimal 2 karakter",
                maxlength: "Nama fasilitas maksimal 35 karakter"
            },
            kategori: {
                required: "Kategori fasilitas wajib dipilih",
                digits: "Kategori tidak valid"
            },
            kondisi_fasilitas: "Kondisi fasilitas wajib dipilih",
            deskripsi: {
                required: "Deskripsi fasilitas wajib diisi",
                minlength: "Deskripsi minimal 10 karakter",
                maxlength: "Deskripsi maksimal 255 karakter"
            }
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#myModal').addClass('hidden').removeClass('flex').html('');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        dataFasilitas.ajax.reload();
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

