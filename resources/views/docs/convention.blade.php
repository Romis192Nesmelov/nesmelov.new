@extends('layouts.docs')

@section('content')
    <div id="container">
        @include('docs.blocks._convention_body_block')
        @include('docs.blocks._signatures_block',['director' => $item->customer->director])
    </div>
    @include('docs.blocks._print_button_block')
@endsection