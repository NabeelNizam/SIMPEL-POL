@extends('layouts.template')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="flex">
            <div class="flex-1 p-6">
                {{-- SOP Section --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
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
                                        <svg class="w-5 h-5 text-blue-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd"></path>
                                        </svg>
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
                                            <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">SOP Perbaikan Fasilitas</div>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('download.sop', ['filename' => 'SOP_TEKNISI.pdf']) }}"
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            Unduh
                                        </a>
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
        // Ambil semua tombol tab dan konten tab
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');

        // Tambahkan event listener untuk setiap tombol tab
        tabButtons.forEach(button => {
            button.addEventListener('click', function () {
                const tabId = this.id.replace('tab-', ''); // Ambil ID tab yang sesuai

                // Reset semua tab (hilangkan kelas aktif)
                tabButtons.forEach(btn => {
                    btn.classList.remove('active', 'border-orange-500', 'text-blue-600');
                    btn.classList.add('border-transparent', 'text-gray-500');
                });

                // Sembunyikan semua konten tab
                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });

                // Aktifkan tab yang diklik
                this.classList.add('active', 'border-orange-500', 'text-blue-600');
                this.classList.remove('border-transparent', 'text-gray-500');

                // Tampilkan konten tab yang sesuai
                const targetContent = document.getElementById(`content-${tabId}`);
                if (targetContent) {
                    targetContent.classList.remove('hidden');
                }
            });
        });
    });
</script>