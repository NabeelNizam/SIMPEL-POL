<!-- Modal Konten Detail -->
<div class="bg-white rounded-lg shadow-lg max-w-3xl w-full p-6 relative max-h-[80vh] overflow-y-auto">

    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl cursor-pointer">
        <i class="fas fa-times"></i>
    </button>

    <h2 class="text-xl font-semibold text-center">Beri Umpan Balik Hasil Perbaikan</h2>
    <div class="w-[305px] h-1 bg-yellow-400 mx-auto mt-1 mb-6 rounded"></div>

    <!-- Isi pengaduan -->
    <div class="mb-6">
        <div class="flex items-center mb-4">
            <div class="bg-blue-500 text-white p-2 rounded-md mr-3 w-8 h-8 flex justify-center items-center">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Isi Pengaduan</h3>
        </div>
        <div class="w-[160px] h-0.5 bg-orange-400 mb-4"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Gambar -->
            <div class="flex justify-center">
                <div class="bg-gray-100 p-4 rounded-lg shadow-sm">
                    @if(!empty($aduan->bukti_foto) && file_exists(public_path($aduan->bukti_foto)))
                        <img src="{{ asset( $aduan->bukti_foto) }}" alt="Foto Aduan"
                            class="w-48 h-32 object-cover rounded-lg shadow">
                    @else
                        <img src="{{ asset('img/no-image.svg') }}" alt="No Image"
                            class="w-48 h-32 object-cover rounded-lg shadow">
                    @endif
                    <div class="mt-3 text-center">
                        <h4 class="font-semibold text-gray-800">{{ $aduan->fasilitas->nama_fasilitas ?? '-' }}</h4>
                        <p class="text-sm text-gray-600">{{ $aduan->fasilitas->kategori->nama_kategori ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <!-- Detail Aduan -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Lokasi</label>
                    @php
                        $ruangan = $aduan->fasilitas->ruangan;
                        $lantai = $ruangan->lantai;
                        $gedung = $lantai->gedung;
                    @endphp
                    <p class="text-gray-800 font-semibold">
                        {{ $gedung->nama_gedung ?? '-' }}{{ $lantai ? ', Lt. ' . $lantai->nama_lantai : '' }}{{ $ruangan ? ', ' . $ruangan->nama_ruangan : '' }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Deskripsi Kerusakan</label>
                    <p class="text-gray-700 text-sm leading-relaxed">{{ $aduan->deskripsi ?? '-' }}</p>
                </div>

                <div class="flex items-center gap-16">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Lapor</label>
                        <p class="text-gray-800 font-semibold">
                            {{ $aduan->tanggal_aduan ? \Carbon\Carbon::parse($aduan->tanggal_aduan)->format('d/m/Y') : '-' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Perbaikan</label>
                        <p class="text-gray-800 font-semibold">
                            {{ $aduan->perbaikan && $aduan->perbaikan->tanggal_selesai ? \Carbon\Carbon::parse($aduan->perbaikan->tanggal_selesai)->format('d/m/Y') : '-' }}
                        </p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Status</label>
                    <span class="px-3 py-1 rounded-full text-white text-sm
                        @if($aduan->status === \App\Http\Enums\Status::SELESAI)
                            bg-green-500
                        @elseif($aduan->status === \App\Http\Enums\Status::MENUNGGU_DIPROSES)
                            bg-blue-500
                        @elseif($aduan->status === \App\Http\Enums\Status::SEDANG_INSPEKSI)
                            bg-yellow-500
                        @elseif($aduan->status === \App\Http\Enums\Status::SEDANG_DIPERBAIKI)
                            bg-orange-500
                        @else
                            bg-gray-500
                        @endif">
                        {{ $aduan->status->value }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Umpan Balik -->
    <div class="mb-6">
        <div class="flex items-center mb-4">
            <div class="bg-blue-500 text-white p-2 rounded-md mr-3 w-8 h-8 flex justify-center items-center">
                <i class="fas fa-comment-dots"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Umpan Balik Pelapor</h3>
        </div>
        <div class="w-[220px] h-0.5 bg-orange-400 mb-4"></div>

        <form id="form-tambah-ulasan" action="{{ route('mahasiswa.riwayat.store_ulasan', $aduan) }}" method="POST" class="grid grid-cols-1 gap-4" enctype="multipart/form-data">
            @csrf
            <div class="w-[50%]">
                <label class="block text-sm font-medium mb-2">Rating <span class="text-red-500">*</span></label>
                <div id="star-container" class="flex items-center gap-[5px] text-xl text-gray-300 cursor-pointer">
                    <i class="fas fa-star" data-value="1"></i>
                    <i class="fas fa-star" data-value="2"></i>
                    <i class="fas fa-star" data-value="3"></i>
                    <i class="fas fa-star" data-value="4"></i>
                    <i class="fas fa-star" data-value="5"></i>
                </div>
                <p id="rating-text" class="text-sm mt-2 text-gray-600">0/5</p>

                <input type="hidden" name="rating" id="rating" required value="0">
                <span id="rating-error" class="text-xs text-red-500 mt-1 error-text"></span>
            </div>

            <div class="w-[93%]">
                <label class="block text-sm font-medium mb-1">
                    Ulasan <span class="text-red-500">*</span>
                </label>
                <textarea name="komentar" id="komentar" rows="4" class="w-full border rounded-md px-3 py-2 text-sm resize-y" placeholder="Deskripsi" required></textarea>
                <span id="komentar-error" class="text-xs text-red-500 mt-1 error-text"></span>
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
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>

<script>
$(document).ready(function() {
    // Highlight bintang
    $('#star-container i').on('click', function () {
        let rating = $(this).data('value');
        $('#rating').val(rating).trigger('change'); // Trigger agar validator detect perubahan

        $('#star-container i').each(function (index) {
            $(this).toggleClass('text-yellow-400', index < rating)
                   .toggleClass('text-gray-300', index >= rating);
        });

        $('#rating-text').text(rating + '/5');
    });

    // Validasi form
    $("#form-tambah-ulasan").validate({
        errorElement: 'span',
        errorClass: 'text-xs text-red-500 mt-1 error-text',
        ignore: [], // Penting: agar input:hidden ikut divalidasi
        rules: {
            rating: {
                required: true,
                digits: true,
                min: 1,
                max: 5
            },
            komentar: {
                required: true,
                minlength: 10,
                maxlength: 255
            }
        },
        messages: {
            rating: {
                required: "Rating wajib diisi",
                digits: "Rating harus berupa angka",
                min: "Rating minimal 1",
                max: "Rating maksimal 5"
            },
            komentar: {
                required: "Ulasan wajib diisi",
                minlength: "Ulasan minimal 10 karakter",
                maxlength: "Ulasan maksimal 255 karakter"
            }
        },
        errorPlacement: function(error, element) {
            const container = element.attr('id') === 'rating'
                ? $('#rating-error')
                : element.next('.error-text');
            container.html(error);
        }
    });
});
</script>
