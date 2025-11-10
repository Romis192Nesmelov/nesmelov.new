<div class="tabbable">
    <ul class="nav nav-tabs nav-tabs-solid nav-tabs-component nav-justified">
        @foreach($tabs as $k => $tab)
            <li {{ !$k ? 'class=active' : '' }}><a href="#tab{{ $k+1 }}" data-toggle="tab" {{ !$k ? 'aria-expanded=true' : 'aria-expanded=false' }}>{{ $tab['name'] }}</a></li>
        @endforeach
    </ul>

    <div class="tab-content">
        @foreach($tabs as $k => $tab)
            <div class="tab-pane {{ !$k ? 'active' : '' }}" id="tab{{ $k+1 }}">
                {!! $tab['content'] !!}
            </div>
        @endforeach
    </div>
</div>