<!-- Sidebar - Left Side -->
<div id="sidebar" class="sidebar w-64 bg-white shadow-lg z-40">
    <!-- Logo at the top of sidebar -->
    <div class="flex items-center justify-center h-16 bg-white">
        <span class="text-lg font-bold text-black-800 flex items-center">
            <img src="{{ asset('img/logo primer.svg') }}" alt="SIMPEL-POL Logo" class="h-6 mr-2">
            SIMPEL-POL
        </span>
    </div>

    <!-- Sidebar Navigation -->
    <div class="h-full overflow-y-auto">
        <nav class="p-4 space-y-4 text-sm text-gray-700">
            <a href="{{ route('dashboard.mahasiswa') }}"
               class="flex items-center p-2 rounded {{ ($activeMenu == 'home') ? 'bg-blue-800 text-white' : 'hover:bg-gray-100' }}">
                <img src="{{ ($activeMenu == 'home') ? asset('icons/solid/Home.svg') : asset('icons/light/Home.svg') }}"
                     alt="Dashboard Icon" class="mr-2 w-5">
                Dashboard
            </a>

            <div>
                <p class="mb-1 text-xs text-gray-500">Pelaporan</p>
                <a href="#"
                   class="flex items-center p-2 rounded {{ ($activeMenu == 'form-pelaporan') ? 'bg-blue-800 text-white' : 'hover:bg-gray-100' }}">
                    <img src="{{ asset('icons/light/Document.svg') }}" alt="" class="mr-2 w-5">
                    Form Pelaporan
                </a>
                <a href="#"
                   class="flex items-center p-2 rounded {{ ($activeMenu == 'riwayat-pelaporan') ? 'bg-blue-800 text-white' : 'hover:bg-gray-100' }}">
                    <img src="{{ asset('icons/light/Document.svg') }}" alt="" class="mr-2 w-5">
                    Riwayat Pelaporan
                </a>
            </div>
        </nav>
    </div>
</div>
