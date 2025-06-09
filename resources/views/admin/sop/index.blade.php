@extends('layouts.template')
@section('content')
    <!-- Box: Daftar SOP -->
    <div class="bg-white border border-blue-500 rounded-md mb-4 p-4">

        <div class="mb-4 p-4 flex items-center justify-between">
            <h2 class="text-gray-800 font-medium text-lg">Daftar SOP</h2>
            <button class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-1 rounded-md text-sm font-semibold">
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

    <!-- Tabs Content -->
    <div id="content-admin" class="tab-content {{ $selectedRole === 'admin' ? '' : 'hidden' }}">
        <div class="space-y-3">
            <div
                class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-gray-900">{{ $adminFile }}</div>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('sopDownload', ['role' => $selectedRole, 'filename' => 'SOP_' . strtoupper($selectedRole) . '.pdf']) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Unduh
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Content -->
    <div id="content-sarpras" class="tab-content {{ $selectedRole === 'sarpras' ? '' : 'hidden' }}">
        <div class="space-y-3">
            <div
                class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-gray-900">{{ $sarprasFile }}</div>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('sopDownload', ['role' => $selectedRole, 'filename' => 'SOP_' . strtoupper($selectedRole) . '.pdf']) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Unduh
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Tabs Content -->
    <div id="content-pelapor" class="tab-content {{ $selectedRole === 'pelapor' ? '' : 'hidden' }}">
        <div class="space-y-3">
            <div
                class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-gray-900">{{ $pelaporFile }}</div>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('sopDownload', ['role' => $selectedRole, 'filename' => 'SOP_' . strtoupper($selectedRole) . '.pdf']) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Unduh
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Content -->
    <div id="content-teknisi" class="tab-content {{ $selectedRole === 'teknisi' ? '' : 'hidden' }}">
        <div class="space-y-3">
            <div
                class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-gray-900">{{ $teknisiFile }}</div>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('sopDownload', ['role' => $selectedRole, 'filename' => 'SOP_' . strtoupper($selectedRole) . '.pdf']) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Unduh
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
    

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