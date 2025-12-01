@extends('layouts.mail')

@section('content')
    <h3>{{ __('Has been changed the time').' '.$fields['time_type'].' '.__('your') }}
    @include('emails.blocks._task_or_sub_task_link_block',['fields' => $fields, 'case' => true])
    ({{ __('Executor').' '.$fields['customer'].' '.__('in the system') }}) @include('emails.blocks._system_href_block').</h3>
    <h4>{{ __('New time') }}: <b>«{{ $fields['time'] }}»</b></h4>
@endsection
