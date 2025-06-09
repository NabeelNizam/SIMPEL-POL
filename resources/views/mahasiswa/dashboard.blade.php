@extends('layouts.template')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="flex">
            <div class="flex-1 p-6">
                {{-- SOP Section --}}
                <div class="bg-white rounded-lg shadow p-2 border-t-4 border-blue-600 mb-8">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                            <img src="{{ asset('icons/light/Book.svg') }}" alt="" class="w-5 h-5 mr-2 text-blue-600">
                            Standar Operasional Prosedur (SOP)
                        </h2>
                    </div>

                    <div class="p-6">
                        {{-- Tabs Navigation --}}
                        <div class="border-b border-gray-200 mb-6">
                            <nav class="-mb-px flex space-x-8">
                                <button id="tab-penjelasan"
                                    class="tab-button active py-2 px-1 border-b-2 border-orange-500 font-medium text-sm text-blue-600 hover:border-gray-300">
                                    Penjelasan
                                </button>
                                <button id="tab-dokumen"
                                    class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:border-gray-300">
                                    Dokumen SOP
                                </button>
                            </nav>
                        </div>

                        {{-- Tab Content --}}
                        <div id="content-penjelasan" class="tab-content">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <img src='{{ asset('icons/light/info.svg') }}' alt="" class="w-6 h-6 text-blue-600">
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800">Tentang SOP Sarana Prasarana</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="prose max-w-none text-gray-700">
                                <p class="mb-4">
                                    SOP Sarana Prasarana adalah panduan prosedur standar untuk pengelolaan, pemeliharaan,
                                    dan penggunaan fasilitas di lingkungan Politeknik Negeri Malang. SOP ini bertujuan untuk
                                    memastikan proses pengelolaan sarana dan prasarana berjalan efektif, efisien, dan sesuai
                                    dengan regulasi yang berlaku.
                                </p>

                                <p class="mb-4">
                                    Seluruh staff dan mahasiswa Politeknik diharapkan mengikuti SOP ini dalam penggunaan dan
                                    pengelolaan sarana prasarana kampus. Untuk mengakses dokumen SOP lengkap, silakan
                                    beralih ke tab "Dokumen SOP".
                                </p>
                            </div>
                        </div>

                        <div id="content-dokumen" class="tab-content hidden">
                            <p class="text-gray-600 mb-4">Berikut adalah daftar dokumen SOP Sarana Prasarana yang dapat
                                diunduh:</p>

                            <div class="space-y-3">
                                {{-- Document Item --}}
                                <div
                                    class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <img src="{{ asset('icons/solid/Doc2.svg') }}" alt="">
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">SOP Perbaikan Fasilitas</div>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('download.sopmhs', ['filename' => 'SOP_PELAPOR.pdf']) }}"
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <img src="{{ asset('icons/light/Download.svg') }}" alt="">
                                            Unduh
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- status riwayat aduan-->
                <div class="bg-white rounded-lg shadow p-2 border-t-4 border-blue-600">
                    <div>
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                                <img src="{{ asset('icons/light/History.svg') }}" alt="" class="w-5 h-5 mr-2 text-blue-600">
                                Daftar Status Riwayat Aduan
                            </h2>
                        </div>

                        <!-- Pencarian -->
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center gap-2">
                                <label for="search" class="text-sm font-medium text-gray-700">Pencarian:</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    placeholder="Cari fasilitas..."
                                    class="w-64 border border-gray-300 rounded-md shadow-sm sm:text-sm" />
                            </div>
                        </div>
                        {{-- card status riwayat --}}
                        <div class="p-6" id="dashboard-table-body">
                            @include('mahasiswa.dashboard_card', ['aduan' => $aduan])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const tabId = this.id.replace('tab-', '');

                    // Reset all tabs
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active', 'border-orange-500', 'text-blue-600');
                        btn.classList.add('border-transparent', 'text-gray-500');
                    });

                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });

                    // Activate clicked tab
                    this.classList.add('active', 'border-orange-500', 'text-blue-600');
                    this.classList.remove('border-transparent', 'text-gray-500');

                    document.getElementById(`content-${tabId}`).classList.remove('hidden');
                });
            });
        });


        function reloadData() {
            $.ajax({
                url: "{{ route('dashboard.mahasiswa') }}",
                method: 'GET',
                data: {
                    search: $('#search').val(),
                    sort_column: $('#sort-column').val(),
                    sort_direction: $('#sort-direction').val()
                },
                success: function (response) {
                    $('#dashboard-table-body').html(response.html);
                },
                error: function () {
                    Swal.fire('Error', 'Gagal memuat data riwayat aduan', 'error');
                }
            });
        }

        $(document).ready(function () {

            // Event untuk pencarian (dengan debounce)
            let debounceTimer;
            $('#search').on('input', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function () {
                    reloadData();
                }, 300);
            });

            // Event untuk sorting jika ada
            $('#sort-column, #sort-direction').on('change', function () {
                reloadData();
            });
        });
    </script>
@endpush