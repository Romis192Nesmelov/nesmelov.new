@extends('layouts.mail')

@section('content')
    <h3>{{ __('The time').' '.$fields['time_type'] }}
        @include('emails.blocks._task_or_sub_task_link_block',['fields' => $fields, 'case' => true])
        {{ __('in the system') }} @include('emails.blocks._system_href_block') {{ $fields['time_status'] }}.</h3>
@endsection
