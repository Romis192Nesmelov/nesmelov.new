@extends('layouts.mail')

@section('content')
    <h4>{{ __('Phone') }}: {{ $fields['phone'] }}</h4>
    <h4>{{ __('The name') }}: {{ $fields['name'] }}</h4>
    <h4>{{ __('Message') }}:</h4>
    <p>{{ $fields['message'] }}</p>
@endsection
