<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    // Untuk mengirimkan token Laravel CSRF pada setiap request ajax
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.querySelector('.main-content');
        const sidebarToggle = document.getElementById('sidebarToggle');
        
        // Variabel untuk melacak status sidebar
        let isSidebarOpen = true; // Default sidebar terbuka
        
        // Fungsi untuk membuka sidebar
        function openSidebar() {
            sidebar.style.transform = 'translateX(0)';
            mainContent.style.marginLeft = '16rem';
            isSidebarOpen = true;
        }
        
        // Fungsi untuk menutup sidebar
        function closeSidebar() {
            sidebar.style.transform = 'translateX(-100%)';
            mainContent.style.marginLeft = '0';
            isSidebarOpen = false;
        }
        
        // Default: sidebar terbuka saat halaman dimuat
        openSidebar();
        
        // Toggle sidebar saat hamburger menu diklik
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (isSidebarOpen) {
                closeSidebar();
            } else {
                openSidebar();
            }
            
            // Pada layar kecil, tambahkan overlay ketika sidebar terbuka
            const overlay = document.getElementById('sidebar-overlay');
            if (window.innerWidth < 768) {
                if (!isSidebarOpen) { // Sidebar akan dibuka
                    if (!overlay) {
                        const newOverlay = document.createElement('div');
                        newOverlay.id = 'sidebar-overlay';
                        newOverlay.className = 'fixed inset-0 bg-gray-900 bg-opacity-50 z-30 md:hidden';
                        document.body.appendChild(newOverlay);
                        
                        newOverlay.addEventListener('click', function() {
                            closeSidebar();
                            this.remove();
                        });
                    }
                } else { // Sidebar akan ditutup
                    if (overlay) overlay.remove();
                }
            }
        });
        
        // Responsive handling untuk perubahan ukuran layar
        window.addEventListener('resize', function() {
            // Jika layar kecil dan sidebar terbuka, tutup sidebar dan tambahkan overlay
            if (window.innerWidth < 768 && isSidebarOpen) {
                closeSidebar();
                
                // Bersihkan overlay yang mungkin ada
                const overlay = document.getElementById('sidebar-overlay');
                if (!overlay) {
                    const newOverlay = document.createElement('div');
                    newOverlay.id = 'sidebar-overlay';
                    newOverlay.className = 'fixed inset-0 bg-gray-900 bg-opacity-50 z-30 md:hidden';
                    document.body.appendChild(newOverlay);
                    
                    newOverlay.addEventListener('click', function() {
                        closeSidebar();
                        this.remove();
                    });
                }
            } 
            // Jika layar besar, buka sidebar secara default dan hapus overlay
            else if (window.innerWidth >= 768) {
                const overlay = document.getElementById('sidebar-overlay');
                if (overlay) overlay.remove();
                
                // Pada desktop, kembalikan ke status terakhir
                if (isSidebarOpen) {
                    openSidebar();
                } else {
                    closeSidebar();
                }
            }
        });
        
        // Handle user dropdown manually
        const userMenuButton = document.getElementById('user-menu-button');
        const userDropdown = document.getElementById('user-dropdown');
        
        if (userMenuButton && userDropdown) {
            userMenuButton.addEventListener('click', function() {
                userDropdown.classList.toggle('hidden');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
                    userDropdown.classList.add('hidden');
                }
            });
        }
    });
</script>