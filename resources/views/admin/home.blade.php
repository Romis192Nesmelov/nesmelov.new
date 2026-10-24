@extends('layouts.admin')

@section('content')
    <div class="panel panel-flat">
        <div class="panel-body">
            @foreach($menus as $item)
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="panel panel-flat">
                        <div class="panel-body text-center">
                            <div class="icon-object"><i class="{{ str_replace('position-left','',$item['icon']) }}"></i></div>
                            <h3 class="text-semibold mt-5">{{ $item['name'] }}</h3>
                            <a href="{{ route('admin.'.$item['href']) }}">
                                @include('admin.blocks._button_block', [
                                    'type' => 'button',
                                    'icon' => 'icon-circle-right2',
                                    'text' => __('Go over'),
                                ])
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
