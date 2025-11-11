@extends('layouts.main')

@section('content')
    @include('blocks._video_block')

    <x-gray_block head="{{ __('Main areas of professional activity') }}">
        @foreach($data['branches'] as $k => $branch)
            @include('blocks._big_icon_block', [
                'icon' => $branch->icon,
                'href' => $branch->eng,
                'subscribe' => $branch->description
            ])
        @endforeach
    </x-gray_block>

    @foreach($data['branches'] as $k => $branch)
        <a name="{{ $branch->eng }}"></a>
        <div class="image-block" style="background-image: url('{{ asset('/storage/'.$branch->image) }}')"></div>

        @if (count($branch->works))
            <x-gray_block head="{{ __('Works from the portfolio').' «'.$branch->rus.'»' }}" scroll="{{ $branch->eng }}">
                <div class="portfolio">
                    @foreach($branch->works as $k => $work)
                        @if ($work->active)
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
                                <a href="{{ $work->url ?? asset('storage/'.$work->full) }}" {{ $work->url ? 'target=_blank' : 'class=img-preview' }} title="{{ $work->description }}"><img src="{{ asset('storage/'.$work->preview) }}" /></a>
                            </div>
                        @endif
                    @endforeach
                </div>
            </x-gray_block>
        @endif
    @endforeach
@endsection
