@extends('layouts.template')
@section('content')
    <!-- Box: Daftar SOP -->
    <div class="bg-white border border-blue-500 rounded-md mb-4 p-4">

        <div class="mb-4 p-4 flex items-center justify-between">
            <h2 class="text-gray-800 font-medium text-lg">Daftar SOP</h2>
            <button onclick="modalAction('{{ route('sop.edit') }}')" class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-1 rounded-md text-sm font-semibold">
                <i class="fa-solid fa-pen text-xs" style="color: #ffffff;"></i> Edit
            </button>
        </div>
        

    <div class="border-b border-gray-200 mb-6 mt-6">
    <form id="roleForm" method="GET" action="{{ route('admin.sop') }}">
        <nav class="-mb-px flex space-x-8">
            <button type="submit" name="selectedRole" value="admin"
                class="tab-button py-2 px-1 border-b-2 font-medium text-sm hover:border-gray-300
                {{ $selectedRole === 'admin' ? 'active border-orange-500 text-blue-600' : 'border-transparent text-gray-500' }}">
                Admin
            </button>
            <button type="submit" name="selectedRole" value="sarpras"
                class="tab-button py-2 px-1 border-b-2 font-medium text-sm hover:border-gray-300
                {{ $selectedRole === 'sarpras' ? 'active border-orange-500 text-blue-600' : 'border-transparent text-gray-500' }}">
                Sarpras
            </button>
            <button type="submit" name="selectedRole" value="pelapor"
                class="tab-button py-2 px-1 border-b-2 font-medium text-sm hover:border-gray-300
                {{ $selectedRole === 'pelapor' ? 'active border-orange-500 text-blue-600' : 'border-transparent text-gray-500' }}">
                Pelapor
            </button>
            <button type="submit" name="selectedRole" value="teknisi"
                class="tab-button py-2 px-1 border-b-2 font-medium text-sm hover:border-gray-300
                {{ $selectedRole === 'teknisi' ? 'active border-orange-500 text-blue-600' : 'border-transparent text-gray-500' }}">
                Teknisi
            </button>
        </nav>
    </form>
</div>

    <div id="content-admin" class="tab-content {{ $selectedRole === 'admin' ? '' : 'hidden' }}">
    <div class="space-y-3">
        <div
            class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    @if (!$adminFile)
                        <svg class="shrink-0 inline w-4 h-4 me-3 text-yellow-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                        </svg>
                    @endif
                </div>
                <div class="ml-3 flex items-center">
                    @if ($adminFile)
                        <svg class="w-8 h-8 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <div class="text-sm font-medium text-gray-900">
                            {{ $adminFile }}
                        </div>
                    @else
                        <span class="text-yellow-500">File SOP Admin tidak tersedia.</span>
                    @endif
                </div>
            </div>
            @if ($adminFile)
                <div class="flex-shrink-0">
                    <a href="{{ route('sopDownload', ['role' => 'admin', 'filename' => $adminFile]) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Unduh SOP Admin
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<div id="content-sarpras" class="tab-content {{ $selectedRole === 'sarpras' ? '' : 'hidden' }}">
    <div class="space-y-3">
        <div
            class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    @if (!$sarprasFile)
                        <svg class="shrink-0 inline w-4 h-4 me-3 text-yellow-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                        </svg>
                    @endif
                </div>
                <div class="ml-3 flex items-center">
                    @if ($sarprasFile)
                        <svg class="w-8 h-8 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <div class="text-sm font-medium text-gray-900">
                            {{ $sarprasFile }}
                        </div>
                    @else
                        <span class="text-yellow-500">File SOP Sarpras tidak tersedia.</span>
                    @endif
                </div>
            </div>
            @if ($sarprasFile)
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('sopDownload', ['role' => 'sarpras', 'filename' => $sarprasFile]) }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 ml-2">
                        Unduh SOP Sarpras
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
    <div id="content-pelapor" class="tab-content {{ $selectedRole === 'pelapor' ? '' : 'hidden' }}">
    <div class="space-y-3">
        <div
            class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    @if (!$pelaporFile)
                        <svg class="shrink-0 inline w-4 h-4 me-3 text-yellow-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                        </svg>
                    @endif
                </div>
                <div class="ml-3">
                    <div class="ml-3 flex items-center">
                        @if ($pelaporFile)
                            <svg class="w-8 h-8 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <div class="text-sm font-medium text-gray-900">
                                {{ $pelaporFile }}
                            </div>
                        @else
                            <span class="text-yellow-500">File SOP Admin tidak tersedia.</span>
                        @endif
                    </div>
                </div>
            </div>
            @if ($pelaporFile)
                <div class="flex-shrink-0">
                    <a href="{{ route('sopDownload', ['role' => 'pelapor', 'filename' => $pelaporFile]) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Unduh SOP Pelapor
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

  <div id="content-teknisi" class="tab-content {{ $selectedRole === 'teknisi' ? '' : 'hidden' }}">
    <div class="space-y-3">
        <div
            class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    @if (!$teknisiFile)
                        <svg class="shrink-0 inline w-4 h-4 me-3 text-yellow-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 1 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                        </svg>
                    @endif
                </div>
                <div class="ml-3">
                    <div class="ml-3 flex items-center">
                        @if ($teknisiFile)
                            <svg class="w-8 h-8 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <div class="text-sm font-medium text-gray-900">
                                {{ $teknisiFile }}
                            </div>
                        @else
                            <span class="text-yellow-500">File SOP Admin tidak tersedia.</span>
                        @endif
                    </div>
                </div>
            </div>
            @if ($teknisiFile)
                <div class="flex-shrink-0">
                    <a href="{{ route('sopDownload', ['role' => 'teknisi', 'filename' => $teknisiFile]) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Unduh SOP Teknisi
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
</div>
 <div id="myModal" class="fixed inset-0 z-50 hidden items-center justify-center backdrop-blur-sm bg-white/30">


@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tabId = this.id.replace('tab-', '');

                    // Reset all tabs
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active', 'border-orange-500', 'text-blue-600');
                        btn.classList.add('border-transparent', 'text-gray-500');
                    });

                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });

                    // Activate clicked tab
                    this.classList.add('active', 'border-orange-500', 'text-blue-600');
                    this.classList.remove('border-transparent', 'text-gray-500');

                    document.getElementById(`content-${tabId}`).classList.remove('hidden');
                });
            });
        });

         
    </script>
@endpush