<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
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

    <!-- Scripts -->
    @include('layouts.scripts')

    @stack('js')
</body>
</html>