@props(['head', 'scroll' => null])

<div class="image-block gray-block" {!! $scroll ? 'data-scroll="'.$scroll.'"' : '' !!}">
    <div class="container slide text-center">
        <h1>{{ $head }}</h1>
        {{ $slot }}
    </div>
</div>
