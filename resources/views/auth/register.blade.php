@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
  <!-- Form 1: Pilih Role -->
  <div id="form-step-1" class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md text-center">
    <h2 class="text-xl font-bold">Daftar Sebagai:</h2>
    <div class="w-12 h-1 bg-orange-500 mx-auto my-2 rounded"></div>
    
    <div class="grid grid-cols-2 gap-6 mt-6">
      <a href="#" class="user-role flex flex-col items-center space-y-2 p-2 rounded-lg border-2 border-transparent hover:border-gray-300 active:border-gray-400 focus:border-gray-400 focus:outline-none transition-all duration-200 hover:scale-105" data-role="sarpras">
        <img src="/img/sarpras2.svg" alt="Sarana Prasarana" class="w-40 h-40" />
      </a>
      <a href="#" class="user-role flex flex-col items-center space-y-2 p-2 rounded-lg border-2 border-transparent hover:border-gray-300 active:border-gray-400 focus:border-gray-400 focus:outline-none transition-all duration-200 hover:scale-105" data-role="teknisi">
        <img src="/img/teknisi.svg" alt="Teknisi" class="w-40 h-40" />
      </a>
      <a href="#" class="user-role flex flex-col items-center space-y-2 p-2 rounded-lg border-2 border-transparent hover:border-gray-300 active:border-gray-400 focus:border-gray-400 focus:outline-none transition-all duration-200 hover:scale-105" data-role="dosen">
        <img src="/img/dosentendik.svg" alt="Dosen / Tendik" class="w-40 h-40" />
      </a>
      <a href="#" class="user-role flex flex-col items-center space-y-2 p-2 rounded-lg border-2 border-transparent hover:border-gray-300 active:border-gray-400 focus:border-gray-400 focus:outline-none transition-all duration-200 hover:scale-105" data-role="mahasiswa">
        <img src="/img/mahasiswa.svg" alt="Mahasiswa" class="w-40 h-40" />
      </a>
    </div>
    
    <div class="mt-6 border-t pt-4">
      <div class="flex items-center justify-between text-left w-full">
        <a href="{{ route('login') }}" class="text-blue-600 hover:underline text-sm">Sudah punya akun?</a>
        <button id="next-step" class="inline-flex items-center justify-center bg-blue-900 text-white rounded-full w-8 h-8 hover:bg-blue-800 transition-colors duration-200">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Form 2: Detail Registrasi -->
  <div id="form-step-2" class="hidden bg-white p-8 rounded-xl shadow-lg w-full max-w-2xl">
    <div class="text-center mb-6">
      <h2 class="text-xl font-bold mb-1">Sign Up <span id="selected-role">sebagai Mahasiswa</span></h2>
      <div class="w-12 h-1 bg-orange-500 mx-auto my-2 rounded"></div>
    </div>

    <form action="#" method="POST">
      <input type="hidden" name="role" id="role-input" value="">
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
          <input type="text" name="name" placeholder="Nama Lengkap"
                 class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
          <input type="email" name="email" placeholder="Alamat Email"
                 class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
          <input type="text" name="username" placeholder="Username"
                 class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
          <input type="text" name="phone" placeholder="Nomor HP"
                 class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required />
        </div>

        <div class="role-field mahasiswa-field">
          <label class="block text-sm font-medium text-gray-700 mb-1">NIM</label>
          <input type="text" name="nim" placeholder="NIM"
                 class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        

        <div class="role-field dosen-field hidden">
          <label class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
          <input type="text" name="nip" placeholder="NIP"
                 class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <div class="role-field dosen-field hidden">
          <label class="block text-sm font-medium text-gray-700 mb-1">Fakultas</label>
          <input type="text" name="fakultas" placeholder="Fakultas"
                 class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <div class="relative">
              <input type="password" name="password" id="password" placeholder="Password"
                     class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required />
              <button type="button" id="togglePassword" class="absolute right-3 top-2.5 text-gray-400 cursor-pointer transition-all duration-200 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" id="eyeClosed" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" id="eyeOpen" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </button>
            </div>
          </div>
          
        <div class="role-field mahasiswa-field">
            <label class="block text-sm font-medium text-gray-700 mb-1">Jurusan</label>
            <input type="text" name="jurusan" placeholder="Jurusan"
                   class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
          </div>

       

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
          <div class="relative">
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Konfirmasi Password"
                   class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required />
            <button type="button" id="toggleConfirmPassword" class="absolute right-3 top-2.5 text-gray-400 cursor-pointer transition-all duration-200 hover:text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" id="eyeClosedConfirm" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
              </svg>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" id="eyeOpenConfirm" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <div class="mt-6 border-t pt-4">
        <div class="flex items-center justify-between text-left w-full">
          <button type="button" id="prev-step" class="inline-flex items-center justify-center bg-gray-300 text-gray-700 rounded-full w-8 h-8 hover:bg-gray-400 transition-colors duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
          </button>
          <button type="submit" class="inline-flex items-center justify-center bg-blue-900 text-white py-2 px-6 rounded-lg hover:bg-blue-800 transition-colors duration-200">
            Daftar
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

@push('js')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Elemen-elemen yang dibutuhkan
    const form1 = document.getElementById('form-step-1');
    const form2 = document.getElementById('form-step-2');
    const nextBtn = document.getElementById('next-step');
    const prevBtn = document.getElementById('prev-step');
    const roleOptions = document.querySelectorAll('.user-role');
    const selectedRoleDisplay = document.getElementById('selected-role');
    const roleInput = document.getElementById('role-input');
    
    let selectedRole = 'mahasiswa'; // Default role
    
    // Handle pilihan role
    roleOptions.forEach(option => {
      option.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Reset semua opsi
        roleOptions.forEach(opt => {
          opt.classList.remove('border-blue-500');
          opt.classList.add('border-transparent');
        });
        
        // Tandai opsi yang dipilih
        this.classList.remove('border-transparent');
        this.classList.add('border-blue-500');
        
        // Set role yang dipilih
        selectedRole = this.getAttribute('data-role');
      });
    });
    
    // Next button (dari form 1 ke form 2)
    nextBtn.addEventListener('click', function() {
      // Set judul form sesuai role yang dipilih
      let roleText = "Mahasiswa";
      if (selectedRole === "dosen") roleText = "Dosen / Tendik";
      if (selectedRole === "teknisi") roleText = "Teknisi";
      if (selectedRole === "sarpras") roleText = "Sarana Prasarana";
      
      selectedRoleDisplay.textContent = `sebagai ${roleText}`;
      roleInput.value = selectedRole;
      
      // Tampilkan field sesuai role
      document.querySelectorAll('.role-field').forEach(field => {
        field.classList.add('hidden');
      });
      
      if (selectedRole === 'mahasiswa') {
        document.querySelectorAll('.mahasiswa-field').forEach(field => {
          field.classList.remove('hidden');
        });
      } else if (selectedRole === 'dosen') {
        document.querySelectorAll('.dosen-field').forEach(field => {
          field.classList.remove('hidden');
        });
      }
      
      // Animasi transisi
      form1.classList.add('animate-fadeOut');
      setTimeout(() => {
        form1.classList.add('hidden');
        form2.classList.remove('hidden');
        form2.classList.add('animate-fadeIn');
      }, 300);
    });
    
    // Prev button (dari form 2 ke form 1)
    prevBtn.addEventListener('click', function() {
      form2.classList.add('animate-fadeOut');
      setTimeout(() => {
        form2.classList.add('hidden');
        form1.classList.remove('hidden');
        form1.classList.remove('animate-fadeOut');
        form1.classList.add('animate-fadeIn');
      }, 300);
    });

    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    const eyeOpen = document.getElementById('eyeOpen');
    const eyeClosed = document.getElementById('eyeClosed');
    
    togglePassword.addEventListener('click', function() {
      // Toggle type
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      
      // Toggle icon
      eyeOpen.classList.toggle('hidden');
      eyeClosed.classList.toggle('hidden');
    });

    // Toggle confirm password visibility
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const confirmPassword = document.getElementById('password_confirmation');
    const eyeOpenConfirm = document.getElementById('eyeOpenConfirm');
    const eyeClosedConfirm = document.getElementById('eyeClosedConfirm');
    
    toggleConfirmPassword.addEventListener('click', function() {
      // Toggle type
      const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
      confirmPassword.setAttribute('type', type);
      
      // Toggle icon
      eyeOpenConfirm.classList.toggle('hidden');
      eyeClosedConfirm.classList.toggle('hidden');
    });
  });
</script>
@endpush

<style>
  .animate-fadeIn {
    animation: fadeIn 0.3s ease-in-out forwards;
  }
  
  .animate-fadeOut {
    animation: fadeOut 0.3s ease-in-out forwards;
  }
  
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }
  
  @keyframes fadeOut {
    from { opacity: 1; transform: translateY(0); }
    to { opacity: 0; transform: translateY(-10px); }
  }
</style>
@endsection