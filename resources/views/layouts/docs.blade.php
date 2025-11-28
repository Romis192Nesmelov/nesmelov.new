<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <script type="text/javascript" src="{{ asset('js/jquery.js') }}"></script>
    @vite([
        'resources/css/icons/icomoon/styles.css',
        'resources/css/docs.css',
        'resources/js/docs.js',
    ])
</head>
<body>

@yield('content')

</body>
</html>
