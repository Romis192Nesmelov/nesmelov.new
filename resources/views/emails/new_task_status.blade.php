@extends('layouts.mail')

@section('content')
    <h3>{{ __('Has been changed your') }}
    @include('emails.blocks._task_or_sub_task_link_block',['fields' => $fields, 'case' => true])
    ({{ __('Customer').': '.$fields['customer'].' '.__('in the system') }}) @include('emails.blocks._system_href_block')</h3>
    <h4>{{ __('New status') }}: <b>«{{ $fields['status'] }}»</b></h4>
@endsection
