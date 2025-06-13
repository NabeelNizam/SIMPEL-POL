@extends('layouts.app')

@section('content')
    @include('layouts.navbar')

    <div class="bg-[#132145] flex flex-col items-center justify-start pt-64 relative min-h-[850px] w-full mt-16 overflow-hidden" id="beranda">
        <img src="/img/polinema-gb.svg" alt="" class="absolute bottom-0 left-0 w-full h-auto pt-16 opacity-[0.30]">
        <h1 class="text-4xl font-bold z-10 text-white text-center">Laporkan, Pantau, Perbaiki</h1>
        <p class="text-center mt-4 max-w-3xl px-4 mb-8 z-10 text-white">
            SIMPEL-POL adalah solusi terpadu untuk manajemen pelaporan dan perbaikan fasilitas di lingkungan kampus Politeknik Negeri Malang. Lapor kerusakan, pantau proses perbaikan, dan nikmati manfaat dari fasilitas yang terawat dengan baik.
        </p>
        <div class="mt-4 z-10 flex flex-wrap justify-center gap-4">
          <a href="{{ route('login') }}" class="text-[#1E3F8A] bg-white border border-[#1E3F8A] hover:bg-[#1E3F8A] hover:text-white focus:ring-4 focus:ring-blue-100 font-medium rounded-lg text-sm px-6 py-2.5 me-2 mb-2 focus:outline-none transition-colors duration-300 inline-block text-center">
              Masuk
          </a>
            <button type="button" class="text-white border border-white hover:bg-white hover:text-[#132145] focus:ring-4 focus:ring-white font-medium rounded-lg text-sm px-6 py-2.5 mb-2 focus:outline-none cursor-pointer">
                Pelajari lebih lanjut
            </button>
        </div>
    </div>

    <div class="-mt-64 z-20 relative flex justify-center"> <!-- Tarik ke atas agar menyentuh hero -->
        <div class="relative w-full max-w-2xl">
            <div class="swiper rounded-3xl overflow-hidden shadow-lg bg-white p-2 border-16 border-white">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="/img/ruang-kelas.svg" class="w-full" />
                    </div>
                    <div class="swiper-slide">
                        <img src="/img/lab-alat.svg" class="w-full" />
                    </div>
                    <div class="swiper-slide">
                        <img src="/img/lift.svg" class="w-full" />
                    </div>
                </div>
                <div class="swiper-pagination relative py-2"></div>
            </div>
        </div>
    </div>

    {{-- Bagian Fitur --}}
    <section class="bg-gradient-to-b from-white to-[#EFF7FF] py-24" id="fitur">
    <div class="flex flex-col items-center justify-center py-6 px-4 mb-8">
        <div class="text-center mb-12">
            <!-- Judul dan Deskripsi -->
            <h1 class="text-3xl font-bold mb-2">Fitur Unggulan</h1>
            <div class="w-20 h-1 bg-orange-400 mx-auto mb-4 rounded-full"></div>
            <p class="text-center max-w-lg text-gray-600 mb-6 text-sm whitespace-nowrap">
                Sistem yang dirancang untuk memudahkan pelaporan dan manajemen perbaikan fasilitas kampus
            </p>
          </div>

        <!-- Grid Fitur -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-4xl w-full mx-auto">
            <!-- Kartu Fitur -->
            <div class="bg-white shadow-lg rounded-xl p-6 text-left flex flex-col items-center">
            <div class="mb-4">
                <img src="/img/1.svg" alt="" class="h-24 mx-auto object-contain">
            </div>
            <h2 class="font-semibold text-lg mb-2">Pelaporan Mudah</h2>
            <p class="text-gray-600 text-sm">Laporkan kerusakan fasilitas dengan cepat dan mudah menggunakan form yang sederhana namun lengkap, disertai dengan unggah foto bukti kerusakan.</p>
            </div>
        
            <div class="bg-white shadow-md rounded-xl p-6 text-left flex flex-col items-center">
            <div class="mb-4">
                <img src="/img/2.svg" alt="" class="h-24 mx-auto object-contain">
            </div>
            <h2 class="font-semibold text-lg mb-2">Pelacakan Real-time</h2>
            <p class="text-gray-600 text-sm">Pantau status perbaikan secara real-time dari mulai pelaporan hingga penyelesaian perbaikan, dengan notifikasi pada setiap tahapan.</p>
            </div>
        
            <div class="bg-white shadow-md rounded-xl p-6 text-left flex flex-col items-center">
            <div class="mb-4">
                <img src="/img/3.svg" alt="" class="h-24 mx-auto object-contain">
            </div>
            <h2 class="font-semibold text-lg mb-2">Prioritas Cerdas</h2>
            <p class="text-gray-600 text-sm">Sistem penilaian prioritas otomatis membantu menentukan urgensi perbaikan dan mengalokasikan sumber daya dengan optimal.</p>
            </div>
        
            <div class="bg-white shadow-md rounded-xl p-6 text-left flex flex-col items-center">
            <div class="mb-4">
                <img src="/img/4.svg" alt="" class="h-24 mx-auto object-contain">
            </div>
            <h2 class="font-semibold text-lg mb-2">Akses Multi-perangkat</h2>
            <p class="text-gray-600 text-sm">Akses sistem kapan saja dan di mana saja melalui berbagai perangkat dengan tampilan responsif yang nyaman digunakan.</p>
            </div>
        
            <div class="bg-white shadow-md rounded-xl p-6 text-left flex flex-col items-center">
            <div class="mb-4">
                <img src="/img/5.svg" alt="" class="h-24 mx-auto object-contain">
            </div>
            <h2 class="font-semibold text-lg mb-2">Umpan Balik Pengguna</h2>
            <p class="text-gray-600 text-sm">Berikan penilaian dan umpan balik setelah perbaikan selesai untuk terus meningkatkan kualitas layanan.</p>
            </div>
        
            <div class="bg-white shadow-md rounded-xl p-6 text-center flex flex-col items-center">
            <div class="mb-4">
                <img src="/img/6.svg" alt="" class="h-24 mx-auto object-contain">
            </div>
            <h2 class="font-semibold text-lg mb-2">Dashboard Analitik</h2>
            <p class="text-gray-600 text-sm">Analisis data kerusakan dan perbaikan untuk mengidentifikasi tren dan mengambil keputusan perawatan preventif.</p>
            </div>
        </div>
    </div>
    </section>

    <section class="bg-gradient-to-b from-white to-[#EFF7FF] py-32" id="cara-kerja">
        <div class="text-center mb-12">
          <h1 class="text-3xl font-bold mb-2">Cara Kerja</h1>
          <div class="w-20 h-1 bg-orange-400 mx-auto mb-4 rounded-full"></div>
          <p class="text-gray-600">Proses pelaporan dan perbaikan fasilitas yang terstruktur dan transparan</p>
        </div>
      
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8 px-4">
            <!-- Laporkan -->
            <div class="flex flex-col items-center text-center">
              <div class="bg-white rounded-full shadow-md mb-4 inline-flex items-center justify-center p-6">
                <img src="/img/laporkan.svg" alt="Laporkan" class="w-16 h-16" />
              </div>
              <h3 class="font-semibold text-lg mb-2">Laporkan</h3>
              <p class="text-gray-600 text-sm">Mahasiswa, dosen, atau tendik melaporkan kerusakan fasilitas melalui sistem</p>
            </div>
          
            <!-- Verifikasi -->
            <div class="flex flex-col items-center text-center">
              <div class="bg-white rounded-full shadow-md mb-4 inline-flex items-center justify-center p-6">
                <img src="/img/verifikasi.svg" alt="Verifikasi" class="w-16 h-16" />
              </div>
              <h3 class="font-semibold text-lg mb-2">Verifikasi</h3>
              <p class="text-gray-600 text-sm">Tim Sarana Prasarana memverifikasi laporan dan menentukan prioritas</p>
            </div>
          
            <!-- Perbaiki -->
            <div class="flex flex-col items-center text-center">
              <div class="bg-white rounded-full shadow-md mb-4 inline-flex items-center justify-center p-6">
                <img src="/img/perbaiki.svg" alt="Perbaiki" class="w-16 h-16" />
              </div>
              <h3 class="font-semibold text-lg mb-2">Perbaiki</h3>
              <p class="text-gray-600 text-sm">Teknisi melakukan perbaikan dan memperbarui status pengerjaan</p>
            </div>
          
            <!-- Evaluasi -->
            <div class="flex flex-col items-center text-center">
              <div class="bg-white rounded-full shadow-md mb-4 inline-flex items-center justify-center p-6">
                <img src="/img/feedback.svg" alt="Evaluasi" class="w-16 h-16" />
              </div>
              <h3 class="font-semibold text-lg mb-2">Evaluasi</h3>
              <p class="text-gray-600 text-sm">Pelapor memberikan umpan balik terhadap hasil perbaikan</p>
            </div>
          </div>
      </section>

      <section class="py-16 bg-white pb-32 pt-32">
        <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
          
          <!-- Statistik 1 -->
          <div>
            <h2 class="text-3xl font-bold text-blue-900">500+</h2>
            <p class="text-sm text-gray-700 mt-2">Fasilitas Terdata</p>
          </div>
      
          <!-- Statistik 2 -->
          <div>
            <h2 class="text-3xl font-bold text-blue-900">100%</h2>
            <p class="text-sm text-gray-700 mt-2">Tingkat Penyelesaian</p>
          </div>
      
          <!-- Statistik 3 -->
          <div>
            <h2 class="text-3xl font-bold text-blue-900">24 Jam</h2>
            <p class="text-sm text-gray-700 mt-2">Respon Rata-rata</p>
          </div>
      
        </div>
      </section>

      {{-- SESI PERTANYAAN --}}
      <section id="faq" class="pt-36">
        <div>
          <div class="text-center mb-12">
            <h1 class="text-3xl font-bold mb-2">Pertanyaan Umum</h1>
            <div class="w-20 h-1 bg-orange-400 mx-auto mb-4 rounded-full"></div>
            <p class="text-gray-600">Jawaban untuk pertanyaan yang sering ditanyakan terkait SIMFAS</p>
          </div>
      
        <!-- Accordion Item dengan animasi JavaScript -->
        <div class="max-w-3xl mx-auto space-y-4 pb-64" id="accordion-container">
          <!-- Accordion Item 1 -->
          <div class="border rounded-lg shadow-sm overflow-hidden">
            <div class="font-medium cursor-pointer flex justify-between items-center p-4 bg-white hover:bg-gray-50 accordion-header">
              Siapa saja yang dapat melaporkan kerusakan fasilitas?
              <svg class="w-5 h-5 accordion-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M19 9l-7 7-7-7" />
              </svg>
            </div>
            <div class="accordion-content bg-gray-50 text-gray-600 px-4 py-0 h-0 overflow-hidden transition-all duration-300">
              <div class="py-4">
                <p>Mahasiswa, dosen, dan tenaga kependidikan dapat menggunakan sistem ini untuk melaporkan kerusakan fasilitas.</p>
              </div>
            </div>
          </div>

          <!-- Accordion Item 2 -->
          <div class="border rounded-lg shadow-sm overflow-hidden">
            <div class="font-medium cursor-pointer flex justify-between items-center p-4 bg-white hover:bg-gray-50 accordion-header">
              Bagaimana cara melaporkan kerusakan fasilitas?
              <svg class="w-5 h-5 accordion-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M19 9l-7 7-7-7" />
              </svg>
            </div>
            <div class="accordion-content bg-gray-50 text-gray-600 px-4 py-0 h-0 overflow-hidden transition-all duration-300">
              <div class="py-4">
                <p>Masuk ke sistem dan pilih menu "Laporkan Kerusakan", kemudian isi formulir laporan dengan lengkap.</p>
              </div>
            </div>
          </div>

       <!-- Accordion Item 3 -->
        <div class="border rounded-lg shadow-sm overflow-hidden">
          <div class="font-medium cursor-pointer flex justify-between items-center p-4 bg-white hover:bg-gray-50 accordion-header">
            Berapa lama waktu yang dibutuhkan untuk menindaklanjuti laporan?
            <svg class="w-5 h-5 accordion-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M19 9l-7 7-7-7" />
            </svg>
          </div>
          <div class="accordion-content bg-gray-50 text-gray-600 px-4 py-0 h-0 overflow-hidden transition-all duration-300">
            <div class="py-4">
              <p>Waktu penanganan tergantung pada jenis kerusakan dan prioritas laporan, namun biasanya dalam 1–2 hari kerja.</p>
            </div>
          </div>
        </div>

        <!-- Accordion Item 4 -->
        <div class="border rounded-lg shadow-sm overflow-hidden">
          <div class="font-medium cursor-pointer flex justify-between items-center p-4 bg-white hover:bg-gray-50 accordion-header">
            Apakah saya bisa melihat status laporan yang saya lihat?
            <svg class="w-5 h-5 accordion-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M19 9l-7 7-7-7" />
            </svg>
          </div>
          <div class="accordion-content bg-gray-50 text-gray-600 px-4 py-0 h-0 overflow-hidden transition-all duration-300">
            <div class="py-4">
              <p>Ya, Anda dapat memantau status laporan melalui menu "Riwayat Laporan".</p>
            </div>
          </div>
        </div>

        <!-- Accordion Item 5 -->
        <div class="border rounded-lg shadow-sm overflow-hidden">
          <div class="font-medium cursor-pointer flex justify-between items-center p-4 bg-white hover:bg-gray-50 accordion-header">
            Bagaimana jika saya lupa password akun saya?
            <svg class="w-5 h-5 accordion-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M19 9l-7 7-7-7" />
            </svg>
          </div>
          <div class="accordion-content bg-gray-50 text-gray-600 px-4 py-0 h-0 overflow-hidden transition-all duration-300">
            <div class="py-4">
              <p>Klik "Lupa Password" pada halaman login dan ikuti instruksi untuk reset password melalui email Anda.</p>
            </div>
          </div>
        </div>
      </section>

      <footer class="bg-[#0E1A40] text-white py-12">
        <div class="max-w-8xl mx-auto px-14 grid grid-cols-1 md:grid-cols-4 gap-16">
          <!-- SIMPEL-POL Description -->
          <div>
            <h2 class="text-lg font-semibold mb-2">SIMPEL-POL</h2>
            <p class="text-sm text-gray-300 mb-4">Sistem Manajemen Pelaporan dan Perbaikan Fasilitas Kampus Politeknik Negeri Malang.</p>
            <div class="flex space-x-8">
              <a href="#" class="bg-blue-700 p-2 rounded-full hover:bg-blue-500 transition flex items-center justify-center">
                <img src="/img/instagram.svg" alt="Instagram" class="w-5 h-5">
              </a>
              <a href="#" class="bg-blue-700 p-2 rounded-full hover:bg-blue-500 transition flex items-center justify-center">
                <img src="/img/yt.svg" alt="YouTube" class="w-5 h-5">
              </a>
              <a href="#" class="bg-blue-700 p-2 rounded-full hover:bg-blue-500 transition flex items-center justify-center">
                <img src="/img/x.svg" alt="Twitter/X" class="w-5 h-5">
              </a>
            </div>
          </div>
      
          <!-- Menu -->
          <div class="px-24">
            <h2 class="text-lg font-semibold mb-2">Menu</h2>
            <ul class="text-sm text-gray-300 space-y-2">
              <li><a href="#beranda" class="hover:text-white transition duration-200">Beranda</a></li>
              <li><a href="#fitur" class="hover:text-white transition duration-200">Fitur</a></li>
              <li><a href="#cara-kerja" class="hover:text-white transition duration-200">Cara Kerja</a></li>
              <li><a href="#faq" class="hover:text-white transition duration-200">FAQ</a></li>
            </ul>
          </div>
      
          <!-- Tautan -->
          <div>
            <h2 class="text-lg font-semibold mb-2">Tautan</h2>
            <ul class="text-sm text-gray-300 space-y-2">
              <li><a href="#">Website Polinema</a></li>
              <li><a href="#">Jurusan TI Polinema</a></li>
              <li><a href="#">Manual Penggunaan</a></li>
              <li><a href="#">Kebijakan Privasi</a></li>
            </ul>
          </div>
      
          <!-- Kontak -->
          <div>
            <h2 class="text-lg font-semibold mb-2">Kontak</h2>
            <ul class="text-sm text-gray-300 space-y-3">
              <li class="flex items-start"><i class="fas fa-map-marker-alt mr-2 mt-1"></i>Jl. Soekarno Hatta No.9, Malang</li>
              <li class="flex items-center"><i class="fas fa-phone mr-2"></i>(0341) 404424</li>
              <li class="flex items-center"><i class="fas fa-envelope mr-2"></i>support@simpelpol.polinema.ac.id</li>
            </ul>
          </div>
        </div>
      
        <div class="border-t border-blue-900 mt-10 pt-6 text-center text-gray-400 text-sm">
          © 2025 SIMFAS - Politeknik Negeri Malang. All rights reserved.
        </div>
      </footer>
      
      
          
      
@endsection

@push('js')
<script>
  document.addEventListener('DOMContentLoaded', function() {
      // Inisialisasi Swiper
      new Swiper('.swiper', {
          loop: true,
          autoplay: {
              delay: 3000,
          },
          pagination: {
              el: '.swiper-pagination',
              clickable: true,
          }
      });
      
      // Animasi accordion
      const accordionHeaders = document.querySelectorAll('.accordion-header');
      
      accordionHeaders.forEach(header => {
          header.addEventListener('click', function() {
              // Toggle active class untuk header yang diklik
              this.classList.toggle('active');
              
              // Rotasi icon
              const icon = this.querySelector('.accordion-icon');
              if (this.classList.contains('active')) {
                  icon.style.transform = 'rotate(180deg)';
              } else {
                  icon.style.transform = 'rotate(0)';
              }
              
              // Ambil content panel
              const content = this.nextElementSibling;
              const contentInner = content.querySelector('div');
              
              // Jika active, expand content
              if (this.classList.contains('active')) {
                  content.style.height = contentInner.offsetHeight + 'px';
              } else {
                  content.style.height = '0px';
              }
              
              // Tutup accordion lain yang sedang terbuka
              accordionHeaders.forEach(otherHeader => {
                  if (otherHeader !== this && otherHeader.classList.contains('active')) {
                      otherHeader.classList.remove('active');
                      otherHeader.nextElementSibling.style.height = '0px';
                      otherHeader.querySelector('.accordion-icon').style.transform = 'rotate(0)';
                  }
              });
          });
      });
  });
</script>
