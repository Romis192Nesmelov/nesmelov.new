@extends('layouts.mail')

@section('content')
    <h1>{{ __('Database backup from the system') }} @include('emails.blocks._system_href_block')</h1>
@endsection
