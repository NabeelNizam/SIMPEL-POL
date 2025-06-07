<!-- Modal Konten Penugasan Teknisi untuk Menginspeksi -->
<div
    class="bg-white rounded-lg shadow-lg max-w-3xl w-full p-6 relative max-h-[80vh] overflow-y-auto border-t-4 border-blue-600">

    <div class="bg-white sticky mt-0">

        <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl">
            <i class="fas fa-times"></i>
        </button>

        <h2 class="text-xl font-semibold text-center">Penugasan Teknisi</h2>
        <div class="w-48 h-1 bg-orange-400 mx-auto mt-1 mb-6 rounded"></div>
    </div>

    <!-- Isi Pengaduan -->
    <div class="mb-6">
        <div class="flex items-center mb-2">
            <div class="bg-blue-500 text-white px-3 py-2 rounded-md mr-3">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Detail Fasilitas</h3>
        </div>
        <div class="w-24 h-1 bg-orange-400 ms-12 mb-4 rounded-full"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Gambar -->
            <div class="flex justify-center">
                <div class="bg-gray-100 p-4 rounded-lg shadow-sm">
                    @if(!empty($fasilitas->foto_fasilitas) && file_exists(public_path($fasilitas->foto_fasilitas)))
                        <img src="{{ asset($fasilitas->foto_fasilitas) }}" alt="Foto Fasilitas"
                            class="w-48 h-32 object-cover rounded-lg shadow">
                    @else
                        <img src="{{ asset('img/no-image.svg') }}" alt="No Image"
                            class="w-48 h-32 object-cover rounded-lg shadow">
                    @endif

                    <div class="mt-3 text-center">
                        <h4 class="font-semibold text-gray-800">{{ $fasilitas->nama_fasilitas ?? '-' }}</h4>
                        <p class="text-sm text-gray-600">{{ $fasilitas->kategori->nama_kategori ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <!-- Detail Aduan -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Lokasi</label>
                    @php
                        $ruangan = $fasilitas->ruangan;
                        $lantai = $ruangan->lantai;
                        $gedung = $lantai->gedung;
                    @endphp
                    <p class="text-gray-800 font-semibold">
                        {{ $gedung->nama_gedung ?? '-' }}{{ $lantai ? ', ' . $lantai->nama_lantai : '' }}{{ $ruangan ? ', ' . $ruangan->nama_ruangan : '' }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Urgensi</label>
                    <p class="text-gray-800 font-semibold">
                        @if($fasilitas->urgensi)
                            <span class="px-3 py-1 rounded-full text-white text-sm
                                                                        @if($fasilitas->urgensi === \App\Http\Enums\Urgensi::DARURAT)
                                                                            bg-red-500
                                                                        @elseif($fasilitas->urgensi === \App\Http\Enums\Urgensi::PENTING)
                                                                            bg-yellow-500
                                                                        @elseif($fasilitas->urgensi === \App\Http\Enums\Urgensi::BIASA)
                                                                            bg-blue-500
                                                                        @else
                                                                            bg-gray-500
                                                                        @endif
                                                                    ">
                                {{ $fasilitas->urgensi->value }}
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-gray-500 text-white text-sm">-</span>
                        @endif
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Jumlah Pelapor</label>
                    <p class="font-semibold text-sm leading-relaxed">{{ $fasilitas->aduan_count ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <hr class="border-gray-300 my-6">

    <!-- Laporan dari Pelapor -->
    <div class="mb-6">
        <div class="flex items-center mb-2">
            <div class="bg-blue-500 text-white p-2 rounded-md mr-3">
                <i class="fas fa-user-cog"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Penugasan Inspeksi</h3>
        </div>
        <div class="w-24 h-1 bg-orange-400 ms-12 mb-4 rounded-full"></div>
        <form id="form-edit-pengaduan" action="{{ route('sarpras.pengaduan.update', $fasilitas->id_fasilitas) }}"
            method="POST">
            @csrf
            @method('PUT')
            <div class="max-w-lg mx-4 py-4">
                <p class="mb-3">Teknisi<i class="text-red-500">*</i></p>
                <select type="select" name="id_teknisi" id="id_teknisi"
                    class="w-full border rounded-md px-3 py-2 text-sm">
                    <option value="" class="text-gray-400">- Pilih Teknisi -</option>
                    @foreach ($teknisi as $t)
                        <option value="{{ $t->id_user }}">{{ $t->nama }}</option>
                    @endforeach
                </select>
                <span id="id_teknisi-error" class="text-xs text-red-500 mt-1 error-text"></span>
                @csrf
            </div>
            <div class="text-right mt-4">
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
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
<script>
    $(document).ready(function () {
        $("#form-edit-pengaduan").validate({
            errorElement: 'span',
            errorClass: 'text-xs text-red-500 mt-1 error-text',
            highlight: function (element) { },
            unhighlight: function (element) { },
            errorPlacement: function (error, element) {
                var errorContainer = element.next('.error-text');
                if (errorContainer.length) {
                    errorContainer.replaceWith(error);
                } else {
                    error.insertAfter(element);
                }
            },
            rules: {
                id_teknisi: {
                    required: true,
                },
            }
        messages: {
                id_teknisi: {
                    required: "Tolong pilih teknisi",
                },
            }
        });
    });
</script>