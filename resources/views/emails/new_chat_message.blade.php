@extends('layouts.mail')

@section('content')
    <h3>{{ __('New message in the chat') }} <a href="{{ url('/admin/tasks?id='.$fields['task_id'].'#message'.$fields['chat_id']) }}">{{ __('by task') }}</a> {{ $fields['customer'] }} - «{{ $fields['name'] }}» {{ __('in the system') }} @include('emails.blocks._system_href_block')</h3>
    <h4>{{ __('Message dated') }} {{ $fields['time'] }}:</h4>
    {!! $fields['text'] !!}
@endsection
