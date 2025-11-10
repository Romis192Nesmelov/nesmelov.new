<a name="{{ $branch->eng }}"></a>
<div class="image-block" style="background-image: url('{{ asset($branch->image) }}')" data-scroll="{{ $branch->eng }}">
    <div class="container slide">
        @if (Settings::getSettings()['show_text'])
            <div class="gray-jaw gray-jaw col-md-8 col-sm-8 col-xs-12 {{ isset($addClass) && $addClass ? $addClass : '' }}">
                <h1>{{ $branch->rus }}</h1>
                {!! $branch->text !!}
                @if ($next)
                    <p class="text-right"><a href="#{{ $next }}" data-scroll="{{ $next }}">Следующий <i class="icon-next"></i></a></p>
                @endif
            </div>
        @endif
    </div>
</div>
