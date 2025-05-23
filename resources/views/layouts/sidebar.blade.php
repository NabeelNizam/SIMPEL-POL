<!-- Sidebar - Left Side -->
<div id="sidebar" class="sidebar w-64 bg-white shadow-lg z-40">
    <!-- Logo at the top of sidebar -->
    <div class="flex items-center justify-center h-16 border-b bg-white">
        <span class="text-lg font-bold text-black-800 flex items-center">
            <img src="{{ asset('img/logo primer.svg') }}" alt="SIMPEL-POL Logo" class="h-6 mr-2">
            SIMPEL-POL
        </span>
    </div>

    <!-- Sidebar Navigation -->
    @if(Auth::check())
        @if(Auth::user()->id_role == 2) {{-- Admin --}}
            <div class="h-full overflow-y-auto">
                <nav class="p-4 space-y-4 text-sm text-gray-700">
                    <a href="#" class="flex items-center p-2 {{ ($activeMenu == 'dashboard') ? 'bg-blue-800 text-white' : 'hover:bg-gray-100' }} rounded">
                        <img src="{{ ($activeMenu == 'dashboard') ? asset('icons/solid/Home.svg') : asset('icons/light/Home.svg') }}" alt="Dashboard" class="mr-2 w-5">
                        Dashboard
                    </a>

                    <div>
                        <p class="mb-1 text-xs text-gray-500 uppercase">Manajemen Data Pengguna</p>
                        <a href="#" class="flex items-center p-2 {{ ($activeMenu == 'pengguna') ? 'bg-blue-800 text-white' : 'hover:bg-gray-100' }} rounded">
                            <img src="{{ asset('icons/light/Users.svg') }}" alt="Pengguna" class="mr-2 w-5">
                            Pengguna
                        </a>
                        <a href="#" class="flex items-center p-2 {{ ($activeMenu == 'role') ? 'bg-blue-800 text-white' : 'hover:bg-gray-100' }} rounded">
                            <img src="{{ asset('icons/light/Layers.svg') }}" alt="Role" class="mr-2 w-5">
                            Role
                        </a>
                        <a href="#" class="flex items-center p-2 {{ ($activeMenu == 'jurusan') ? 'bg-blue-800 text-white' : 'hover:bg-gray-100' }} rounded">
                            <img src="{{ asset('icons/light/Layers.svg') }}" alt="Jurusan" class="mr-2 w-5">
                            Jurusan
                        </a>
                    </div>

                    <div>
                        <p class="mb-1 text-xs text-gray-500 uppercase">Manajemen Data Fasilitas</p>
                        <a href="#" class="flex items-center p-2 {{ ($activeMenu == 'fasilitas') ? 'bg-blue-800 text-white' : 'hover:bg-gray-100' }} rounded">
                            <img src="{{ asset('icons/light/Layers.svg') }}" alt="Fasilitas" class="mr-2 w-5">
                            Fasilitas
                        </a>
                        <a href="#" class="flex items-center p-2 {{ ($activeMenu == 'kategori') ? 'bg-blue-800 text-white' : 'hover:bg-gray-100' }} rounded">
                            <img src="{{ asset('icons/light/Layers.svg') }}" alt="Kategori" class="mr-2 w-5">
                            Kategori Fasilitas
                        </a>
                        <a href="#" class="flex items-center p-2 {{ ($activeMenu == 'lokasi') ? 'bg-blue-800 text-white' : 'hover:bg-gray-100' }} rounded">
                            <img src="{{ asset('icons/light/Layers.svg') }}" alt="Lokasi" class="mr-2 w-5">
                            Lokasi
                        </a>
                    </div>

                    <div>
                        <p class="mb-1 text-xs text-gray-500 uppercase">Laporan</p>
                        <a href="#" class="flex items-center p-2 {{ ($activeMenu == 'laporan') ? 'bg-blue-800 text-white' : 'hover:bg-gray-100' }} rounded">
                            <img src="{{ asset('icons/light/Document.svg') }}" alt="Laporan Perbaikan" class="mr-2 w-5">
                            Laporan Perbaikan
                        </a>
                    </div>

                    <div>
                        <p class="mb-1 text-xs text-gray-500 uppercase">Kelola</p>
                        <a href="#" class="flex items-center p-2 {{ ($activeMenu == 'periode') ? 'bg-blue-800 text-white' : 'hover:bg-gray-100' }} rounded">
                            <img src="{{ asset('icons/light/TimeSquare.svg') }}" alt="Periode" class="mr-2 w-5">
                            Periode
                        </a>
                        <a href="#" class="flex items-center p-2 {{ ($activeMenu == 'kriteria') ? 'bg-blue-800 text-white' : 'hover:bg-gray-100' }} rounded">
                            <img src="{{ asset('icons/light/Layers.svg') }}" alt="Kriteria" class="mr-2 w-5">
                            Kriteria Prioritas Perbaikan
                        </a>
                        <a href="#" class="flex items-center p-2 {{ ($activeMenu == 'sop') ? 'bg-blue-800 text-white' : 'hover:bg-gray-100' }} rounded">
                            <img src="{{ asset('icons/light/Settings.svg') }}" alt="SOP" class="mr-2 w-5">
                            SOP
                        </a>
                    </div>
                </nav>
            </div>
        @elseif(Auth::user()->id_role == 1) {{-- Mahasiswa --}}
            <div class="h-full overflow-y-auto">
                <nav class="p-4 space-y-4 text-sm text-gray-700">
                    <a href="#"
                    class="flex items-center p-2 rounded {{ ($activeMenu == 'home') ? 'bg-blue-800 text-white' : 'hover:bg-gray-100' }}">
                        <img src="{{ ($activeMenu == 'home') ? asset('icons/solid/Home.svg') : asset('icons/light/Home.svg') }}"
                            alt="Dashboard Icon" class="mr-2 w-5">
                        Dashboard
                    </a>

                    <div>
                        <p class="mb-1 text-xs text-gray-500 uppercase">Pelaporan</p>
                        <a href="#"
                        class="flex items-center p-2 rounded {{ ($activeMenu == 'form-pelaporan') ? 'bg-blue-800 text-white' : 'hover:bg-gray-100' }}">
                            <img src="{{ asset('icons/light/Document.svg') }}" alt="Form Pelaporan" class="mr-2 w-5">
                            Form Pelaporan
                        </a>
                        <a href="#"
                        class="flex items-center p-2 rounded {{ ($activeMenu == 'riwayat-pelaporan') ? 'bg-blue-800 text-white' : 'hover:bg-gray-100' }}">
                            <img src="{{ asset('icons/light/Document.svg') }}" alt="Riwayat Pelaporan" class="mr-2 w-5">
                            Riwayat Pelaporan
                        </a>
                    </div>
                </nav>
            </div>
        @elseif(Auth::user()->id_role == 3) {{-- Teknisi/Sarpras --}}
            <div class="h-full overflow-y-auto">
                <nav class="p-4 space-y-4 text-sm text-gray-700">
                    <a href="#"
                    class="flex items-center p-2 rounded {{ ($activeMenu == 'home') ? 'bg-blue-800 text-white' : 'hover:bg-gray-100' }}">
                        <img src="{{ ($activeMenu == 'home') ? asset('icons/solid/Home.svg') : asset('icons/light/Home.svg') }}"
                            alt="Dashboard Icon" class="mr-2 w-5">
                        Dashboard
                    </a>

                    <div>
                        <p class="mb-1 text-xs text-gray-500 uppercase">Penanganan Laporan</p>
                        <a href="#"
                        class="flex items-center p-2 rounded {{ ($activeMenu == 'laporan-pending') ? 'bg-blue-800 text-white' : 'hover:bg-gray-100' }}">
                            <img src="{{ asset('icons/light/Document.svg') }}" alt="Laporan Pending" class="mr-2 w-5">
                            Laporan Pending
                        </a>
                        <a href="#"
                        class="flex items-center p-2 rounded {{ ($activeMenu == 'laporan-process') ? 'bg-blue-800 text-white' : 'hover:bg-gray-100' }}">
                            <img src="{{ asset('icons/light/Document.svg') }}" alt="Laporan Proses" class="mr-2 w-5">
                            Laporan Proses
                        </a>
                        <a href="#"
                        class="flex items-center p-2 rounded {{ ($activeMenu == 'laporan-completed') ? 'bg-blue-800 text-white' : 'hover:bg-gray-100' }}">
                            <img src="{{ asset('icons/light/Document.svg') }}" alt="Laporan Selesai" class="mr-2 w-5">
                            Laporan Selesai
                        </a>
                    </div>
                </nav>
            </div>
        @elseif(Auth::user()->id_role == 4) {{-- Dosen/Pegawai --}}
            <div class="h-full overflow-y-auto">
                <nav class="p-4 space-y-4 text-sm text-gray-700">
                    <a href="#"
                    class="flex items-center p-2 rounded {{ ($activeMenu == 'home') ? 'bg-blue-800 text-white' : 'hover:bg-gray-100' }}">
                        <img src="{{ ($activeMenu == 'home') ? asset('icons/solid/Home.svg') : asset('icons/light/Home.svg') }}"
                            alt="Dashboard Icon" class="mr-2 w-5">
                        Dashboard
                    </a>

                    <div>
                        <p class="mb-1 text-xs text-gray-500 uppercase">Pelaporan</p>
                        <a href="#"
                        class="flex items-center p-2 rounded {{ ($activeMenu == 'form-pelaporan') ? 'bg-blue-800 text-white' : 'hover:bg-gray-100' }}">
                            <img src="{{ asset('icons/light/Document.svg') }}" alt="Form Pelaporan" class="mr-2 w-5">
                            Form Pelaporan
                        </a>
                        <a href="#"
                        class="flex items-center p-2 rounded {{ ($activeMenu == 'riwayat-pelaporan') ? 'bg-blue-800 text-white' : 'hover:bg-gray-100' }}">
                            <img src="{{ asset('icons/light/Document.svg') }}" alt="Riwayat Pelaporan" class="mr-2 w-5">
                            Riwayat Pelaporan
                        </a>
                    </div>
                </nav>
            </div>
        @else {{-- Role lain/tidak dikenal --}}
            <div class="h-full overflow-y-auto">
                <nav class="p-4 space-y-4 text-sm text-gray-700">
                    <div class="p-3 bg-yellow-100 text-yellow-800 rounded">
                        <p>Menu tidak tersedia untuk role Anda (ID: {{ Auth::user()->id_role }})</p>
                    </div>
                    
                    <a href="#" class="flex items-center p-2 hover:bg-gray-100 rounded">
                        <img src="{{ asset('icons/light/Home.svg') }}" alt="Home" class="mr-2 w-5">
                        Beranda
                    </a>
                </nav>
            </div>
        @endif
    @endif
</div>