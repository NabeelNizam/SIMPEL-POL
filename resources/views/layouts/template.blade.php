<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tailwindcss/forms@0.5.2/dist/forms.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>SIMPEL-POL</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html {
            scroll-behavior: smooth;
        }

        /* Sidebar transitions */
        .sidebar {
            transition: transform 0.3s ease-in-out;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            transform: translateX(0); /* Default visible */
            width: 16rem; /* w-64 = 16rem */
            z-index: 40;
        }

        .sidebar.closed {
            transform: translateX(-100%); /* Hide when closed */
        }

        /* Main content spacing */
        .main-content {
            transition: margin-left 0.3s ease-in-out;
            margin-left: 16rem; /* Default with margin */
    }

    .main-content.full {
        margin-left: 0; /* No margin when sidebar is closed */
        }

        /* Responsive adjustments for mobile */
        @media (max-width: 767px) {
            .sidebar {
                transform: translateX(-100%); /* Default hidden on mobile */
        }

        .sidebar.open {
            transform: translateX(0); /* Show when open on mobile */
        }

        .main-content {
            margin-left: 0; /* No margin on mobile by default */
            }
        }
    
    
</style>
</head>

<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        @include('layouts.sidebar')

        <!-- Main Content Container -->
        <div class="main-content flex-1 flex flex-col overflow-hidden">
            <!-- Header Component -->
            @include('layouts.header')

            <!-- Page Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-4 md:p-6">
                <!-- Breadcrumb -->
                @include('layouts.breadcrumb')

                <!-- Main Content -->
                <div class="mt-4">
                    @yield('content')
                </div>
            </main>

            <!-- Footer Component -->
            @include('layouts.footer')
        </div>
    </div>

    <div id="mainModal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center p-4 z-50 hidden">
        <div id="modalContentContainer"
            class="relative bg-white rounded-lg shadow-xl mx-auto overflow-hidden" data-backdrop="static">
        </div>
    </div>
    <!-- Scripts -->
    @include('layouts.scripts')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        
    // Fungsi untuk menampilkan modal utama
    function showMainModal() {
        const mainModal = document.getElementById('mainModal');
        if (mainModal) {
            mainModal.classList.remove('hidden');
            mainModal.classList.add('flex'); // Mengubah display dari hidden ke flex
            // Optional: Tambahkan animasi fade-in
            // mainModal.classList.add('opacity-0', 'transition-opacity', 'duration-300');
            // setTimeout(() => mainModal.classList.remove('opacity-0'), 10);
        }
    }

    // Fungsi untuk menyembunyikan modal utama
    function hideMainModal() {
        const mainModal = document.getElementById('mainModal');
        if (mainModal) {
            // Optional: Tambahkan animasi fade-out
            // mainModal.classList.add('opacity-0');
            // setTimeout(() => {
                mainModal.classList.remove('flex'); // Mengubah display dari flex ke hidden
                mainModal.classList.add('hidden');
                // Kosongkan konten modal setelah disembunyikan (opsional, untuk membersihkan)
                document.getElementById('modalContentContainer').innerHTML = '';
            // }, 300); // Sesuaikan durasi dengan transisi CSS Anda
        }
    }

    // Fungsi modalAction yang akan dipanggil oleh tombol
    function modalAction(url = '') {
        const modalContentContainer = document.getElementById('modalContentContainer');
        if (!modalContentContainer) {
            console.error("Elemen 'modalContentContainer' tidak ditemukan.");
            return;
        }

        // Load konten via AJAX
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.text();
            })
            .then(html => {
                modalContentContainer.innerHTML = html; // Masukkan konten HTML ke dalam kontainer
                showMainModal(); // Tampilkan modal utama (termasuk overlay)
            })
            .catch(error => {
                console.error("Error loading modal content:", error);
                alert("Gagal memuat konten modal."); // Memberi tahu pengguna
            });
    }

    // Event listener untuk tombol tutup di dalam konten modal yang dimuat via AJAX
    // Gunakan event delegation karena tombol tutup dimuat secara dinamis
    document.getElementById('mainModal').addEventListener('click', function(event) {
        // Cek apakah target klik atau leluhurnya memiliki atribut data-dismiss="modal"
        if (event.target.closest('[data-dismiss="modal"]')) {
            hideMainModal();
        }
    });

    // Event listener untuk menutup modal dengan tombol 'Esc'
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            hideMainModal();
        }
    });
    </script>
    @stack('js')
</body>
</html>