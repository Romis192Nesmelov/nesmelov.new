@extends('layouts.docs')

@section('content')
    <div id="container">
        @include('docs.blocks._bill_body_block')
        @include('docs.blocks._signatures_block',['director' => $item->task->customer->director])
    </div>
    @include('docs.blocks._print_button_block')
@endsection