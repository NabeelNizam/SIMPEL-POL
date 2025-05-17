<section class="mb-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-800 mb-1">{{ $breadcrumb->title }}</h1>
        <ol class="text-sm text-gray-600 flex gap-1">
            @foreach($breadcrumb->list as $key => $value)
                @if($key == count($breadcrumb->list) - 1)
                    <li class="text-gray-500">{{ $value }}</li>
                @else
                    <li>
                        {{ $value }} <span class="mx-1">></span>
                    </li>
                @endif
            @endforeach
        </ol>
    </div>
</section>
