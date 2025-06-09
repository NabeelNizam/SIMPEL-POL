<!-- Sidebar - Left Side -->
<div id="sidebar" class="sidebar w-64 bg-white shadow-lg z-40">
    {{-- <!-- Logo at the top of sidebar -->
    <div class="flex items-center justify-center h-16 bg-white">
        <span class="text-lg font-bold text-black-800 flex items-center">
            <img src="{{ asset('img/logo primer.svg') }}" alt="SIMPEL-POL Logo" class="h-6 mr-2">
            SIMPEL-POL
        </span>
    </div> --}}

    <!-- Sidebar Navigation -->
    @if (Auth::check())
        @if (Auth::user()->id_role == 2)
            {{-- Admin --}}
            <!-- Logo at the top of sidebar -->
            <div class="flex items-center justify-center h-16 bg-white">
                <a href="{{ route('admin.dashboard') }}">
                    <span class="text-lg font-bold text-black-800 flex items-center">
                        <img src="{{ asset('img/logo primer.svg') }}" alt="SIMPEL-POL Logo" class="h-6 mr-2">
                        SIMPEL-POL
                    </span>
                </a>
            </div>
            <div class="h-full overflow-y-auto">
                <nav class="p-4 space-y-4 text-sm text-gray-700">
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center p-2 w-full {{ $activeMenu == 'dashboard' ? 'bg-blue-800 text-white border-r-4' : 'hover:bg-gray-100' }} rounded-none"
                        style="{{ $activeMenu == 'dashboard' ? 'border-color: #F99D1C;' : '' }}">
                        <img src="{{ $activeMenu == 'dashboard' ? asset('icons/solid/Home.svg') : asset('icons/light/Home.svg') }}"
                            alt="Dashboard" class="mr-2 w-5">
                        Dashboard
                    </a>

                    <div>
                        <p class="mb-1 text-xs text-gray-500 uppercase">Manajemen Data Pengguna</p>
                        <a href="{{ route('admin.pengguna') }}"
                            class="flex items-center p-2 w-full {{ $activeMenu == 'pengguna' ? 'bg-blue-800 text-white border-r-4' : 'hover:bg-gray-100' }} rounded-none"
                            style="{{ $activeMenu == 'pengguna' ? 'border-color: #F99D1C;' : '' }}">
                            <img src="{{ $activeMenu == 'pengguna' ? asset('icons/solid/Users.svg') : asset('icons/light/Users.svg') }}"
                                alt="Pengguna" class="mr-2 w-5">
                            Pengguna
                        </a>
                        <a href="{{ route('admin.role') }}"
                            class="flex items-center p-2 w-full {{ $activeMenu == 'role' ? 'bg-blue-800 text-white border-r-4' : 'hover:bg-gray-100' }} rounded-none"
                            style="{{ $activeMenu == 'role' ? 'border-color: #F99D1C;' : '' }}">
                            <img src="{{ $activeMenu == 'role' ? asset('icons/solid/Layers.svg') : asset('icons/light/Layers.svg') }}"
                                alt="Role" class="mr-2 w-5">
                            Role
                        </a>
                        <a href="{{ route('admin.jurusan') }}"
                            class="flex items-center p-2 w-full {{ $activeMenu == 'jurusan' ? 'bg-blue-800 text-white border-r-4' : 'hover:bg-gray-100' }} rounded-none"
                            style="{{ $activeMenu == 'jurusan' ? 'border-color: #F99D1C;' : '' }}">
                            <img src="{{ $activeMenu == 'jurusan' ? asset('icons/solid/Layers.svg') : asset('icons/light/Layers.svg') }}"
                                alt="Jurusan" class="mr-2 w-5">
                            Jurusan
                        </a>
                    </div>

                    <div>
                        <p class="mb-1 text-xs text-gray-500 uppercase">Manajemen Data Fasilitas</p>
                        <a href="{{ route('admin.fasilitas') }}"
                            class="flex items-center p-2 w-full {{ $activeMenu == 'fasilitas' ? 'bg-blue-800 text-white border-r-4' : 'hover:bg-gray-100' }} rounded-none"
                            style="{{ $activeMenu == 'fasilitas' ? 'border-color: #F99D1C;' : '' }}">
                            <img src="{{ $activeMenu == 'fasilitas' ? asset('icons/solid/Layers.svg') : asset('icons/light/Layers.svg') }}"
                                alt="Fasilitas" class="mr-2 w-5">
                            Fasilitas
                        </a>
                        <a href="{{ route('admin.kategori') }}"
                            class="flex items-center p-2 w-full {{ $activeMenu == 'kategori' ? 'bg-blue-800 text-white border-r-4' : 'hover:bg-gray-100' }} rounded-none"
                            style="{{ $activeMenu == 'kategori' ? 'border-color: #F99D1C;' : '' }}">
                            <img src="{{ $activeMenu == 'kategori' ? asset('icons/solid/Layers.svg') : asset('icons/light/Layers.svg') }}"
                                alt="Kategori" class="mr-2 w-5">
                            Kategori Fasilitas
                        </a>
                        <a href="lokasi"
                            class="flex items-center p-2 w-full {{ $activeMenu == 'lokasi' ? 'bg-blue-800 text-white border-r-4' : 'hover:bg-gray-100' }} rounded-none"
                            style="{{ $activeMenu == 'lokasi' ? 'border-color: #F99D1C;' : '' }}">
                            <img src="{{ $activeMenu == 'lokasi' ? asset('icons/solid/Layers.svg') : asset('icons/light/Layers.svg') }}"
                                alt="Lokasi" class="mr-2 w-5">
                            Lokasi
                        </a>
                    </div>

                    <div>
                        <p class="mb-1 text-xs text-gray-500 uppercase">Laporan</p>
                        <a href="{{ route('admin.aduan') }}"
                            class="flex items-center p-2 w-full {{ $activeMenu == 'aduan' ? 'bg-blue-800 text-white border-r-4' : 'hover:bg-gray-100' }} rounded-none"
                            style="{{ $activeMenu == 'aduan' ? 'border-color: #F99D1C;' : '' }}">
                            <img src="{{ $activeMenu == 'aduan' ? asset('icons/solid/Document.svg') : asset('icons/light/Document.svg') }}"
                                alt="Laporan Perbaikan" class="mr-2 w-5">
                            Laporan Perbaikan
                        </a>
                    </div>

                    <div>
                        <p class="mb-1 text-xs text-gray-500 uppercase">Kelola</p>
                        <a href="{{ route('admin.periode') }}"
                            class="flex items-center p-2 w-full {{ $activeMenu == 'periode' ? 'bg-blue-800 text-white border-r-4' : 'hover:bg-gray-100' }} rounded-none"
                            style="{{ $activeMenu == 'periode' ? 'border-color: #F99D1C;' : '' }}">
                            <img src="{{ $activeMenu == 'periode' ? asset('icons/solid/Time square.svg') : asset('icons/light/Time square.svg') }}"
                                alt="Periode" class="mr-2 w-5">
                            Periode
                        </a>
                        <a href="#"
                            class="flex items-center p-2 w-full {{ $activeMenu == 'kriteria' ? 'bg-blue-800 text-white border-r-4' : 'hover:bg-gray-100' }} rounded-none"
                            style="{{ $activeMenu == 'kriteria' ? 'border-color: #F99D1C;' : '' }}">
                            <img src="{{ $activeMenu == 'kriteria' ? asset('icons/solid/Layers.svg') : asset('icons/light/Layers.svg') }}"
                                alt="Kriteria" class="mr-2 w-5">
                            Kriteria Prioritas Perbaikan
                        </a>
                        <a href="#"
                            class="flex items-center p-2 w-full {{ $activeMenu == 'sop' ? 'bg-blue-800 text-white border-r-4' : 'hover:bg-gray-100' }} rounded-none"
                            style="{{ $activeMenu == 'sop' ? 'border-color: #F99D1C;' : '' }}">
                            <img src="{{ $activeMenu == 'sop' ? asset('icons/solid/Settings.svg') : asset('icons/light/Settings.svg') }}"
                                alt="SOP" class="mr-2 w-5">
                            SOP
                        </a>
                    </div>
                </nav>
            </div>
        @elseif(in_array(Auth::user()->id_role, [1, 5, 6]))
            {{-- Mahasiswa|Dosen|Tendik --}}
            <!-- Logo at the top of sidebar -->
            <div class="flex items-center justify-center h-16 bg-white">
                <a href="{{ route('mahasiswa.dashboard') }}">
                    <span class="text-lg font-bold text-black-800 flex items-center">
                        <img src="{{ asset('img/logo primer.svg') }}" alt="SIMPEL-POL Logo" class="h-6 mr-2">
                        SIMPEL-POL
                    </span>
                </a>
            </div>
            <div class="h-full overflow-y-auto">
                <nav class="p-4 space-y-4 text-sm text-gray-700">
                    <a href="{{ route('dashboard.mahasiswa') }}"
                        class="flex items-center p-2 w-full {{ $activeMenu == 'home' ? 'bg-blue-800 text-white border-r-4' : 'hover:bg-gray-100' }} rounded-none"
                        style="{{ $activeMenu == 'home' ? 'border-color: #F99D1C;' : '' }}">
                        <img src="{{ $activeMenu == 'home' ? asset('icons/solid/Home.svg') : asset('icons/light/Home.svg') }}"
                            alt="Dashboard Icon" class="mr-2 w-5">
                        Dashboard
                    </a>

                    <div>
                        <p class="mb-1 text-xs text-gray-500 uppercase">Pelaporan</p>
                        <a href="{{ route('mahasiswa.form') }}"
                            class="flex items-center p-2 w-full {{ $activeMenu == 'form' ? 'bg-blue-800 text-white border-r-4' : 'hover:bg-gray-100' }} rounded-none"
                            style="{{ $activeMenu == 'form' ? 'border-color: #F99D1C;' : '' }}">
                            <img src="{{ $activeMenu == 'form' ? asset('icons/solid/Document.svg') : asset('icons/light/Document.svg') }}"
                                alt="Form Pelaporan" class="mr-2 w-5">
                            Form Pelaporan
                        </a>
                        <a href="{{ route('mahasiswa.riwayat') }}"
                            class="flex items-center p-2 w-full {{ $activeMenu == 'riwayat' ? 'bg-blue-800 text-white border-r-4' : 'hover:bg-gray-100' }} rounded-none"
                            style="{{ $activeMenu == 'riwayat' ? 'border-color: #F99D1C;' : '' }}">
                            <img src="{{ $activeMenu == 'riwayat' ? asset('icons/solid/Document.svg') : asset('icons/light/Document.svg') }}"
                                alt="Riwayat Pelaporan" class="mr-2 w-5">
                            Riwayat Pelaporan
                        </a>
                    </div>
                </nav>
            </div>
        @elseif(Auth::user()->id_role == 3)
            {{-- Teknisi --}}
            <!-- Logo at the top of sidebar -->
            <div class="flex items-center justify-center h-16 bg-white">
                <a href="{{ route('teknisi.dashboard') }}">
                    <span class="text-lg font-bold text-black-800 flex items-center">
                        <img src="{{ asset('img/logo primer.svg') }}" alt="SIMPEL-POL Logo" class="h-6 mr-2">
                        SIMPEL-POL
                    </span>
                </a>
            </div>
            <div class="h-full overflow-y-auto">
                <nav class="p-4 space-y-4 text-sm text-gray-700">
                    <a href="{{ route('teknisi.dashboard') }}"
                        class="flex items-center p-2 w-full {{ $activeMenu == 'home' ? 'bg-blue-800 text-white border-r-4' : 'hover:bg-gray-100' }} rounded-none"
                        style="{{ $activeMenu == 'home' ? 'border-color: #F99D1C;' : '' }}">
                        <img src="{{ $activeMenu == 'home' ? asset('icons/solid/Home.svg') : asset('icons/light/Home.svg') }}"
                            alt="Dashboard Icon" class="mr-2 w-5">
                        Dashboard
                    </a>

                    <div>
                        <p class="mb-1 text-xs text-gray-500 uppercase">Laporan</p>
                        <a href="/teknisi/penugasan"
                            class="flex items-center p-2 w-full {{ $activeMenu == 'penugasan' ? 'bg-blue-800 text-white border-r-4' : 'hover:bg-gray-100' }} rounded-none"
                            style="{{ $activeMenu == 'penugasan' ? 'border-color: #F99D1C;' : '' }}">
                            <img src="{{ $activeMenu == 'penugasan' ? asset('icons/solid/Document.svg') : asset('icons/light/Document.svg') }}"
                                alt="Laporan Pending" class="mr-2 w-5">
                            Penugasan
                        </a>
                        <a href={{route('teknisi.perbaikan')}}
                            class="flex items-center p-2 w-full {{ $activeMenu == 'perbaikan' ? 'bg-blue-800 text-white border-r-4' : 'hover:bg-gray-100' }} rounded-none"
                            style="{{ $activeMenu == 'perbaikan' ? 'border-color: #F99D1C;' : '' }}">
                            <img src="{{ $activeMenu == 'perbaikan' ? asset('icons/solid/Document.svg') : asset('icons/light/Document.svg') }}"
                                alt="Laporan Proses" class="mr-2 w-5">
                            Perbaikan
                        </a>
                        <a href="/teknisi/riwayat"
                            class="flex items-center p-2 w-full {{ $activeMenu == 'riwayat' ? 'bg-blue-800 text-white border-r-4' : 'hover:bg-gray-100' }} rounded-none"
                            style="{{ $activeMenu == 'riwayat' ? 'border-color: #F99D1C;' : '' }}">
                            <img src="{{ $activeMenu == 'riwayat' ? asset('icons/solid/Document.svg') : asset('icons/light/Document.svg') }}"
                                alt="Laporan Selesai" class="mr-2 w-5">
                            Riwayat Laporan
                        </a>
                    </div>
                </nav>
            </div>
        @elseif(Auth::user()->id_role == 4)
            {{-- Sarpras --}}
            <!-- Logo at the top of sidebar -->
            <div class="flex items-center justify-center h-16 bg-white">
                <a href="{{ route('sarpras.dashboard') }}">
                    <span class="text-lg font-bold text-black-800 flex items-center">
                        <img src="{{ asset('img/logo primer.svg') }}" alt="SIMPEL-POL Logo" class="h-6 mr-2">
                        SIMPEL-POL
                    </span>
                </a>
            </div>
            <div class="h-full overflow-y-auto">
                <nav class="p-4 space-y-4 text-sm text-gray-700">
                    <a href="/sarpras"
                        class="flex items-center p-2 w-full {{ $activeMenu == 'home' ? 'bg-blue-800 text-white border-r-4' : 'hover:bg-gray-100' }} rounded-none"
                        style="{{ $activeMenu == 'home' ? 'border-color: #F99D1C;' : '' }}">
                        <img src="{{ $activeMenu == 'home' ? asset('icons/solid/Home.svg') : asset('icons/light/Home.svg') }}"
                            alt="Dashboard Icon" class="mr-2 w-5">
                        Dashboard
                    </a>

                    <div>
                        <p class="mb-1 text-xs text-gray-500 uppercase">Kelola</p>

                        <a href="{{ route('sarpras.bobot') }}"
                            class="flex items-center p-2 w-full {{ $activeMenu == 'bobot' ? 'bg-blue-800 text-white border-r-4' : 'hover:bg-gray-100' }} rounded-none"
                            style="{{ $activeMenu == 'bobot' ? 'border-color: #F99D1C;' : '' }}">
                            <img src="{{ $activeMenu == 'bobot' ? asset('icons/solid/Settings.svg') : asset('icons/light/Settings.svg') }}"
                                alt="Form Pelaporan" class="mr-2 w-5">
                            Bobot Prioritas Perbaikan
                        </a>

                        <a href="sarpras/pelaporan"
                            class="flex items-center p-2 w-full {{ $activeMenu == 'pelaporan' ? 'bg-blue-800 text-white border-r-4' : 'hover:bg-gray-100' }} rounded-none"
                            style="{{ $activeMenu == 'pelaporan' ? 'border-color: #F99D1C;' : '' }}">
                            <img src="{{ $activeMenu == 'pelaporan' ? asset('icons/solid/Document.svg') : asset('icons/light/Document.svg') }}"
                                alt="Form Pelaporan" class="mr-2 w-5">
                            Form Pelaporan
                        </a>
                    </div>
                    <div>
                        <p class="mb-1 text-xs text-gray-500 uppercase">Laporan</p>
                        <a href="{{ route('sarpras.pengaduan') }}"
                            class="flex items-center p-2 w-full {{ $activeMenu == 'pengaduan' ? 'bg-blue-800 text-white border-r-4' : 'hover:bg-gray-100' }} rounded-none"
                            style="{{ $activeMenu == 'pengaduan' ? 'border-color: #F99D1C;' : '' }}">
                            <img src="{{ $activeMenu == 'pengaduan' ? asset('icons/solid/Layers.svg') : asset('icons/light/Layers.svg') }}"
                                alt="Form Pelaporan" class="mr-2 w-5">
                            Pengaduan
                        </a>
                        <a href="{{ route('sarpras.penugasan') }}"
                            class="flex items-center p-2 w-full {{ $activeMenu == 'penugasan' ? 'bg-blue-800 text-white border-r-4' : 'hover:bg-gray-100' }} rounded-none"
                            style="{{ $activeMenu == 'penugasan' ? 'border-color: #F99D1C;' : '' }}">
                            <img src="{{ $activeMenu == 'penugasan' ? asset('icons/solid/Layers.svg') : asset('icons/light/Layers.svg') }}"
                                alt="Form Pelaporan" class="mr-2 w-5">
                            Penugasan
                        </a>
                        <a href="{{route('sarpras.perbaikan')}}"
                            class="flex items-center p-2 w-full {{ $activeMenu == 'perbaikan' ? 'bg-blue-800 text-white border-r-4' : 'hover:bg-gray-100' }} rounded-none"
                            style="{{ $activeMenu == 'perbaikan' ? 'border-color: #F99D1C;' : '' }}">
                            <img src="{{ $activeMenu == 'perbaikan' ? asset('icons/solid/Layers.svg') : asset('icons/light/Layers.svg') }}"
                                alt="Form Pelaporan" class="mr-2 w-5">
                            Perbaikan
                        </a>
                        <a href="/sarpras/riwayat"
                            class="flex items-center p-2 w-full {{ $activeMenu == 'riwayat' ? 'bg-blue-800 text-white border-r-4' : 'hover:bg-gray-100' }} rounded-none"
                            style="{{ $activeMenu == 'riwayat' ? 'border-color: #F99D1C;' : '' }}">
                            <img src="{{ $activeMenu == 'riwayat' ? asset('icons/solid/Layers.svg') : asset('icons/light/Layers.svg') }}"
                                alt="Form Pelaporan" class="mr-2 w-5">
                            Riwayat Laporan
                        </a>
                    </div>
                </nav>
            </div>
        @endif
    @endif
</div>