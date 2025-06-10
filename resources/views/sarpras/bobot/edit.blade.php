<!-- Isi yang dimuat oleh AJAX ke dalam #myModal -->
<div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 relative border-t border-blue-700">

    <button id="modal-close" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
        <i class="fas fa-times"></i>
    </button>

    <h2 class="text-xl font-semibold mb-2 text-center">Edit Data Bobot Kriteria</h2>
    <div class="w-[220px] h-1 bg-yellow-400 mx-auto mt-1 mb-6 rounded"></div>

    <form id="form-edit-bobot" action="{{ route('sarpras.bobot.update') }}" method="POST" class="grid grid-cols-1 gap-4">
        @csrf
        @method('PUT')

        @foreach ($kriteria as $k)
        <div>
            <label class="block text-sm font-medium mb-1">{{ $k->nama_kriteria }}<span class="text-red-500"> *</span></label>
            <input type="number" name="{{ 'bobot_' . $k->id_kriteria }}" id="{{ 'bobot_' . $k->id_kriteria }}" class="w-full border rounded-md px-3 py-2 text-sm" value={{ $k->bobot }} placeholder="Bobot Kriteria" required min="0" max="1" step="0.005">
            <span id="{{ 'bobot_' . $k->id_kriteria }}-error" class="text-xs text-red-500 mt-1 error-text"></span>
        </div>
        @endforeach

        <div class="text-right mt-4">
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md cursor-pointer">
                <div class="flex justify-center items-center gap-[10px]">
                    <img src="{{ asset('icons/light/Check-circle.svg') }}" alt="Simpan" class="w-6 h-6">
                    <p>Simpan</p>
                </div>
            </button>
        </div>
    </form>ccccc
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
<script>
$(document).ready(function() {
    // Tambahkan metode custom untuk validasi total bobot
    $.validator.addMethod("totalBobotSatu", function(value, element) {
        let total = 0;
        for (let i = 1; i <= 6; i++) {
            let val = parseFloat($(`#bobot_${i}`).val());
            total += isNaN(val) ? 0 : val;
        }
        return total.toFixed(3) == 1.000; // Gunakan pembulatan untuk menghindari error desimal
    }, "Jumlah total bobot harus sama dengan 1");

    let rules = {};
    $.each([1, 2, 3, 4, 5, 6], function(i, num) {
        rules['bobot_' + num] = {
            required: true,
            number: true,
            min: 0,
            max: 1
        };
    });

    let messages = {};
    $.each([1, 2, 3, 4, 5, 6], function(i, num) {
        messages['bobot_' + num] = {
            required: "Bobot " + num + " harus diisi",
            number: "Bobot harus berupa angka desimal",
            min: "Minimal bernilai 0",
            max: "Maksimal bernilai 1",
        };
    });

    rules['bobot_6'].totalBobotSatu = true;

    $("#form-edit-bobot").validate({
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
        rules: rules,
        messages: messages,
    });
});
</script>