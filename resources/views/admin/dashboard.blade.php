@extends('layouts.template')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6" name="laporan">
            {{-- Total Laporan --}}
            <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                <p class="text-sm text-gray-500">TOTAL LAPORAN</p>
                <h2 class="text-2xl font-bold text-gray-800">{{ $totalLaporan }}</h2>
                <p class="text-xs text-gray-400 mt-1">
                    {{ $totalLaporan > 0 ? $totalLaporan . ' laporan baru minggu ini' : 'Tidak ada laporan baru minggu ini' }}
                </p>
            </div>

            {{-- Tertunda --}}
            <div class="bg-white rounded-lg shadow border-l-4 border-yellow-400 p-4">
                <p class="text-sm text-gray-500">TERTUNDA</p>
                <h2 class="text-2xl font-bold text-gray-800">{{ $tertunda }}</h2>
                <p class="text-xs text-gray-400 mt-1">
                    {{ $tertunda > 0 ? $tertunda . ' menunggu lebih dari 48 jam' : 'Tidak ada laporan tertunda' }}</p>
            </div>

            {{-- Dalam Proses --}}
            <div class="bg-white rounded-lg shadow border-l-4 border-blue-500 p-4">
                <p class="text-sm text-gray-500">DALAM PROSES</p>
                <h2 class="text-2xl font-bold text-gray-800">{{ $dalamProses }}</h2>
                <p class="text-xs text-gray-400 mt-1">
                    {{ $dalamProses > 0 ? $dalamProses . ' diperbaiki hari ini' : 'Tidak ada laporan dalam proses' }}</p>
            </div>

            {{-- Selesai --}}
            <div class="bg-white rounded-lg shadow border-l-4 border-green-500 p-4">
                <p class="text-sm text-gray-500">SELESAI</p>
                <h2 class="text-2xl font-bold text-gray-800">{{ $selesai }}</h2>
                <p class="text-xs text-gray-400 mt-1">
                    {{ $selesai > 0 ? $selesai . ' diselesaikan dalam 24 jam terakhir' : 'Tidak ada laporan selesai' }}</p>
            </div>
        </div>

        <div class="flex">
            <div class="flex-1 p-6">
                {{-- SOP Section --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
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
                                    SOP Sarana Prasarana adalah panduan prosedur standar
                                    untuk pengelolaan, pemeliharaan, dan penggunaan fasilitas
                                    di lingkungan Politeknik Negeri Malang.
                                    SOP ini bertujuan untuk memastikan proses pengelolaan
                                    sarana dan prasarana berjalan efektif, efisien, dan sesuai
                                    dengan regulasi yang berlaku.
                                </p>

                                <p class="mb-4">
                                    Seluruh staff dan mahasiswa Polinema diharapkan
                                    mengikuti SOP ini dalam penggunaan dan pengelolaan
                                    sarana prasarana kampus. Untuk mengakses dokumen SOP
                                    lengkap, silakan beralih ke tab "Dokumen SOP".
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
                                            <div class="text-sm font-medium text-gray-900">SOP Untuk Pihak Admin </div>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                       <a href="{{ route('sopDownload', ['role' => $sedangLogin, 'filename' => 'SOP_' . strtoupper($sedangLogin) . '.pdf']) }}"
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
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6 mt-6">
                    {{-- Umpan Balik --}}
                    <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Umpan Balik</h3>
                        <canvas id="umpanBalikChart"></canvas>

                        <div class="text-center mt-4">
                            <h2 class="text-base font-semibold text-gray-800">Rata-rata:</h2>
                            <p class="text-gray-800 font-bold">
                                {{ $umpanBalik->count() > 0 ? round($umpanBalik->sum(fn($item) => $item->rating * $item->total) / $umpanBalik->sum('total'), 2) : 'Belum ada data' }}
                            </p>
                        </div>
                    </div>

                    {{-- Status Perbaikan Fasilitas --}}
                    <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Perbaikan Fasilitas</h3>
                        <canvas id="statusPerbaikanChart"></canvas>
                    </div>

                    {{-- Kategori Kerusakan --}}
                    <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Kategori Kerusakan</h3>
                        <canvas id="kategoriKerusakanChart"></canvas>
                    </div>

                    {{-- Tren Kerusakan Fasilitas --}}
                    <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Tren Kerusakan Fasilitas</h3>
                        <canvas id="trenKerusakanChart"></canvas>
                    </div>

                </div>

                {{-- Bagian Penuh untuk Tren Anggaran --}}
                <div class="bg-white rounded-lg shadow border border-gray-200 p-4 w-full">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Tren Anggaran</h3>
                    <canvas id="trenAnggaranChart"></canvas>
                </div>
            @endsection

            @push('js')
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const tabButtons = document.querySelectorAll('.tab-button');
                        const tabContents = document.querySelectorAll('.tab-content');

                        // Umpan Balik Chart
                        const umpanBalikCtx = document.getElementById('umpanBalikChart').getContext('2d');
                        const umpanBalikChart = new Chart(umpanBalikCtx, {
                            type: 'bar',
                            data: {
                                labels: ['Sangat Puas (5)', 'Puas (4)', 'Cukup Puas (3)', 'Kurang Puas (2)',
                                    'Tidak Puas (1)'
                                ],
                                datasets: [{
                                    label: 'Jumlah Umpan Balik',
                                    data: @json($umpanBalik->pluck('total')),
                                    backgroundColor: [
                                        'rgba(54, 162, 235, 0.5)',
                                        'rgba(75, 192, 192, 0.5)',
                                        'rgba(255, 205, 86, 0.5)',
                                        'rgba(255, 159, 64, 0.5)',
                                        'rgba(255, 99, 132, 0.5)'

                                    ],
                                    borderColor: [
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(75, 192, 192, 1)',
                                        'rgba(255, 205, 86, 1)',
                                        'rgba(255, 159, 64, 1)',
                                        'rgba(255, 99, 132, 1)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        display: true
                                    } // Menampilkan label "Jumlah Pelapor"
                                },
                                scales: {
                                    x: {
                                        title: {
                                            display: true,
                                            text: 'Tingkat Kepuasan' // Label untuk sumbu X
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'Jumlah Pelapor' // Label untuk sumbu Y
                                        }
                                    }
                                }
                            }
                        });

                        // Status Perbaikan Chart
                        const statusPerbaikanCtx = document.getElementById('statusPerbaikanChart').getContext('2d');
                        const statusPerbaikanChart = new Chart(statusPerbaikanCtx, {
                            type: 'doughnut',
                            data: {
                                labels: @json($statusPerbaikan->pluck('status')),
                                datasets: [{
                                    data: @json($statusPerbaikan->pluck('total')),
                                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'top'
                                    }
                                }
                            }
                        });

                        // Kategori Kerusakan Chart
                        const kategoriKerusakanCtx = document.getElementById('kategoriKerusakanChart').getContext('2d');
                        const kategoriKerusakanChart = new Chart(kategoriKerusakanCtx, {
                            type: 'bar',
                            data: {
                                labels: @json($kategoriKerusakan->pluck('kategori')), // Kategori fasilitas
                                datasets: [{
                                    label: 'Jumlah Aduan',
                                    data: @json($kategoriKerusakan->pluck('total')), // Jumlah aduan per kategori
                                    backgroundColor: [
                                        'rgba(255, 99, 132, 0.5)',
                                        'rgba(54, 162, 235, 0.5)',
                                        'rgba(255, 205, 86, 0.5)',
                                        'rgba(75, 192, 192, 0.5)',
                                        'rgba(153, 102, 255, 0.5)'
                                    ],
                                    borderColor: [
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(255, 205, 86, 1)',
                                        'rgba(75, 192, 192, 1)',
                                        'rgba(153, 102, 255, 1)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top'
                                    }
                                },
                                scales: {
                                    x: {
                                        title: {
                                            display: true,
                                            text: 'Kategori Fasilitas' // Label untuk sumbu X
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'Jumlah Aduan' // Label untuk sumbu Y
                                        }
                                    }
                                }
                            }
                        });

                        // Tren Kerusakan Chart
                        const trenKerusakanCtx = document.getElementById('trenKerusakanChart').getContext('2d');
                        const trenKerusakanChart = new Chart(trenKerusakanCtx, {
                            type: 'line',
                            data: {
                                labels: @json($trenKerusakan->pluck('bulan')), // Bulan (1 hingga 12)
                                datasets: [{
                                    label: 'Jumlah Aduan',
                                    data: @json($trenKerusakan->pluck('total')), // Jumlah aduan per bulan
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    backgroundColor: 'rgba(75, 192, 192, 0.4)',
                                    tension: 0.4,
                                    fill: true,
                                    pointBackgroundColor: 'white',
                                    pointBorderColor: 'rgba(75, 192, 192, 1)',
                                    pointRadius: 5,
                                    pointHoverRadius: 6,
                                    borderWidth: 2
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        enabled: true
                                    },
                                    datalabels: {
                                        anchor: 'end',
                                        align: 'top',
                                        color: '#000',
                                        font: {
                                            weight: 'bold'
                                        },
                                        formatter: Math.round
                                    }
                                },
                                scales: {
                                    x: {
                                        title: {
                                            display: true,
                                            text: 'Bulan'
                                        },
                                        ticks: {
                                            callback: function(value, index) {
                                                const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei',
                                                    'Juni', 'Juli', 'Agustus', 'September', 'Oktober',
                                                    'November', 'Desember'
                                                ];
                                                return months[index]; // Menampilkan nama bulan
                                            }
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'Jumlah Laporan'
                                        },
                                        ticks: {
                                            stepSize: 2
                                        }
                                    }
                                }
                            },
                            plugins: [ChartDataLabels]
                        });

                        // Tren Anggaran Chart
                        const trenAnggaranCtx = document.getElementById('trenAnggaranChart').getContext('2d');
                        const trenAnggaranChart = new Chart(trenAnggaranCtx, {
                            type: 'line', // Ubah dari 'bar' ke 'line'
                            data: {
                                labels: @json($trenAnggaran->pluck('bulan')), // Bulan (1 hingga 12)
                                datasets: [{
                                    label: 'Pengeluaran (Rp)',
                                    data: @json($trenAnggaran->pluck('total')), // Total pengeluaran per bulan
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    backgroundColor: 'rgba(255, 99, 132, 0.2)', // Warna area di bawah garis
                                    tension: 0.4, // Membuat garis lebih halus
                                    fill: true, // Isi area di bawah garis
                                    pointBackgroundColor: 'white',
                                    pointBorderColor: 'rgba(255, 99, 132, 1)',
                                    pointRadius: 5,
                                    pointHoverRadius: 6,
                                    borderWidth: 2
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                }
                            }, 
                        });

                        tabButtons.forEach(button => {
                            button.addEventListener('click', function() {
                                const tabId = this.id.replace('tab-', '');

                                // Reset all tabs
                                tabButtons.forEach(btn => {
                                    btn.classList.remove('active', 'border-orange-500',
                                    'text-blue-600');
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
                </script>
