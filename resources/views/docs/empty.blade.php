@extends('layouts.docs')

@section('content')
    {!! $content !!}
    @include('docs.blocks._print_button_block')
@endsection