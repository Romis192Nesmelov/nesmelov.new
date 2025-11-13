@extends('layouts.admin')

@section('content')

    @include('admin.blocks._modal_delete_block',['modalId' => 'delete-modal', 'function' => 'delete-work', 'head' => __('Are you sure you want to delete this work?')])

    <div class="panel panel-flat">
        <div class="panel-heading">
            <h4 class="panel-title">{{ isset($data['chapter']) ? $data['chapter']->name : __('Adding a chapter') }}</h4>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" enctype="multipart/form-data" action="{{ url('/admin/chapter') }}" method="post">
                @csrf
                @if (isset($data['chapter']))
                    <input type="hidden" name="id" value="{{ $data['chapter']->id }}">
                @endif

                @include('admin.blocks._image_block', [
                    'col' => 12,
                    'name' => 'image',
                    'head' => __('Substrate'),
                    'preview' => isset($data['chapter']) && $data['chapter']->image ? asset($data['chapter']->image) : '',
                    'full' => isset($data['chapter']) && $data['chapter']->image ? asset($data['chapter']->image) : null
                ])

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="panel panel-flat">
                        <div class="panel-body">
                            <div class="panel-body">
                                @include('admin.blocks._input_block', [
                                    'label' => __('Name in the menu'),
                                    'name' => 'rus',
                                    'type' => 'text',
                                    'max' => 100,
                                    'placeholder' => __('Name in the menu'),
                                    'value' => isset($data['chapter']) ? $data['chapter']->rus : ''
                                ])

                                @include('admin.blocks._input_block', [
                                    'label' => __('Link to the extended portfolio'),
                                    'name' => 'full_portfolio',
                                    'type' => 'text',
                                    'max' => 255,
                                    'placeholder' => __('Link to the extended portfolio'),
                                    'value' => isset($data['chapter']) ? $data['chapter']->full_portfolio : ''
                                ])
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="panel-body">
                        @include('admin.blocks._checkbox_block', ['name' => 'active', 'checked' => isset($data['chapter']) ? $data['chapter']->active : true, 'label' => __('Chapter is active')])
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
                                <td class="text-center head"><a href="/admin/chapters/{{ $data['chapter']->eng }}?id={{ $work->id }}">{{ $work->name }}</a></td>
                                <td class="text-center">{{ $work->description }}</td>
                                <td class="text-center">@include('admin.blocks._status_block', ['status' => $work->active, 'trueLabel' => __('Active'), 'falseLabel' => __('Not Active')])</td>
                                <td class="delete"><span del-data="{{ $work->id }}" modal-data="delete-modal" class="glyphicon glyphicon-remove-circle"></span></td>
                            </tr>
                        @endforeach
                    </table>
                @endif
                @include('admin.blocks._add_button_block',['href' => 'chapters/'.$data['chapter']->eng.'/add', 'text' => __('Add a work')])
            </div>
        @endif

    </div>
@endsection
