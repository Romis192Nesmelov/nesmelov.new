@extends('layouts.mail')

@section('content')
    <h3>{{ __('Your E-mail address is listed as a login in the system') }} @include('emails.blocks._system_href_block')</h3>
    @include('emails.blocks._if_you_dont_do_that_block')
@endsection
