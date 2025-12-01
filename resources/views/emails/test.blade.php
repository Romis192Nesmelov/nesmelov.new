@extends('layouts.mail')

@section('content')
    <h1>{{ __('A test message.') }} @include('emails.blocks._system_href_block')</h1>
@endsection
