<nav aria-label="breadcrumb">
    <ol class="flex space-x-2">
        <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Home</a></li>
        @foreach ($crumbs as $crumb)
            <li><span class="text-gray-500">/</span></li>
            @if ($loop->last)
                <li aria-current="page" class="text-gray-700">{{ $crumb['label'] }}</li>
            @else
                <li><a href="{{ $crumb['url'] }}" class="text-gray-500 hover:text-gray-700">{{ $crumb['label'] }}</a></li>
            @endif
        @endforeach
    </ol>
</nav>
<br>

