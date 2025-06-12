<div class="max-h-96 overflow-y-auto space-y-6">
    @forelse($aduan as $item)
        <div class="bg-gray-50 rounded-lg p-6 mb-6">
            <div class="flex gap-6">
                <!-- gambar -->
                <div class="flex-shrink-0">
                    @if(!empty($item->fasilitas->foto_fasilitas) && file_exists(public_path($item->fasilitas->foto_fasilitas)))
                        <img src="{{ asset('storage/' . $item->fasilitas->foto_fasilitas) }}" alt="Foto Fasilitas"
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
                                class="flex w-full items-center {{ $currentStep >= 1 ? 'text-blue-800' : 'text-gray-500' }} after:content-[''] after:w-full after:h-1 after:border-b after:{{ $currentStep >= 2 ? 'border-blue-100' : 'border-gray-100' }} after:border-4 after:inline-block">
                                <span
                                    class="flex items-center justify-center w-8 h-8 {{ $currentStep >= 1 ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-500' }} rounded-full shrink-0">
                                    @if($currentStep > 1)
                                        <img src={{ asset('icons/solid/Done.svg') }} alt="Menunggu Diproses" class="w-4 h-4 ">
                                    @else
                                        <img src={{ asset('icons/light/TimeCircle.svg') }} alt="Menunggu Diproses" class="w-4 h-4">
                                    @endif
                                </span>
                                <span class="ml-2 text-xs font-medium hidden sm:block">Menunggu Diproses</span>
                            </li>

                            <!-- Step 2: Sedang Inspeksi -->
                            <li
                                class="flex w-full items-center {{ $currentStep >= 2 ? 'text-yellow-500' : 'text-gray-500' }} after:content-[''] after:w-full after:h-1 after:border-b after:{{ $currentStep >= 3 ? 'border-orange-100' : 'border-gray-100' }} after:border-4 after:inline-block">
                                <span
                                    class="flex items-center justify-center w-8 h-8 {{ $currentStep >= 2 ? 'bg-yellow-100 text-yellow-500' : 'bg-gray-100 text-gray-500' }} rounded-full shrink-0">
                                    @if($currentStep > 2)
                                        <img src={{ asset('icons/solid/Done.svg') }} alt="Sedang Inspeksi" class="w-4 h-4">
                                    @else
                                    <img src="{{ asset('icons/solid/Info.svg') }}" alt="Sedang Inspeksi" class="w-4 h-4 {{ $currentStep >= 2 ? '' : 'grayscale brightness-75' }}">
                                    @endif
                                </span>
                                <span class="ml-2 text-xs font-medium hidden sm:block">Sedang Inspeksi</span>
                            </li>

                            <!-- Step 3: Sedang Diperbaiki -->
                            <li
                                class="flex w-full items-center {{ $currentStep >= 3 ? 'text-orange-500' : 'text-gray-500' }} after:content-[''] after:w-full after:h-1 after:border-b after:{{ $currentStep >= 4 ? 'border-green-100' : 'border-gray-100' }} after:border-4 after:inline-block">
                                <span
                                    class="flex items-center justify-center w-8 h-8 {{ $currentStep >= 3 ? 'bg-orange-100 text-orange-500' : 'bg-gray-100 text-gray-500' }} rounded-full shrink-0">
                                    @if($currentStep > 3)
                                        <img src={{ asset('icons/solid/Done.svg') }} alt="Sedang Diperbaiki" class="w-4 h-4">
                                    @else
                                        <img src={{ asset('icons/solid/Progress.svg') }} alt="Sedang Diperbaiki" class="w-4 h-4 {{ $currentStep >= 3 ? '' : 'grayscale brightness-75' }}">
                                    @endif
                                </span>
                                <span class="ml-2 text-xs font-medium hidden sm:block">Sedang Diperbaiki</span>
                            </li>

                            <!-- Step 4: Selesai -->
                            <li class="flex items-center {{ $currentStep >= 4 ? 'text-green-600' : 'text-gray-500' }}">
                                <span
                                    class="flex items-center justify-center w-8 h-8 {{ $currentStep >= 4 ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500' }} rounded-full shrink-0">
                                    @if($currentStep >= 4)
                                        <img src={{ asset('icons/solid/Done.svg') }} alt="Selesai" class="w-4 h-4 " >
                                    @else
                                        <img src={{ asset('icons/solid/Check.svg') }} alt="Selesai" class="w-4 h-4 {{ $currentStep >= 4 ? '' : 'grayscale brightness-75' }}"">
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

</div>