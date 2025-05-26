<div class="p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-center w-full">Tambah Data Pengguna</h2>
    </div>

    <div class="h-1 w-32 bg-orange-400 rounded-full mx-auto mb-6"></div>

    <form id="form-tambah-pengguna" action="{{ route('admin.store_ajax') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">Nama <span class="text-red-500">*</span></label>
            <input type="text" name="nama" id="nama" class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Nama">
            <span id="nama-error" class="text-xs text-red-500 mt-1"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">No. Telepon <span class="text-red-500">*</span></label>
            <input type="text" name="telepon" id="telepon" class="w-full border rounded-md px-3 py-2 text-sm" placeholder="No. Telepon">
            <span id="telepon-error" class="text-xs text-red-500 mt-1"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" id="email" class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Email">
            <span id="email-error" class="text-xs text-red-500 mt-1"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Username <span class="text-red-500">*</span></label>
            <input type="text" name="username" id="username" class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Username">
            <span id="username-error" class="text-xs text-red-500 mt-1"></span>
        </div>

        <div>
        <label class="block text-sm font-medium mb-1">Jurusan <span class="text-red-500">*</span></label>
        <select name="jurusan" id="jurusan" class="w-full border rounded-md px-3 py-2 text-sm">
            <option value="">- Pilih Jurusan -</option>
            @foreach($jurusan as $j)
                <option value="{{ $j->id_jurusan }}">{{ $j->nama_jurusan }}</option>
            @endforeach
        </select>
        <span id="jurusan-error" class="text-xs text-red-500 mt-1"></span>
    </div>



        <div>
            <label class="block text-sm font-medium mb-1">Role <span class="text-red-500">*</span></label>
            <select name="id_role" id="id_role" class="w-full border rounded-md px-3 py-2 text-sm">
                <option value="">- Pilih role -</option>
                @foreach($role as $r)
                    <option value="{{ $r->id_role }}">{{ $r->nama_role }}</option>
                @endforeach
            </select>
            <span id="id_role-error" class="text-xs text-red-500 mt-1"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Password <span class="text-red-500">*</span></label>
            <input type="password" name="password" id="password" class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Password">
            <span id="password-error" class="text-xs text-red-500 mt-1"></span>
        </div>

        <div class="col-span-1 md:col-span-2 flex justify-end mt-4">
            <button type="button" onclick="closeModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm mr-2">
                Batal
            </button>
            <button type="submit" id="btn-simpan" class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-md text-sm">
            Simpan
            </button>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    // Reset semua error message saat form dimuat
    clearErrorMessages();
    
    // Submit form menggunakan AJAX
    $("#btn-simpan").click(function() {
        // Reset pesan error sebelum validasi baru
        clearErrorMessages();
        
        // Tampilkan loading state
        let btnSimpan = $(this);
        btnSimpan.prop('disabled', true);
        btnSimpan.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
        
        $("#form-tambah-pengguna").on("submit", function(event) {
    event.preventDefault(); // Mencegah form refresh halaman

    // Reset pesan error sebelum validasi baru
    clearErrorMessages();

    // Tampilkan loading state
    let btnSimpan = $("#btn-simpan");
    btnSimpan.prop("disabled", true);
    btnSimpan.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

    // Ambil data form
    let formData = $(this).serialize();

    // Kirim data via AJAX
    $.ajax({
        url: "{{ route('admin.store_ajax') }}",
        method: "POST",
        data: formData,
        dataType: "json",
        success: function(response) {
            if (response.success) {
                // Tampilkan notifikasi sukses
                Swal.fire({
                    title: "Berhasil!",
                    text: response.message || "Data pengguna berhasil ditambahkan",
                    icon: "success",
                    confirmButtonText: "OK"
                }).then((result) => {
                    // Reload DataTable
                    if (typeof dataUser !== "undefined") {
                        dataUser.ajax.reload();
                    }

                    // Tutup modal
                    closeModal();
                });
            } else {
                // Tampilkan pesan error umum
                Swal.fire({
                    title: "Gagal!",
                    text: response.message || "Terjadi kesalahan saat menyimpan data",
                    icon: "error",
                    confirmButtonText: "OK"
                });
            }
        },
        error: function(xhr) {
            // Handle error response
            if (xhr.status === 422) {
                // Validation errors
                let errors = xhr.responseJSON.errors;
                displayValidationErrors(errors);

                Swal.fire({
                    title: "Validasi Gagal!",
                    text: "Periksa kembali data yang Anda masukkan",
                    icon: "warning",
                    confirmButtonText: "OK"
                });
            } else {
                Swal.fire({
                    title: "Error!",
                    text: "Terjadi kesalahan pada server",
                    icon: "error",
                    confirmButtonText: "OK"
                });
            }
        },
        complete: function() {
            // Kembalikan tombol ke keadaan semula
            btnSimpan.prop("disabled", false);
            btnSimpan.html('<i class="fas fa-save"></i> Simpan');
        }
    });
});
            complete: function() {
                // Kembalikan tombol ke keadaan semula
                btnSimpan.prop('disabled', false);
                btnSimpan.html('<i class="fas fa-save"></i> Simpan');
            }
        });
    });
    
    // Fungsi untuk menampilkan error validasi
    function displayValidationErrors(errors) {
        $.each(errors, function(field, messages) {
            let errorElement = $("#" + field + "-error");
            errorElement.html(messages[0]);
            $("#" + field).addClass('border-red-500');
        });
    }
    
    // Fungsi untuk membersihkan pesan error
    function clearErrorMessages() {
        $(".text-red-500").html("");
        $("input, select").removeClass('border-red-500');
    }
    
    // Membersihkan error ketika input diubah
    $("input, select").on('input change', function() {
        let fieldId = $(this).attr('id');
        $("#" + fieldId + "-error").html("");
        $(this).removeClass('border-red-500');
    });
});

function closeModal() {
    $('#myModal').modal('hide');
}
</script>