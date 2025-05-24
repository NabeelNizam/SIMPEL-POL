<!-- Navbar - After Sidebar -->
<header class="bg-white shadow-sm z-30 w-full">
    <div class="flex items-center justify-between h-16 px-4">
        <!-- Hamburger Toggle (Left Side) -->
        <button id="sidebarToggle"
            class="p-2 rounded-md text-gray-600 hover:bg-gray-100 focus:outline-none md:flex lg:flex">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- User Profile Dropdown (Right Side) -->
        <div class="flex items-center mr-6">
            <div class="relative">
                <button type="button"
                    class="flex items-center space-x-3 text-gray-700 hover:text-gray-900 focus:outline-none"
                    id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                    data-dropdown-placement="bottom">
                    <img class="w-8 h-8 rounded-full border border-gray-300"
                        src="https://ui-avatars.com/api/?name=Admin+User&background=random" alt="user photo">
                    <span class="hidden md:block text-sm font-medium">Admin User</span>
                </button>

                <!-- Dropdown menu -->
                <div class="z-50 hidden absolute right-0 mt-2 w-48 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow"
                    id="user-dropdown">
                    <div class="px-4 py-3">
                        <span class="block text-sm text-gray-900 font-medium">Admin User</span>
                        <span class="block text-sm text-gray-500 truncate">admin@simpel-pol.com</span>
                    </div>
                    <ul class="py-2" aria-labelledby="user-menu-button">
                        <li>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user mr-2"></i> Profil Saya
                            </a>
                        </li>
                        <li>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-cog mr-2"></i> Pengaturan
                            </a>
                        </li>
                        <li>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-key mr-2"></i> Ganti Password
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                @csrf
                                @method('POST')
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>