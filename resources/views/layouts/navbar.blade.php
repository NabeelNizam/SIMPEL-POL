<nav class="bg-white border-gray-200 fixed top-0 left-0 right-0 z-50 shadow-sm">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
  <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
      <span class="text-lg font-bold text-black-800 flex items-center">
          <img src="{{ asset('img/logo landing.svg') }}" alt="SIMPEL-POL Logo" class="h-8 mr-6">
          SIMPEL-POL
      </span>
  </a>
  <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
    <a href="{{ route('login') }}" class="text-[#1E3F8A] bg-white border border-[#1E3F8A] hover:bg-[#1E3F8A] hover:text-white focus:ring-4 focus:ring-blue-100 font-medium rounded-lg text-sm px-6 py-2.5 me-2 mb-2 focus:outline-none transition-colors duration-300 inline-block text-center">
      Masuk
    </a>
      <button data-collapse-toggle="navbar-cta" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 cursor-pointer" aria-controls="navbar-cta" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
        </svg>
    </button>
  </div>
  <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-cta">
    <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white">
      <li>
        <a href="#beranda" class="block py-2 px-3 md:p-0 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 nav-link">Beranda</a>
      </li>
      <li>
        <a href="#fitur" class="block py-2 px-3 md:p-0 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 nav-link">Fitur</a>
      </li>
      <li>
        <a href="#cara-kerja" class="block py-2 px-3 md:p-0 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 nav-link">Cara Kerja</a>
      </li>
      <li>
        <a href="#faq" class="block py-2 px-3 md:p-0 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 nav-link">FAQ</a>
      </li>
    </ul>
  </div>
  </div>
</nav>

@push('js')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Fungsi untuk menangani scroll
    function handleScroll() {
      // Ambil semua section
      const sections = document.querySelectorAll('section[id], div[id="beranda"]');
      
      // Ambil posisi scroll saat ini
      const scrollPosition = window.scrollY + 100; // offset 100px agar header bisa masuk hitungan
      
      // Loop melalui semua section
      sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.offsetHeight;
        const sectionId = section.getAttribute('id');
        
        // Cek apakah scroll saat ini berada di section ini
        if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
          // Hapus kelas aktif dari semua link
          document.querySelectorAll('#navbar-cta a').forEach(link => {
            link.classList.remove('text-blue-700');
            link.classList.add('text-gray-900');
          });
          
          // Tambahkan kelas aktif ke link yang sesuai
          const activeLink = document.querySelector(`#navbar-cta a[href="#${sectionId}"]`);
          if (activeLink) {
            activeLink.classList.remove('text-gray-900');
            activeLink.classList.add('text-blue-700');
          }
        }
      });
    }
    
    // Tambahkan event listener untuk scroll
    window.addEventListener('scroll', handleScroll);
    
    // Jalankan sekali saat halaman dimuat
    handleScroll();

    // Tambahkan event listener untuk klik pada link navigasi
    document.querySelectorAll('#navbar-cta a').forEach(link => {
      link.addEventListener('click', function(e) {
        // Hapus kelas aktif dari semua link
        document.querySelectorAll('#navbar-cta a').forEach(item => {
          item.classList.remove('text-blue-700');
          item.classList.add('text-gray-900');
        });
        
        // Tambahkan kelas aktif ke link yang diklik
        this.classList.remove('text-gray-900');
        this.classList.add('text-blue-700');
      });
    });
  });
</script>
@endpush