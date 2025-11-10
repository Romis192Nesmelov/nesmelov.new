@if (isset($dataScroll))
    <a name="{{ $dataScroll }}"></a>
@endif
<div class="image-block gray-block {{ isset($addClass) ? $addClass : '' }}" {{ isset($dataScroll) ? 'data-scroll='.$dataScroll : '' }}>
    <div class="container slide text-center">
        <h1>{{ $head }}</h1>
        {!! $content !!}
    </div>
</div>
