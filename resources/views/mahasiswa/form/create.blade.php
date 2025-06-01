<!-- Isi yang dimuat oleh AJAX ke dalam #myModal -->
<div class="bg-white rounded-lg shadow-lg max-w-3xl w-full p-6 relative border-t border-blue-700">

    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
        <i class="fas fa-times"></i>
    </button>

    <h2 class="text-xl font-semibold mb-2 text-center">Tambah Pengaduan</h2>
    <div class="w-24 h-1 bg-yellow-400 mx-auto mt-1 mb-6 rounded"></div>

   <form id="form-tambah-laporan" action="{{ route('mahasiswa.form.store_ajax') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @csrf

    <div>
        <label class="block text-sm font-medium mb-1">Gedung <span class="text-red-500">*</span></label>
        <select name="gedung" id="gedung" class="w-full border rounded-md px-3 py-2 text-sm">
            <option value="">Pilih gedung</option>
            @foreach($gedung as $g)
                <option value="{{ $g->id_gedung }}">{{ $g->nama_gedung }}</option>
            @endforeach
        </select>
        <span id="gedung-error" class="text-xs text-red-500 mt-1 error-text"></span>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Lantai <span class="text-red-500">*</span></label>
        <select name="lantai" id="lantai" class="w-full border rounded-md px-3 py-2 text-sm" disabled>
            <option value="">Pilih lantai</option>
        </select>
        <span id="lantai-error" class="text-xs text-red-500 mt-1 error-text"></span>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Ruangan <span class="text-red-500">*</span></label>
        <select name="ruangan" id="ruangan" class="w-full border rounded-md px-3 py-2 text-sm" disabled>
            <option value="">Pilih ruangan</option>
        </select>
        <span id="ruangan-error" class="text-xs text-red-500 mt-1 error-text"></span>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Nama Fasilitas <span class="text-red-500">*</span></label>
        <select name="fasilitas" id="fasilitas" class="w-full border rounded-md px-3 py-2 text-sm" disabled>
            <option value="">Pilih fasilitas</option>
        </select>
        <span id="fasilitas-error" class="text-xs text-red-500 mt-1 error-text"></span>
    </div>

    <div class="col-span-2">
        <label class="block text-sm font-medium mb-1">Bukti Foto</label>
        <input type="file" name="foto" id="foto" accept=".jpg,.jpeg,.png" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <small class="text-gray-500">Format yang didukung: JPG, PNG. Ukuran maksimal: 2MB</small>
        <span id="foto-error" class="text-xs text-red-500 mt-1 error-text"></span>
    </div>

    <div class="col-span-2">
        <label class="block text-sm font-medium mb-1">Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi" rows="4" class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Deskripsi"></textarea>
        <span id="deskripsi-error" class="text-xs text-red-500 mt-1 error-text"></span>
    </div>

    <div class="col-span-2 text-right mt-4">
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
            <i class="fas fa-check-circle"></i> Simpan
        </button>
    </div>
</form>
</div>
<script>
$(document).ready(function() {
     // Ketika Gedung dipilih
    $('#gedung').on('change', function() {
        let gedungId = $(this).val();
        $('#lantai').prop('disabled', true).html('<option value="">Pilih lantai</option>');
        $('#ruangan').prop('disabled', true).html('<option value="">Pilih ruangan</option>');
        $('#fasilitas').prop('disabled', true).html('<option value="">Pilih fasilitas</option>');

        if (gedungId) {
            $.ajax({
                url: "{{ route('mahasiswa.form.get_lantai') }}",
                type: "GET",
                data: { gedung_id: gedungId },
                beforeSend: function() {
                    $('#lantai').html('<option value="">Memuat...</option>');
                },
                success: function(response) {
                    if (response.status) {
                        $('#lantai').prop('disabled', false).html(response.options);
                    } else {
                        $('#lantai').html('<option value="">Tidak ada lantai</option>');
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Gagal memuat data lantai.'
                    });
                }
            });
        }
    });

    // Ketika Lantai dipilih
    $('#lantai').on('change', function() {
        let lantaiId = $(this).val();
        $('#ruangan').prop('disabled', true).html('<option value="">Pilih ruangan</option>');
        $('#fasilitas').prop('disabled', true).html('<option value="">Pilih fasilitas</option>');

        if (lantaiId) {
            $.ajax({
                url: "{{ route('mahasiswa.form.get_ruangan') }}",
                type: "GET",
                data: { lantai_id: lantaiId },
                beforeSend: function() {
                    $('#ruangan').html('<option value="">Memuat...</option>');
                },
                success: function(response) {
                    if (response.status) {
                        $('#ruangan').prop('disabled', false).html(response.options);
                    } else {
                        $('#ruangan').html('<option value="">Tidak ada ruangan</option>');
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Gagal memuat data ruangan.'
                    });
                }
            });
        }
    });

    // Ketika Ruangan dipilih
    $('#ruangan').on('change', function() {
        let ruanganId = $(this).val();
        $('#fasilitas').prop('disabled', true).html('<option value="">Pilih fasilitas</option>');

        if (ruanganId) {
            $.ajax({
                url: "{{ route('mahasiswa.form.get_fasilitas') }}",
                type: "GET",
                data: { ruangan_id: ruanganId },
                beforeSend: function() {
                    $('#fasilitas').html('<option value="">Memuat...</option>');
                },
                success: function(response) {
                    if (response.status) {
                        $('#fasilitas').prop('disabled', false).html(response.options);
                    } else {
                        $('#fasilitas').html('<option value="">Tidak ada fasilitas</option>');
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Gagal memuat data fasilitas.'
                    });
                }
            });
        }
    });

    // Validasi Form
    $("#form-tambah-laporan").validate({
        rules: {
            gedung: "required",
            lantai: "required",
            ruangan: "required",
            fasilitas: "required",
            foto: {
                required: true,
                extension: "jpg|jpeg|png",
                filesize: 2 * 1024 * 1024 // 2MB
            },
            deskripsi: {
                required: true,
                minlength: 10
            }
        },
        messages: {
            gedung: "Gedung wajib dipilih",
            lantai: "Lantai wajib dipilih",
            ruangan: "Ruangan wajib dipilih",
            fasilitas: "Fasilitas wajib dipilih",
            foto: {
                required: "Bukti foto wajib diunggah",
                extension: "Format foto tidak valid (harus JPG, JPEG, atau PNG)",
                filesize: "Ukuran maksimal 2MB"
            },
            deskripsi: {
                required: "Deskripsi wajib diisi",
                minlength: "Deskripsi minimal 10 karakter"
            }
        },
        submitHandler: function(form) {
            let formData = new FormData(form);
            $.ajax({
                url: form.action,
                type: form.method,
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    Swal.close();
                    if (response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        // reload tabel jika ada
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
                error: function() {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Gagal menyimpan data.'
                    });
                }
            });
            return false;
        }
    });

    // Custom method untuk validasi ukuran file
    $.validator.addMethod("filesize", function(value, element, param) {
        return this.optional(element) || (element.files[0].size <= param);
    }, "Ukuran file terlalu besar.");
});
</script>
