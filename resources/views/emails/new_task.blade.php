@extends('layouts.mail')

@section('content')
    <h3>{{ __('Created new') }}
        @include('emails.blocks._task_or_sub_task_link_block',['fields' => $fields, 'case' => false])
        {{ __('in the system') }} @include('emails.blocks._system_href_block')</h3>
    <p>
        {{ __('Customer') }}: <b>{{ $fields['customer'] }}</b><br>
        {{ __('Task name') }}: <b>{{ $fields['name'] }}</b><br>
        {{ __('Contact e-mail') }}: <b>{{ $fields['email'] }}</b><br>
        {{ __('Contact phone') }}: <b>{{ $fields['phone'] }}</b><br>
        {{ __('Contact person') }}: <b>{{ $fields['contact_person'] }}</b><br>
        {{ __('Issue price') }}: <b>{{ $fields['value'] }}р.</b><br>

        @if (!isset($fields['parent_id']) || !$fields['parent_id'])
            {{ __('Prepayment') }}: <b>{{ $fields['paid_off'] }}р.</b><br>
            {{ __('Percentage to the performer') }}: <b>{{ $fields['percents'] }}%</b><br>
            {{ __('Responsible for execution') }}: <b>{{ $fields['owner'] }}</b><br>
            {{ __('Executor') }}: <b>{{ $fields['user'] }}</b><br>
        @endif

        {{ __('Status') }}: <b>«{{ $fields['status'] }}»</b><br>
        {{ __('Getting started') }}: <b>{{ date('d.m.Y',$fields['start_time']) }}</b><br>
        {{ __('Estimated completion time') }}: <b>{{ date('d.m.Y',$fields['completion_time']) }}</b>
    </p>
@endsection
