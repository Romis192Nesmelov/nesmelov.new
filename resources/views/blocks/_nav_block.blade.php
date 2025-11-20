<nav class="navbar navbar-default navbar-static-top">
    <div class="navbar-header">
        <!-- Collapsed Hamburger -->
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
            <span class="sr-only">Toggle Navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <div class="collapse navbar-collapse pull-left" id="app-navbar-collapse">
        <!-- Left Side Of Navbar -->
        <ul class="nav navbar-nav dropdown {{ isset($data['chapter']) && $data['chapter']->id != 1 && $data['chapter']->id != 9 ? 'visible-xs' : '' }}">
            <li class="main-menu"><a href="#" data-scroll="home"><i class="icon icon-home5"></i></a></li>
            @foreach($mainMenu as $menu)
                @if ($menu['name'])
                    <li class="main-menu">
                        @if ($menu['href'])
                            <a href="#{{ $menu['href'] }}" data-scroll="{{ $menu['href'] }}">{{ $menu['name'] }}</a>
                        @else
                            <a>{{ $menu['name'] }}</a>
                        @endif

                        @if (isset($menu['submenu']))
                            <ul class="dropdown-menu">
                                @foreach($menu['submenu'] as $href => $name)
                                    <li><a href="{{ $href }}">{{ $name }}</a></li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</nav>
<div class="feedback"><a href="mailto:{{ env('MAIL_TO') }}">{{ __('Feedback') }}</a></div>
<div class="lang"><a href="{{ route('admin.change-lang',['lang' => app()->getLocale() == 'ru' ? 'en' : 'ru']) }}">{{ app()->getLocale() == 'ru' ? 'EN' : 'RU' }}</a></div>
{{--<div class="feedback"><a href="#feedback_modal" data-toggle="modal">Напишите нам!</a></div>--}}
