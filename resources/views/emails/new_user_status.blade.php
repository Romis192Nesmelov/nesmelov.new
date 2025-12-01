@extends('layouts.mail')

@section('content')
    <h3>{{ __('Changing your status in the system') }} @include('emails.blocks._system_href_block')</h3>
    <h4>{{ __('New status').': '.$fields['status'] ? __('Administrator') : __('User') }}</h4>
    @include('emails.blocks._if_you_dont_do_that_block')
@endsection
