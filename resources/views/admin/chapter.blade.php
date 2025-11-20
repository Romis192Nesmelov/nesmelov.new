@extends('layouts.admin')

@section('content')

    @include('admin.blocks._modal_delete_block',['modalId' => 'delete-modal', 'function' => 'delete-work', 'head' => __('Are you sure you want to delete this work?')])

    <div class="panel panel-flat">
        <div class="panel-heading">
            <h4 class="panel-title">{{ $data['chapter']->name }}</h4>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" enctype="multipart/form-data" action="{{ url('/admin/chapter') }}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{ $data['chapter']->id }}">

                @include('admin.blocks._image_block', [
                    'col' => 6,
                    'name' => 'image',
                    'head' => __('Substrate'),
                    'preview' => asset($data['chapter']->image),
                    'full' => asset($data['chapter']->image)
                ])

                @if ($data['chapter']->icon)
                    @include('admin.blocks._image_block', [
                        'col' => 2,
                        'name' => 'icon',
                        'head' => __('Icon'),
                        'preview' => asset($data['chapter']->icon),
                        'full' => asset($data['chapter']->icon)
                    ])
                @endif
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="panel panel-flat">
                        <div class="panel-body">
                            <div class="panel-body">
                                @include('admin.blocks._input_block', [
                                    'addClass' => 'col-md-6 col-sm-12 col-xs-12',
                                    'label' => __('The name on the menu is in Russian'),
                                    'name' => 'ru',
                                    'type' => 'text',
                                    'max' => 100,
                                    'placeholder' => __('The name on the menu is in Russian'),
                                    'value' => $data['chapter']->ru
                                ])

                                @include('admin.blocks._input_block', [
                                    'addClass' => 'col-md-6 col-sm-12 col-xs-12',
                                    'label' => __('The name on the menu is in English'),
                                    'name' => 'en',
                                    'type' => 'text',
                                    'max' => 100,
                                    'placeholder' => __('The name on the menu is in English'),
                                    'value' => $data['chapter']->en
                                ])

                                @include('admin.blocks._input_block', [
                                    'addClass' => 'col-md-6 col-sm-12 col-xs-12',
                                    'label' => __('The description in Russian'),
                                    'name' => 'description_ru',
                                    'type' => 'text',
                                    'max' => 100,
                                    'placeholder' => __('The description in Russian'),
                                    'value' => $data['chapter']->description_ru
                                ])

                                @include('admin.blocks._input_block', [
                                    'addClass' => 'col-md-6 col-sm-12 col-xs-12',
                                    'label' => __('The description in English'),
                                    'name' => 'description_en',
                                    'type' => 'text',
                                    'max' => 100,
                                    'placeholder' => __('The description in English'),
                                    'value' => $data['chapter']->description_en
                                ])

                                @include('admin.blocks._input_block', [
                                    'label' => __('Link to the extended portfolio'),
                                    'name' => 'full_portfolio',
                                    'type' => 'text',
                                    'max' => 255,
                                    'placeholder' => __('Link to the extended portfolio'),
                                    'value' => $data['chapter']->full_portfolio
                                ])
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="panel-body">
                        @include('admin.blocks._checkbox_block', ['name' => 'active', 'checked' => $data['chapter']->active, 'label' => __('Chapter is active')])
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    @include('admin.blocks._button_block', ['type' => 'submit', 'icon' => ' icon-floppy-disk', 'text' => trans('admin_content.save'), 'addClass' => 'pull-right'])
                </div>
            </form>
        </div>

        @if (isset($data['chapter']) && $data['chapter']->id != 1)
            <div class="panel-body">
                @if (count($data['chapter']->works))
                    <table class="table datatable-basic table-items">
                        <tr>
                            <th class="id">Id</th>
                            <th class="text-center">{{ __('Image') }}</th>
                            <th class="text-center">{{ __('Name') }}</th>
                            <th class="text-center">{{ __('Description') }}</th>
                            <th class="text-center">{{ __('Status') }}</th>
                            <th class="delete">{{ __('Delete') }}</th>
                        </tr>
                        @foreach ($data['chapter']->works as $work)
                            <tr role="row" id="{{ 'work_'.$work->id }}">
                                <td class="id">{{ $work->id }}</td>
                                <td class="image"><a {{ $work->url ? 'target=_blank' : 'class=img-preview' }} href="{{ $work->url ? url($work->url) : asset($work->full) }}"><img src="{{ asset($work->preview) }}" /></a></td>
                                <td class="text-center head"><a href="{{ url('/admin/chapters/'.$data['chapter']->slug.'?id='.$work->id) }}">{{ $work['name_'.app()->getLocale()] }}</a></td>
                                <td class="text-center">{{ $work['description_'.app()->getLocale()] }}</td>
                                <td class="text-center">@include('admin.blocks._status_block', ['status' => $work->active, 'trueLabel' => __('Active'), 'falseLabel' => __('Not Active')])</td>
                                <td class="delete"><span del-data="{{ $work->id }}" modal-data="delete-modal" class="glyphicon glyphicon-remove-circle"></span></td>
                            </tr>
                        @endforeach
                    </table>
                @endif
                @include('admin.blocks._add_button_block',['href' => 'chapters/'.$data['chapter']->en.'/add', 'text' => __('Add a work')])
            </div>
        @endif

    </div>
@endsection
