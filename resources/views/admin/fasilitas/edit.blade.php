<!-- Isi yang dimuat oleh AJAX ke dalam #myModal -->
<div
    class="bg-white rounded-lg shadow-lg max-w-3xl w-full p-6 relative border-t border-blue-700 max-h-[85%] overflow-y-auto">

    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 cursor-pointer">
        <i class="fas fa-times"></i>
    </button>

    <h2 class="text-xl font-semibold mb-2 text-center">Edit Fasilitas</h2>
    <div class="w-[120px] h-1 bg-yellow-400 mx-auto mt-1 mb-6 rounded"></div>

    <form id="form-edit-fasilitas" action="{{ route('admin.fasilitas.update', $fasilitas->id_fasilitas) }}" method="POST"
        class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1">Gedung <span class="text-red-500">*</span></label>
            <select required name="gedung" id="gedung"
                class="cursor-pointer w-full border rounded-md px-3 py-2 text-sm">
                <option value="">- Pilih Gedung -</option>
                @foreach ($gedung as $g)
                    <option value="{{ $g->id_gedung }}"
                        {{ $fasilitas->ruangan->lantai->gedung->id_gedung == $g->id_gedung ? 'selected' : '' }}>
                        {{ $g->nama_gedung }}</option>
                @endforeach
            </select>
            <span id="gedung-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Lantai <span class="text-red-500">*</span></label>
            <select required name="lantai" id="lantai"
                class="w-full border rounded-md px-3 py-2 text-sm bg-gray-100 cursor-not-allowed" disabled>
                <option value="">- Pilih Lantai -</option>
            </select>
            <span id="lantai-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Ruangan <span class="text-red-500">*</span></label>
            <select required name="ruangan" id="ruangan"
                class="w-full border rounded-md px-3 py-2 text-sm bg-gray-100 cursor-not-allowed" disabled>
                <option value="">- Pilih Ruangan -</option>
            </select>
            <span id="ruangan-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Kategori Fasilitas <span class="text-red-500">*</span></label>
            <select required name="kategori" id="kategori"
                class="cursor-pointer w-full border rounded-md px-3 py-2 text-sm">
                <option value="">- Kategori Fasilitas -</option>
                @foreach ($kategori as $k)
                    <option value="{{ $k->id_kategori }}"
                        {{ $fasilitas->id_kategori == $k->id_kategori ? 'selected' : '' }}>{{ $k->nama_kategori }}
                    </option>
                @endforeach
            </select>
            <span id="kategori-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div class="col-span-2">
            <label class="block text-sm font-medium mb-1">Nama Fasilitas <span class="text-red-500">*</span></label>
            <input type="text" name="nama_fasilitas" id="nama_fasilitas"
                class="w-full border rounded-md px-3 py-2 text-sm" value="{{ $fasilitas->nama_fasilitas }}"
                placeholder="Nama" required>
            <span id="nama_fasilitas-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Kondisi Fasilitas <span class="text-red-500">*</span></label>
            <select required name="kondisi" id="kondisi"
                class="cursor-pointer w-full border rounded-md px-3 py-2 text-sm">
                <option value="">- Kondisi Fasilitas -</option>
                <option value="LAYAK" {{ $fasilitas->kondisi->value == 'LAYAK' ? 'selected' : '' }}>Layak</option>
                <option value="RUSAK" {{ $fasilitas->kondisi->value == 'RUSAK' ? 'selected' : '' }}>Rusak</option>
            </select>
            <span id="kondisi-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Urgensi Fasilitas <span class="text-red-500">*</span></label>
            <select required name="urgensi" id="urgensi"
                class="cursor-pointer w-full border rounded-md px-3 py-2 text-sm">
                <option value="">- Urgensi Fasilitas -</option>
                <option value="DARURAT" {{ $fasilitas->urgensi->value == 'DARURAT' ? 'selected' : '' }}>Darurat
                </option>
                <option value="PENTING" {{ $fasilitas->urgensi->value == 'PENTING' ? 'selected' : '' }}>Penting
                </option>
                <option value="BIASA" {{ $fasilitas->urgensi->value == 'BIASA' ? 'selected' : '' }}>Biasa</option>
            </select>
            <span id="urgensi-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div class="col-span-2">
            <label class="block text-sm font-medium mb-1">Deskripsi Fasilitas <span
                    class="text-red-500">*</span></label>
            <textarea name="deskripsi" id="deskripsi" rows="4" class="w-full border rounded-md px-3 py-2 text-sm resize-y"
                placeholder="Masukkan deskripsi fasilitas" required>{{ $fasilitas->deskripsi }}</textarea>
            <span id="deskripsi-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div class="col-span-2">
            <label for="foto_fasilitas" class="block text-sm font-medium mb-1">Foto Fasilitas <span
                    class="text-red-500">*</span></label>
            <div class="flex items-center border border-gray-300 rounded-md bg-white overflow-hidden">
                <input type="text" id="file-name-display"
                    placeholder="{{ $fasilitas->foto_fasilitas ? basename($fasilitas->foto_fasilitas) : 'Pilih File' }}"
                    class="flex-grow px-3 py-2 text-sm {{ $fasilitas->foto_fasilitas ? 'text-black' : 'text-gray-500' }} bg-gray-50 border-none focus:ring-0 focus:outline-none"
                    readonly>
                <label for="foto_fasilitas"
                    class="font-semibold px-4 py-2 text-sm text-black bg-gray-300 hover:bg-gray-400 cursor-pointer">Browse</label>
                <input type="file" id="foto_fasilitas" name="foto_fasilitas" accept=".jpg,.jpeg,.png"
                    class="hidden"
                    onchange="const input = document.getElementById('file-name-display'); input.value = this.files[0]?.name || '{{ $fasilitas->foto_fasilitas ? basename($fasilitas->foto_fasilitas) : 'Pilih File'}}'; input.classList.remove('text-gray-500'); input.classList.add('text-black');">
            </div>
            <p class="mt-1 text-xs text-gray-500">Format yang didukung: JPG, PNG, JPEG. Ukuran maksimal: 2MB</p>
            <span id="foto_fasilitas-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div class="col-span-2 text-right mt-4">
            <button type="submit"
                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md cursor-pointer">
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
        // Inisialisasi dropdown lantai dan ruangan berdasarkan gedung yang sudah dipilih
        let gedungID = $('#gedung').val();
        if (gedungID) {
            $.get('/admin/fasilitas/get-lantai/' + gedungID, function(data) {
                $('#lantai').html('<option value="">- Pilih Lantai -</option>');
                $.each(data, function(key, val) {
                    $('#lantai').append(
                        `<option value="${val.id_lantai}" ${val.id_lantai == '{{ $fasilitas->ruangan->lantai->id_lantai }}' ? 'selected' : ''}>${val.nama_lantai}</option>`
                    );
                });
                $('#lantai').prop('disabled', false)
                    .removeClass('cursor-not-allowed bg-gray-100')
                    .addClass('cursor-pointer');

                // Inisialisasi dropdown ruangan berdasarkan lantai yang sudah dipilih
                let lantaiID = $('#lantai').val();
                if (lantaiID) {
                    $.get('/admin/fasilitas/get-ruangan/' + lantaiID, function(data) {
                        $('#ruangan').html('<option value="">- Pilih Ruangan -</option>');
                        $.each(data, function(key, val) {
                            $('#ruangan').append(
                                `<option value="${val.id_ruangan}" ${val.id_ruangan == '{{ $fasilitas->id_ruangan }}' ? 'selected' : ''}>${val.nama_ruangan}</option>`
                            );
                        });
                        $('#ruangan').prop('disabled', false)
                            .removeClass('cursor-not-allowed bg-gray-100')
                            .addClass('cursor-pointer');
                    });
                }
            });
        }

        $('#gedung').on('change', function() {
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
                $.get('/admin/fasilitas/get-lantai/' + gedungID, function(data) {
                    $('#lantai').html('<option value="">- Pilih Lantai -</option>');
                    $.each(data, function(key, val) {
                        $('#lantai').append(
                            `<option value="${val.id_lantai}" ${val.id_lantai == '{{ $fasilitas->ruangan->lantai->id_lantai }}' ? 'selected' : ''}>${val.nama_lantai}</option>`
                        );
                    });

                    // Enable lantai + style
                    $('#lantai').prop('disabled', false)
                        .removeClass('cursor-not-allowed bg-gray-100')
                        .addClass('cursor-pointer');
                });
            }
        });

        $('#lantai').on('change', function() {
            let lantaiID = $(this).val();

            $('#ruangan').html('<option value="">- Pilih Ruangan -</option>')
                .prop('disabled', true)
                .removeClass('cursor-pointer')
                .addClass('cursor-not-allowed bg-gray-100');

            if (lantaiID) {
                $.get('/admin/fasilitas/get-ruangan/' + lantaiID, function(data) {
                    $('#ruangan').html('<option value="">- Pilih Ruangan -</option>');
                    $.each(data, function(key, val) {
                        $('#ruangan').append(
                            `<option value="${val.id_ruangan}" ${val.id_ruangan == '{{ $fasilitas->id_ruangan }}' ? 'selected' : ''}>${val.nama_ruangan}</option>`
                        );
                    });

                    // Enable ruangan + style
                    $('#ruangan').prop('disabled', false)
                        .removeClass('cursor-not-allowed bg-gray-100')
                        .addClass('cursor-pointer');
                });
            }
        });

        $("#form-edit-fasilitas").validate({
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
                kondisi: "required",
                urgensi: "required",
                deskripsi: {
                    required: true,
                    minlength: 10,
                    maxlength: 255,
                },
                foto_fasilitas: {
                    required: false, // Foto tidak wajib saat edit
                    extension: "jpg|jpeg|png",
                    filesize: 2
                }
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
                kondisi: "Kondisi fasilitas wajib dipilih",
                urgensi: "Urgensi fasilitas wajib dipilih",
                deskripsi: {
                    required: "Deskripsi fasilitas wajib diisi",
                    minlength: "Deskripsi minimal 10 karakter",
                    maxlength: "Deskripsi maksimal 255 karakter"
                },
                foto_fasilitas: {
                    extension: "Format file harus JPG, PNG, atau JPEG",
                    filesize: "Ukuran file maksimal 2MB"
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
                            $('#myModal').addClass('hidden').removeClass('flex').html(
                                '');
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
                            text: xhr.responseJSON?.message ||
                                'Gagal menyimpan data'
                        });
                        $('#myModal').addClass('hidden').removeClass('flex').html('');
                    }
                });
                return false;
            }
        });
    });
</script>
