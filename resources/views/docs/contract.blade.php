@extends('layouts.docs')

@section('content')
    <div id="container">
        @include('docs.blocks._contract_body_block')
    </div>
    @include('docs.blocks._print_button_block')
@endsection