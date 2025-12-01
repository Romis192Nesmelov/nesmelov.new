@extends('layouts.mail')

@section('content')
    <h3>{{ __('You are registered in the system') }} @include('emails.blocks._system_href_block')</h3>
    <h4>{{ __('Your password:').' '.$fields['password'] }}</h4>
    <p>{{ __('You can change the password in the «User Profile» section after authorization.') }}</p>
    @include('emails.blocks._if_you_dont_do_that_block')
@endsection
