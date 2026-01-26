<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ getSeoTags()['title'] }}</title>
    @include('blocks._favicons_block')

    @foreach(getMetas() as $meta => $params)
        @if (getSeoTags()[$meta])
            <meta {{ $params['name'] ? 'name='.$params['name'] : 'property='.$params['property'] }} content="{{ getSeoTags()[$meta] }}">
        @endif
    @endforeach

    <script type="text/javascript" src="{{ asset('js/jquery.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap.js') }}"></script>
    <link href="https://fonts.googleapis.com/css?family=Jura&display=swap" rel="stylesheet">
    @vite([
        'resources/css/icons/icomoon/styles.css',
        'resources/css/app.css',
        'resources/js/fancybox.min.js',
        'resources/js/jquery.easing.js',
        'resources/js/app.js',
    ])
<body>

@include('blocks._nav_block')
@yield('content')

<footer>
    <div class="container text-center">
        <div class="logo"><img src="{{ asset('storage/images/logo.jpg') }}"></div>
        <p class="small">{{ '© '.getSeoTags()['title'].' '.date('Y') }}г.<br>{{ getSeoTags()['meta_description'] }}</p>
    </div>
</footer>

<div id="on_top_button"><i class="glyphicon glyphicon-upload"></i></div>
</body>
</html>
