@if (count($data['years']) > 1)
    <div class="pull-left">
        @foreach($data['years'] as $year)
            <div class="year {{ $year == $data['year'] ? 'active' : '' }}">
                @if ($year != $data['year'])
                    <a href="/{{ preg_replace('/(\/[1,2]\d{3})$/','',request()->path()).'/'.$year }}">{{ $year }}</a>
                @else
                    {{ $year }}
                @endif
            </div>
        @endforeach
    </div>
@endif
