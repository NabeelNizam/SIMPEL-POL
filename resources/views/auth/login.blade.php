@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md justify-center items-center">
        <h2 class="text-2xl font-bold mb-1">Login</h2>
        <div class="w-12 h-1 bg-orange-500 mb-6 rounded"></div>

        <form action="#" method="POST" class="space-y-4">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
            <input type="email" id="email" name="email" placeholder="Alamat Email"
                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <div class="relative">
                <input type="password" id="password" name="password" placeholder="Password"
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                <button type="button" id="togglePassword" class="absolute right-3 top-2.5 text-gray-400 cursor-pointer transition-all duration-200 hover:text-gray-600">
                    <!-- Icon mata tertutup (default) -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" id="eyeClosed" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                    <!-- Icon mata terbuka (saat toggle) -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" id="eyeOpen" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <label class="inline-flex items-center">
            <input type="checkbox" class="form-checkbox" />
            <span class="ml-2 text-sm text-gray-700">Keep me sign in</span>
            </label>
            <a href="#" class="text-sm text-gray-500 hover:underline">Forgot Password?</a>
        </div>

        <button type="submit"
                class="w-full bg-blue-900 hover:bg-blue-800 text-white font-semibold py-2 rounded-md">
            Login
        </button>
        </form>

        <p class="mt-4 text-center text-sm">
        <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Belum punya akun?</a>
        </p>
    </div>
</div>
   
    @endsection

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClosed = document.getElementById('eyeClosed');
    
            togglePassword.addEventListener('click', function() {
                // Toggle jenis input antara "password" dan "text"
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Toggle tampilan ikon
                eyeOpen.classList.toggle('hidden');
                eyeClosed.classList.toggle('hidden');
                
                // Animasi toggle
                togglePassword.classList.add('scale-110');
                setTimeout(() => {
                    togglePassword.classList.remove('scale-110');
                }, 100);
            });
        });
    </script>
    @endpush