<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="yandex-verification" content="e4261c36119b5851" />
    <meta name="google-site-verification" content="gzzatjygFT8h71fZtOvRF0DweX34Da8kJIEC5mrJSEk" />

    <title>{{ getSeoTags()['title'] }}</title>
    @include('blocks._favicons_block')

    @foreach(getMetas() as $meta => $params)
        @if (getSeoTags()[$meta])
            <meta {{ $params['name'] ? 'name='.$params['name'] : 'property='.$params['property'] }} content="{{ getSeoTags()[$meta] }}">
        @endif
    @endforeach

    <link href="https://fonts.googleapis.com/css?family=Jura&display=swap" rel="stylesheet">
    @vite([
        'resources/css/icons/icomoon/styles.css',
        'resources/css/app.css',
        'resources/js/app.js',
    ])
<body>

@include('blocks._nav_block')
@yield('content')

<footer>
    <div class="container text-center">
        <div class="logo"><img src="{{ asset('storage/images/logo.jpg') }}"></div>
        <p class="small">©Несмелов.рф {{ date('Y') }}г.<br>{{ getSeoTags()['meta_description'] }}</p>
    </div>
</footer>

<div id="on_top_button"><i class="glyphicon glyphicon-upload"></i></div>
</body>
</html>
