<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nesmelov&Co. {{ __('Login') }}</title>
    @include('blocks._favicons_block')
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">

    @vite([
        'resources/css/auth.css',
        'resources/css/icons/icomoon/styles.css',
        'resources/js/app.js',
    ])
</head>

<body class="login-container bg-slate-800">

<!-- Page container -->
<div class="page-container">

    <!-- Page content -->
    <div class="page-content">

        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Content area -->
            <div class="content">

            <form method="POST" action="{{ route('auth.sign_in') }}">
                @csrf
                <div class="panel panel-body login-form">
                    <div class="text-center">
                        <img width="200" src="{{ asset('storage/images/logo_cir.jpg') }}" />
                        <h5 class="content-group-lg">{{ trans('auth.login_to_your_account') }} <small class="display-block">{!! trans('auth.login_head') !!}</small></h5>
                    </div>

                    @include('admin.blocks._input_block',['name' => 'email', 'type' => 'email', 'placeholder' => 'E-mail', 'icon' => 'glyphicon-envelope'])
                    @include('admin.blocks._input_block',['name' => 'password', 'type' => 'password', 'placeholder' => trans('auth.password'), 'icon' => 'icon-lock2'])

                    <div class="form-group login-options">
                        <div class="row">
                            @include('admin.blocks._checkbox_block', ['name' => 'remember', 'checked' => true, 'label' => trans('auth.remember_me')])
                        </div>
                    </div>

                    <div class="form-group">
                        @include('admin.blocks._button_block', ['type' => 'submit', 'mainClass' => 'bg-warning-400 btn-block', 'text' => trans('content.enter'), 'icon' => 'icon-circle-right2 position-right'])
                    </div>
                </div>
            </form>

            </div>
            <!-- /content area -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->

</div>
<!-- /page container -->

</body>
</html>
