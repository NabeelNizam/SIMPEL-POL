<!-- Header HTML -->
<header class="bg-white shadow-sm z-30 w-full">
    <div class="flex items-center justify-between h-16 px-4">
        <!-- Hamburger Toggle (Left Side) -->
        <button id="sidebarToggle"
            class="p-2 rounded-md text-gray-600 hover:bg-gray-100 focus:outline-none md:flex lg:flex cursor-pointer">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- User Profile and Notification Dropdowns (Right Side) -->
        <div class="flex items-center mr-6">
            <!-- Notification Dropdown -->
            <div class="relative">
                <button type="button" class="p-2 rounded-md text-gray-600 hover:bg-gray-200 focus:outline-none cursor-pointer relative"
                        id="notifikasi-dropdown-button" aria-expanded="false" data-dropdown-toggle="notifikasi-dropdown"
                        data-dropdown-placement="bottom">
                    <img src="{{ asset('icons/light/Bell.svg') }}" alt="notification-icon" class="w-8 h-8">
                    @if($unreadCount > 0)
                        <div class="absolute top-0.5 right-0.5 w-4 h-4 text-xs text-white bg-red-500 rounded-full flex items-center justify-center font-bold" id="unread-count">
                            {{ $unreadCount }}
                        </div>
                    @endif
                </button>

                <!-- Notification Dropdown Menu -->
                <div id="notifikasi-dropdown" class="z-50 hidden absolute right-0 mt-2 w-[380px] translate-x-[-50px] translate-y-[4px] text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow-lg border border-gray-300">
                    <div class="px-4 py-3 relative flex justify-between items-center">
                        <div class="w-full flex items-center justify-between align-middle">
                            <span class="py-2 block text-md text-gray-900 font-medium">Notifikasi</span>
                            @if ($unreadCount > 0)
                            <div class="px-4 py-2">
                                <a href="#" id="mark-all-read" class="text-sm font-normal text-blue-500 hover:text-blue-700">Baca Semua</a>
                            </div>
                            @endif
                        </div>
                        <button id="notifikasi-close-button" class="text-gray-500 hover:text-gray-700 cursor-pointer focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="w-[338px] h-1 bg-orange-400 mx-3 rounded"></div>
                    <ul class="py-2 max-h-[400px] overflow-y-auto" id="notification-list" aria-labelledby="notifikasi-dropdown-button">
                        @forelse ($notifikasi as $notif)
                            <li data-notif-id="{{ $notif->id_notifikasi }}">
                                <div class="px-3 py-1">
                                    <div class="block px-3 py-2 text-sm text-black rounded-lg {{ !$notif->is_read ? 'bg-[#BAE2FD] hover:bg-[#A6D7F7]' : 'bg-gray-100 hover:bg-gray-200' }} relative notification-item">
                                        <p class="break-words max-w-[280px]">{!! $notif->pesan !!}</p>
                                        <div class="flex justify-between items-center mt-1.5">
                                            <span class="text-xs text-gray-600 block">{{ \Carbon\Carbon::parse($notif->waktu_kirim)->format('d/m/Y') }}</span>
                                            <span class="text-xs text-gray-600 block">{{ \Carbon\Carbon::parse($notif->waktu_kirim)->format('H:i') }}</span>
                                        </div>
                                        <div class="absolute top-2 right-2">
                                            <button type="button" class="px-1 text-black focus:outline-none cursor-pointer options-button" data-notif-id="{{ $notif->id_notifikasi }}">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </button>
                                            <div id="options-dropdown-{{ $notif->id_notifikasi }}" class="hidden absolute -mt-2 right-0 w-[180px] bg-white border border-gray-200 rounded-md shadow-lg z-[60]">
                                                @if(!$notif->is_read)
                                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center mark-read" data-notif-id="{{ $notif->id_notifikasi }}">
                                                        <i class="fas fa-check-circle mr-2 text-blue-500"></i> Tandai sebagai Baca
                                                    </a>
                                                @endif
                                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center delete-notif" data-notif-id="{{ $notif->id_notifikasi }}">
                                                    <i class="fas fa-trash-alt mr-2 text-red-500"></i> Hapus
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li>
                                <div class="p-1">
                                    <p class="block px-4 py-2 text-sm text-gray-700 rounded-md">
                                        Tidak ada notifikasi
                                    </p>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- User Profile Dropdown -->
            <div class="relative ml-4">
                <button type="button"
                    class="flex items-center space-x-3 hover:bg-gray-200 p-2 rounded-md text-gray-700 hover:text-gray-900 focus:outline-none cursor-pointer"
                    id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                    data-dropdown-placement="bottom">
                    <img class="w-8 h-8 rounded-full border border-gray-300"
                        src="{{ asset( (auth()->user()->foto_profil ? auth()->user()->foto_profil : 'img/profiles.svg')) }}" alt="user photo">
                    <span class="hidden md:block text-sm font-medium">{{ auth()->user()->nama }}</span>
                </button>

                <!-- User Dropdown Menu -->
                <div class="z-50 hidden absolute right-0 mt-2 w-48 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow"
                    id="user-dropdown">
                    <div class="px-4 py-3">
                        <span class="block text-sm text-gray-900 font-medium">{{ auth()->user()->nama }}</span>
                        <span class="block text-sm text-gray-500 truncate">{{ auth()->user()->email }}</span>
                    </div>
                    <div class="w-40 h-1 bg-orange-400 mx-4 rounded"></div>
                    <ul class="py-2" aria-labelledby="user-menu-button">
                        <li>
                            <a href="/profil"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user mr-2"></i> Lihat Profil
                            </a>
                        </li>
                        <li>
                            <button onclick="modalAction('{{ route('profil.edit_ajax') }}')" class="block w-full max-w-xs text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">
                                <i class="fas fa-edit mr-2"></i> Edit Profil
                            </button>
                        </li>
                        <li>
                            <button
                                onclick="modalAction('{{ route('profil.password') }}')"
                                class="block w-full max-w-xs text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">
                                <i class="fas fa-key mr-2"></i> Ubah Kata Sandi
                            </button>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                @csrf
                                @method('POST')
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 cursor-pointer">
                                    <i class="fas fa-sign-out-alt mr-2"></i> <span>Logout</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Ensure Flowbite is included -->
<script src="https://unpkg.com/flowbite@1.8.1/dist/flowbite.min.js"></script>

<!-- Custom JS -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const notifButton       = document.getElementById('notifikasi-dropdown-button');
  const notifDropdown     = document.getElementById('notifikasi-dropdown');
  const notifCloseButton  = document.getElementById('notifikasi-close-button');
  const notificationList  = document.getElementById('notification-list');
  const unreadCountElem   = document.getElementById('unread-count');
  const markAllReadBtn    = document.getElementById('mark-all-read');
  const userButton        = document.getElementById('user-menu-button');
  const userDropdown      = document.getElementById('user-dropdown');

  // Toggle dropdowns
  notifButton.addEventListener('click', e => { e.stopPropagation(); notifDropdown.classList.toggle('hidden'); });
  notifCloseButton.addEventListener('click', e => { e.stopPropagation(); notifDropdown.classList.add('hidden'); });
  userButton.addEventListener('click', e => { e.stopPropagation(); userDropdown.classList.toggle('hidden'); });
  document.addEventListener('click', e => {
    if (!notifButton.contains(e.target) && !notifDropdown.contains(e.target)) notifDropdown.classList.add('hidden');
    if (!userButton.contains(e.target) && !userDropdown.contains(e.target))     userDropdown.classList.add('hidden');
    document.querySelectorAll('[id^="options-dropdown-"]').forEach(d => d.classList.add('hidden'));
  });

  // Update notifications list & badge
  async function updateNotifications() {
    try {
      const res  = await fetch('{{ route('notifikasi.get') }}', {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
      });
      const { notifications, unreadCount } = await res.json();

      // Badge & “Baca Semua”
      if (unreadCountElem) {
        if (unreadCount > 0) {
          unreadCountElem.innerText = unreadCount;
          unreadCountElem.style.display = 'flex';
          markAllReadBtn.parentElement.style.display = 'block';
        } else {
          unreadCountElem.style.display = 'none';
          markAllReadBtn.parentElement.style.display = 'none';
        }
      }

      // Rebuild list
      notificationList.innerHTML = '';
      if (notifications.length === 0) {
        notificationList.innerHTML = `
          <li>
            <div class="p-1">
              <p class="px-4 py-2 text-sm text-gray-700 rounded-md">Tidak ada notifikasi</p>
            </div>
          </li>`;
      } else {
        notifications.forEach(notif => {
          const bgClass = notif.is_read ? 'bg-gray-100 hover:bg-gray-200' : 'bg-[#BAE2FD] hover:bg-[#A6D7F7]';
          notificationList.innerHTML += `
            <li data-notif-id="${notif.id_notifikasi}">
              <div class="px-3 py-1">
                <div class="px-3 py-2 text-sm text-black rounded-lg ${bgClass} relative">
                  <p class="break-words max-w-[280px]">${notif.pesan}</p>
                  <div class="flex justify-between items-center mt-1.5">
                    <span class="text-xs text-gray-600">${notif.waktu_kirim_formatted}</span>
                    <span class="text-xs text-gray-600">${notif.waktu_kirim_time}</span>
                  </div>
                  <div class="absolute top-2 right-2">
                    <button class="options-button px-1 focus:outline-none cursor-pointer" data-notif-id="${notif.id_notifikasi}">
                      <i class="fas fa-ellipsis-h"></i>
                    </button>
                    <div id="options-dropdown-${notif.id_notifikasi}"
                         class="hidden absolute -mt-2 right-0 w-[180px] bg-white border rounded-md shadow-lg z-60 border-gray-200">
                      ${!notif.is_read ? `
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center mark-read" data-notif-id="${notif.id_notifikasi}">
                          <i class="fas fa-check-circle mr-2 text-blue-500"></i> Tandai sebagai Baca
                        </a>` : ''}
                      <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center delete-notif" data-notif-id="${notif.id_notifikasi}">
                        <i class="fas fa-trash-alt mr-2 text-red-500"></i> Hapus
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </li>`;
        });
      }

      attachListeners();
    } catch (err) {
      console.error('Gagal memuat notifikasi:', err);
    }
  }

  // Event delegation untuk opsi, mark-read, delete
  function attachListeners() {
    notificationList.addEventListener('click', async e => {
      const optBtn = e.target.closest('.options-button');
      if (optBtn) {
        e.stopPropagation();
        const id = optBtn.dataset.notifId;
        document.querySelectorAll('[id^="options-dropdown-"]').forEach(d => d.classList.add('hidden'));
        document.getElementById(`options-dropdown-${id}`).classList.toggle('hidden');
        return;
      }
      const markBtn = e.target.closest('.mark-read');
      if (markBtn) {
        e.preventDefault(); e.stopPropagation();
        const id = markBtn.dataset.notifId;
        await fetch(`{{ url('notifikasi') }}/${id}/tandai_baca`, {
          method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
        await updateNotifications();
        notifDropdown.classList.remove('hidden');
        return;
      }
      const delBtn = e.target.closest('.delete-notif');
      if (delBtn) {
        e.preventDefault(); e.stopPropagation();
        const id = delBtn.dataset.notifId;
        await fetch(`{{ url('notifikasi') }}/${id}/hapus`, {
          method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
        await updateNotifications();
        notifDropdown.classList.remove('hidden');
      }
    });
  }

  // “Baca Semua”
  if (markAllReadBtn) {
    markAllReadBtn.addEventListener('click', async e => {
      e.preventDefault(); e.stopPropagation();
      await fetch('{{ route('notifikasi.tandai-baca-semua') }}', { method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
      });
      await updateNotifications();
      notifDropdown.classList.remove('hidden');
    });
  }

  // Inisialisasi
  updateNotifications();
});
</script>
