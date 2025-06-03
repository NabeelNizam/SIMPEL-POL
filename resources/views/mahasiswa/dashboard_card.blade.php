@forelse($aduan as $item)
    <div class="bg-gray-50 rounded-lg p-6 mb-6">
        <div class="flex gap-6">
            <!-- gambar -->
            <div class="flex-shrink-0">
                @if($item->bukti_foto)
                    <img src="{{ asset('storage/' . $item->bukti_foto) }}" alt="Foto Aduan"
                        class="w-32 h-24 object-cover rounded-lg shadow">
                @else
                    <img src="{{ asset('img/no-image.svg') }}" alt="No Image"
                        class="w-32 h-24 object-cover rounded-lg shadow bg-gray-200">
                @endif
            </div>

            <!-- info -->
            <div class="flex-1">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h4 class="font-semibold text-gray-800 text-lg mb-1">
                            {{ $item->fasilitas->nama_fasilitas ?? 'Proyektor Epson' }}
                        </h4>
                        <p class="text-sm text-gray-600">
                            {{ $item->fasilitas->lokasi ?? 'Gedung Sipil, LT.7, Lobby' }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-medium text-gray-800">
                            {{ $item->tanggal_aduan ? \Carbon\Carbon::parse($item->tanggal_aduan)->format('d/m/Y') : '07/05/2025' }}
                        </span>
                    </div>
                </div>

                <!-- Status Stepper -->
                <div class="mt-4">
                    @php
                        $currentStatus = is_object($item->status) ? $item->status->value : ($item->status ?? 'MENUNGGU_DIPROSES');
                        $statuses = [
                            'MENUNGGU_DIPROSES' => ['label' => 'Menunggu Diproses', 'step' => 1],
                            'SEDANG_INSPEKSI' => ['label' => 'Sedang Inspeksi', 'step' => 2],
                            'SEDANG_DIPERBAIKI' => ['label' => 'Sedang Diperbaiki', 'step' => 3],
                            'SELESAI' => ['label' => 'Selesai', 'step' => 4]
                        ];
                        $currentStep = $statuses[$currentStatus]['step'] ?? 1;
                    @endphp

                    <ol class="flex items-center w-full">
                        <!-- Step 1: Menunggu Diproses -->
                        <li
                            class="flex w-full items-center {{ $currentStep >= 1 ? 'text-blue-600' : 'text-gray-500' }} after:content-[''] after:w-full after:h-1 after:border-b after:{{ $currentStep >= 2 ? 'border-blue-100' : 'border-gray-100' }} after:border-4 after:inline-block">
                            <span
                                class="flex items-center justify-center w-8 h-8 {{ $currentStep >= 1 ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-500' }} rounded-full shrink-0">
                                @if($currentStep > 1)
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 16 12">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                                    </svg>
                                @else
                                    <span class="text-xs font-medium">1</span>
                                @endif
                            </span>
                            <span class="ml-2 text-xs font-medium hidden sm:block">Menunggu
                                Diproses</span>
                        </li>

                        <!-- Step 2: Sedang Inspeksi -->
                        <li
                            class="flex w-full items-center {{ $currentStep >= 2 ? 'text-orange-500' : 'text-gray-500' }} after:content-[''] after:w-full after:h-1 after:border-b after:{{ $currentStep >= 3 ? 'border-orange-100' : 'border-gray-100' }} after:border-4 after:inline-block">
                            <span
                                class="flex items-center justify-center w-8 h-8 {{ $currentStep >= 2 ? 'bg-orange-100 text-orange-500' : 'bg-gray-100 text-gray-500' }} rounded-full shrink-0">
                                @if($currentStep > 2)
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 16 12">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                                    </svg>
                                @else
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                    </svg>
                                @endif
                            </span>
                            <span class="ml-2 text-xs font-medium hidden sm:block">Sedang
                                Inspeksi</span>
                        </li>

                        <!-- Step 3: Sedang Diperbaiki -->
                        <li
                            class="flex w-full items-center {{ $currentStep >= 3 ? 'text-yellow-500' : 'text-gray-500' }} after:content-[''] after:w-full after:h-1 after:border-b after:{{ $currentStep >= 4 ? 'border-yellow-100' : 'border-gray-100' }} after:border-4 after:inline-block">
                            <span
                                class="flex items-center justify-center w-8 h-8 {{ $currentStep >= 3 ? 'bg-yellow-100 text-yellow-500' : 'bg-gray-100 text-gray-500' }} rounded-full shrink-0">
                                @if($currentStep > 3)
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 16 12">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                                    </svg>
                                @else
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.98 2.98 0 0 0 .13 5H5Z" />
                                        <path
                                            d="M14.066 0H7v5a2 2 0 0 1-2 2H0v11a1.97 1.97 0 0 0 1.934 2h12.132A1.97 1.97 0 0 0 16 18V2a1.97 1.97 0 0 0-1.934-2ZM9 13a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2Zm4 .382a1 1 0 0 1-1.447.894L10 13v-2l1.553-1.276a1 1 0 0 1 1.447.894v2.764Z" />
                                    </svg>
                                @endif
                            </span>
                            <span class="ml-2 text-xs font-medium hidden sm:block">Sedang
                                Diperbaiki</span>
                        </li>

                        <!-- Step 4: Selesai -->
                        <li class="flex items-center {{ $currentStep >= 4 ? 'text-green-600' : 'text-gray-500' }}">
                            <span
                                class="flex items-center justify-center w-8 h-8 {{ $currentStep >= 4 ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500' }} rounded-full shrink-0">
                                @if($currentStep >= 4)
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 16 12">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                                    </svg>
                                @else
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="currentColor" viewBox="0 0 18 20">
                                        <path
                                            d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2ZM7 2h4v3H7V2Zm5.7 8.289-3.975 3.857a1 1 0 0 1-1.393 0L5.3 12.182a1.002 1.002 0 1 1 1.4-1.436l1.328 1.289 3.28-3.181a1 1 0 1 1 1.392 1.435Z" />
                                    </svg>
                                @endif
                            </span>
                            <span class="ml-2 text-xs font-medium hidden sm:block">Selesai</span>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    @if(!$loop->last)
        <hr class="my-6 border-gray-200">
    @endif

    @empty
    <div class="bg-gray-50 rounded-lg p-6 mb-6">
        <p class="text-gray-500 text-center">Tidak ada aduan yang tersedia.</p>
    </div>
@endforelse
 
<div class="mt-4">
    {{ $aduan->links() }}
</div>