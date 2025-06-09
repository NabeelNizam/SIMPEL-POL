{{ $autofill_test = env('FORM_AUTOFILL', false) }}

<!-- Isi yang dimuat oleh AJAX ke dalam #myModal -->
<div class="bg-white rounded-lg shadow-lg max-w-3xl w-full p-6 relative border-t border-blue-700">

    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 cursor-pointer">
        <i class="fas fa-times"></i>
    </button>

    <h2 class="text-xl font-semibold mb-2 text-center">Edit Aduan</h2>
    <div class="w-[115px] h-1 bg-yellow-400 mx-auto mt-1 mb-6 rounded"></div>

    <form id="form-edit-aduan" action="{{ route('mahasiswa.form.update', $aduan) }}" method="POST"
        class="grid grid-cols-1 md:grid-cols-2 gap-4" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1">Gedung <span class="text-red-500">*</span></label>
            <select required name="gedung" id="gedung"
                class="cursor-pointer w-full border rounded-md px-3 py-2 text-sm">
                <option value="">- Pilih Gedung -</option>
                @foreach ($gedung as $g)
                    <option value="{{ $g->id_gedung }}"
                        {{ $g->id_gedung == ($aduan->fasilitas->ruangan->lantai->gedung->id_gedung ?? '') ? 'selected' : '' }}>
                        {{ $g->nama_gedung }}
                    </option>
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

        <div class="col-span-2">
            <label class="block text-sm font-medium mb-1">Fasilitas <span class="text-red-500">*</span></label>
            <select required name="fasilitas" id="fasilitas"
                class="w-full border rounded-md px-3 py-2 text-sm bg-gray-100 cursor-not-allowed" disabled>
                <option value="">- Pilih Fasilitas -</option>
            </select>
            <span id="fasilitas-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div class="col-span-2">
            <label for="bukti_foto" class="block text-sm font-medium mb-1">
                Bukti Foto
            </label>
            <div class="flex items-center border border-gray-300 rounded-md bg-white overflow-hidden">
                <input type="text" id="file-name-display" placeholder="Pilih File"
                    class="flex-grow px-3 py-2 text-sm text-gray-500 bg-gray-50 border-none focus:ring-0 focus:outline-none"
                    value="{{ $aduan->bukti_foto ? basename($aduan->bukti_foto) : '' }}" readonly>
                <label for="bukti_foto"
                    class="font-semibold px-4 py-2 text-sm text-black bg-gray-300 hover:bg-gray-400 cursor-pointer">
                    Browse</label>
                <input type="file" id="bukti_foto" name="bukti_foto" accept=".jpg,.jpeg,.png" class="hidden"
                    onchange="const input = document.getElementById('file-name-display'); input.value = this.files[0]?.name; input.classList.remove('text-gray-500'); input.classList.add('text-black');">
            </div>
            <p class="mt-1 text-xs text-gray-500">
                Format yang didukung: JPG, PNG, JPEG. Ukuran maksimal: 2MB
                @if ($aduan->bukti_foto)
                    | File saat ini: <a href="{{ asset('storage/' . $aduan->bukti_foto) }}" target="_blank">Lihat</a>
                @endif
            </p>
            <span id="bukti_foto-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>

        <div class="col-span-2">
            <label class="block text-sm font-medium mb-1">
                Deskripsi <span class="text-red-500">*</span>
            </label>
            <textarea name="deskripsi" id="deskripsi" rows="4" class="w-full border rounded-md px-3 py-2 text-sm resize-y"
                placeholder="Masukkan deskripsi fasilitas" required>{{ old('deskripsi', $aduan->deskripsi) }}</textarea>
            <span id="deskripsi-error" class="text-xs text-red-500 mt-1 error-text"></span>
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
        // Inisialisasi dropdown lantai, ruangan, dan fasilitas berdasarkan gedung yang sudah dipilih
        let gedungID = $('#gedung').val();
        if (gedungID) {
            $.get('/pelapor/form/get-lantai/' + gedungID, function(data) {
                // console.log('Data lantai:', data); // Debug
                $('#lantai').html('<option value="">- Pilih Lantai -</option>');
                if (data.length === 0) {
                    $('#lantai').append('<option value="">Tidak ada lantai tersedia</option>');
                } else {
                    $.each(data, function(key, val) {
                        $('#lantai').append(
                            `<option value="${val.id_lantai}" ${val.id_lantai == '{{ $aduan->fasilitas->ruangan->lantai->id_lantai ?? '' }}' ? 'selected' : ''}>${val.nama_lantai}</option>`
                        );
                    });
                    $('#lantai').prop('disabled', false)
                        .removeClass('cursor-not-allowed bg-gray-100')
                        .addClass('cursor-pointer');
                }

                // Inisialisasi dropdown ruangan berdasarkan lantai yang sudah dipilih
                let lantaiID = $('#lantai').val();
                if (lantaiID) {
                    $.get('/pelapor/form/get-ruangan/' + lantaiID, function(data) {
                        // console.log('Data ruangan:', data); // Debug
                        $('#ruangan').html('<option value="">- Pilih Ruangan -</option>');
                        if (data.length === 0) {
                            $('#ruangan').append('<option value="">Tidak ada ruangan tersedia</option>');
                        } else {
                            $.each(data, function(key, val) {
                                $('#ruangan').append(
                                    `<option value="${val.id_ruangan}" ${val.id_ruangan == '{{ $aduan->fasilitas->id_ruangan ?? '' }}' ? 'selected' : ''}>${val.nama_ruangan}</option>`
                                );
                            });
                            $('#ruangan').prop('disabled', false)
                                .removeClass('cursor-not-allowed bg-gray-100')
                                .addClass('cursor-pointer');
                        }

                        // Inisialisasi dropdown fasilitas berdasarkan ruangan yang sudah dipilih
                        let ruanganID = $('#ruangan').val();
                        if (ruanganID) {
                            $.get('/pelapor/form/get-fasilitas/' + ruanganID, function(data) {
                                // console.log('Data fasilitas:', data); // Debug
                                $('#fasilitas').html('<option value="">- Pilih Fasilitas -</option>');
                                if (data.length === 0) {
                                    $('#fasilitas').append('<option value="">Tidak ada fasilitas tersedia</option>');
                                } else {
                                    $.each(data, function(key, val) {
                                        $('#fasilitas').append(
                                            `<option value="${val.id_fasilitas}" ${val.id_fasilitas == '{{ $aduan->fasilitas->id_fasilitas ?? '' }}' ? 'selected' : ''}>${val.nama_fasilitas}</option>`
                                        );
                                    });
                                    $('#fasilitas').prop('disabled', false)
                                        .removeClass('cursor-not-allowed bg-gray-100')
                                        .addClass('cursor-pointer');
                                }
                            }).fail(function(jqXHR, textStatus, errorThrown) {
                                console.error('Error loading fasilitas:', textStatus, errorThrown);
                                alert('Gagal memuat data fasilitas. Silakan coba lagi.');
                            });
                        }
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        console.error('Error loading ruangan:', textStatus, errorThrown);
                        alert('Gagal memuat data ruangan. Silakan coba lagi.');
                    });
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.error('Error loading lantai:', textStatus, errorThrown);
                alert('Gagal memuat data lantai. Silakan coba lagi.');
            });
        }

        // Handle perubahan gedung
        $('#gedung').on('change', function() {
            let gedungID = $(this).val();

            // Reset dan disable lantai, ruangan, dan fasilitas
            $('#lantai').html('<option value="">- Pilih Lantai -</option>')
                .prop('disabled', true)
                .removeClass('cursor-pointer')
                .addClass('cursor-not-allowed bg-gray-100');
            $('#ruangan').html('<option value="">- Pilih Ruangan -</option>')
                .prop('disabled', true)
                .removeClass('cursor-pointer')
                .addClass('cursor-not-allowed bg-gray-100');
            $('#fasilitas').html('<option value="">- Pilih Fasilitas -</option>')
                .prop('disabled', true)
                .removeClass('cursor-pointer')
                .addClass('cursor-not-allowed bg-gray-100');

            if (gedungID) {
                $.get('/pelapor/form/get-lantai/' + gedungID, function(data) {
                    // console.log('Data lantai:', data); // Debug
                    $('#lantai').html('<option value="">- Pilih Lantai -</option>');
                    if (data.length === 0) {
                        $('#lantai').append('<option value="">Tidak ada lantai tersedia</option>');
                    } else {
                        $.each(data, function(key, val) {
                            $('#lantai').append(
                                `<option value="${val.id_lantai}">${val.nama_lantai}</option>`
                            );
                        });
                        $('#lantai').prop('disabled', false)
                            .removeClass('cursor-not-allowed bg-gray-100')
                            .addClass('cursor-pointer');
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error('Error loading lantai:', textStatus, errorThrown);
                    alert('Gagal memuat data lantai. Silakan coba lagi.');
                });
            }
        });

        // Handle perubahan lantai
        $('#lantai').on('change', function() {
            let lantaiID = $(this).val();

            // Reset dan disable ruangan dan fasilitas
            $('#ruangan').html('<option value="">- Pilih Ruangan -</option>')
                .prop('disabled', true)
                .removeClass('cursor-pointer')
                .addClass('cursor-not-allowed bg-gray-100');
            $('#fasilitas').html('<option value="">- Pilih Fasilitas -</option>')
                .prop('disabled', true)
                .removeClass('cursor-pointer')
                .addClass('cursor-not-allowed bg-gray-100');

            if (lantaiID) {
                $.get('/pelapor/form/get-ruangan/' + lantaiID, function(data) {
                    // console.log('Data ruangan:', data); // Debug
                    $('#ruangan').html('<option value="">- Pilih Ruangan -</option>');
                    if (data.length === 0) {
                        $('#ruangan').append('<option value="">Tidak ada ruangan tersedia</option>');
                    } else {
                        $.each(data, function(key, val) {
                            $('#ruangan').append(
                                `<option value="${val.id_ruangan}">${val.nama_ruangan}</option>`
                            );
                        });
                        $('#ruangan').prop('disabled', false)
                            .removeClass('cursor-not-allowed bg-gray-100')
                            .addClass('cursor-pointer');
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error('Error loading ruangan:', textStatus, errorThrown);
                    alert('Gagal memuat data ruangan. Silakan coba lagi.');
                });
            }
        });

        // Handle perubahan ruangan
        $('#ruangan').on('change', function() {
            let ruanganID = $(this).val();

            // Reset dan disable fasilitas
            $('#fasilitas').html('<option value="">- Pilih Fasilitas -</option>')
                .prop('disabled', true)
                .removeClass('cursor-pointer')
                .addClass('cursor-not-allowed bg-gray-100');

            if (ruanganID) {
                $.get('/pelapor/form/get-fasilitas/' + ruanganID, function(data) {
                    // console.log('Data fasilitas:', data); // Debug
                    $('#fasilitas').html('<option value="">- Pilih Fasilitas -</option>');
                    if (data.length === 0) {
                        $('#fasilitas').append('<option value="">Tidak ada fasilitas tersedia</option>');
                    } else {
                        $.each(data, function(key, val) {
                            $('#fasilitas').append(
                                `<option value="${val.id_fasilitas}">${val.nama_fasilitas}</option>`
                            );
                        });
                        $('#fasilitas').prop('disabled', false)
                            .removeClass('cursor-not-allowed bg-gray-100')
                            .addClass('cursor-pointer');
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error('Error loading fasilitas:', textStatus, errorThrown);
                    alert('Gagal memuat data fasilitas. Silakan coba lagi.');
                });
            }
        });

        // Form validation
        $("#form-edit-aduan").validate({
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
                fasilitas: {
                    required: true,
                    digits: true
                },
                deskripsi: {
                    required: true,
                    minlength: 10,
                    maxlength: 255
                },
                bukti_foto: {
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
                fasilitas: {
                    required: "Fasilitas wajib dipilih",
                    digits: "Fasilitas tidak valid"
                },
                deskripsi: {
                    required: "Deskripsi fasilitas wajib diisi",
                    minlength: "Deskripsi minimal 10 karakter",
                    maxlength: "Deskripsi maksimal 255 karakter"
                },
                bukti_foto: {
                    extension: "Format file harus JPG, PNG, atau JPEG",
                    filesize: "Ukuran file maksimal 2MB"
                }
            }
        });
    });
</script>