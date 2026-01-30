@extends('layouts.main')

@section('content')
    @include('blocks._video_block')

    <x-gray_block head="{{ __('Main areas of professional activity') }}">
        @foreach($data['branches'] as $k => $branch)
            @include('blocks._big_icon_block', [
                'icon' => $branch->icon,
                'href' => $branch->slug,
                'subscribe' => $branch['description_'.app()->getLocale()]
            ])
        @endforeach
    </x-gray_block>

    @foreach($data['branches'] as $k => $branch)
        <a name="{{ $branch->slug }}"></a>
        <div class="image-block" style="background-image: url('{{ asset($branch->image) }}')"></div>

        @if (count($branch->activeWorks))
            <x-gray_block head="{{ __('Works from the portfolio').' «'.$branch[app()->getLocale()].'»' }}" scroll="{{ $branch->slug }}">
                <div class="portfolio">
                    @foreach($branch->activeWorks as $k => $work)
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
                            @if ($branch->id == 5 && $work->url)
                                <a class="hidden-xs" href="JavaScript:newWindow = window.open('/show-pdf/?id={{ $work->id }}','','top=100,left=100,toolbar=0,location=0,directories=0,status=0,menuBar=0,scrollBars=0,resizable=1,width=850,height=1000');newWindow.focus();" title="{{ $work['description_'.app()->getLocale()] }}">
                                    <img src="{{ asset($work->preview) }}" />
                                </a>
                                <a class="img-preview visible-xs" href="{{ asset($work->full) }}" title="{{ $work['description_'.app()->getLocale()] }}">
                                    <img src="{{ asset($work->preview) }}" />
                                </a>
                            @else
                                <a href="{{ $work->url ? : asset($work->full) }}" {{ $work->url ? 'target=_blank' : 'class=img-preview' }} title="{{ $work['description_'.app()->getLocale()] }}">
                                    <img src="{{ asset($work->preview) }}" />
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </x-gray_block>
        @endif
    @endforeach
@endsection
