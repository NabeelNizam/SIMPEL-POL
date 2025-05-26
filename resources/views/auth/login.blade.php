@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md justify-center items-center">
        <h2 class="text-2xl font-bold mb-1">Login</h2>
        <div class="w-12 h-1 bg-orange-500 mb-6 rounded"></div>

        <form id="loginForm" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" id="username" name="username" placeholder="Username"
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" placeholder="Password"
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <button type="button" id="togglePassword" class="absolute right-3 top-2.5 text-gray-400 cursor-pointer hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" id="eyeClosed" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M3 3l3.59 3.59M17.71 17.71L21 21" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" id="eyeOpen" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
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

            <div id="loginAlert" class="text-sm text-center text-red-600 mt-2 hidden"></div>
        </form>

        <p class="mt-4 text-center text-sm">
            <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Belum punya akun?</a>
        </p>
    </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeOpen = document.getElementById('eyeOpen');
        const eyeClosed = document.getElementById('eyeClosed');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            eyeOpen.classList.toggle('hidden');
            eyeClosed.classList.toggle('hidden');
        });

        const loginForm = document.getElementById('loginForm');
        const alertBox = document.getElementById('loginAlert');

        loginForm.addEventListener('submit', function (e) {
            e.preventDefault();
            alertBox.classList.add('hidden');

            const formData = new FormData(loginForm);

            fetch("{{ route('login') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(async response => {
                const data = await response.json();

                if (data.status) {
                    window.location.href = data.redirect;
                } else {
                    alertBox.textContent = data.message || 'Login gagal.';
                    alertBox.classList.remove('hidden');
                }
            })
            .catch(() => {
                alertBox.textContent = 'Terjadi kesalahan sistem.';
                alertBox.classList.remove('hidden');
            });
        });
    });
</script>
@endpush
