@extends('layouts.admin')

@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h6 class="panel-title">{{ isset($data['sub_task']) ? __('Editing a subtask').' '.$data['sub_task']->name.' - '.__('for task').' «'.$data['task']->name.'»' : __('Adding a subtask for a task').' «'.$data['task']->name.'»' }}</h6>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" enctype="multipart/form-data" action="{{ url('/admin/sub_task') }}" method="post">
                @csrf

                @if (isset($data['sub_task']))
                    <input type="hidden" name="id" value="{{ $data['sub_task']->id }}">
                @else
                    <input type="hidden" name="parent_id" value="{{ $data['task']->id }}">
                @endif

                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h6 class="panel-title"><b>{{ __('Parent task') }}:</b> {{ $data['task']->name }}</h6>
                        </div>
                    </div>

                    @if (Gate::allows('owner-task',$data['task']) && Gate::allows('owner-or-user-task',$data['task']))
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h5 class="panel-title">{{ __('Status') }}</h5>
                            </div>
                            <div class="panel-body">
                                @include('admin.blocks._radio_button_block', [
                                    'name' => 'status',
                                    'values' => $data['statuses'],
                                    'activeValue' => isset($data['sub_task']) ? $data['sub_task']->status : 3
                                ])
                            </div>
                        </div>
                    @else
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h5 class="panel-title">
                                    {{ __('Status') }}:
                                    @include('admin.blocks._extended_status_block',[
                                        'status' => $data['sub_task']->status,
                                        'descriptions' => $data['statuses_simple']
                                    ])
                                </h5>
                            </div>
                        </div>
                    @endif

                    @if (Gate::allows('is-admin') && isset($data['sub_task']) && $data['sub_task']->percents)
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h5 class="panel-title">{{ __('Interest paid') }}</h5>
                            </div>
                            <div class="panel-body">
                                @can('owner-or-user-task',$data['task'])
                                    @include('admin.blocks._radio_button_block', [
                                        'name' => 'paid_percents',
                                        'values' => [
                                            ['val' => 0, 'descript' => __('Not paid')],
                                            ['val' => 1, 'descript' => __('Paid')]
                                        ],
                                        'activeValue' => $data['sub_task']->paid_percents
                                    ])
                                @else
                                    @include('admin.blocks._status_block',[
                                        'status' => $data['task']->paid_percents,
                                        'trueLabel' => 'Да',
                                        'falseLabel' => 'Нет'
                                    ])
                                @endcan
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-md-8 col-sm-6 col-xs-12">
                    <div class="panel panel-flat">
                        <div class="panel-body">
                            @can('owner-or-user-task',$data['task'])
                                <div class="panel panel-flat">
                                    <div class="panel-body">
                                        @include('admin.blocks._input_block', [
                                            'addClass' => 'col-md-12 col-sm-12 col-xs-12 name',
                                            'label' => __('Task name'),
                                            'name' => 'name',
                                            'type' => 'text',
                                            'max' => 255,
                                            'placeholder' => __('Task name'),
                                            'value' => isset($data['sub_task']) ? $data['sub_task']->name : ''
                                        ])

                                        @include('admin.blocks._input_block', [
                                            'addClass' => 'col-md-6 col-sm-12 col-xs-12',
                                            'label' => __('Issue price'),
                                            'name' => 'value',
                                            'type' => 'number',
                                            'min' => 0,
                                            'max' => 2000000,
                                            'placeholder' => __('Issue price'),
                                            'value' => isset($data['sub_task']) ? $data['sub_task']->value : 100
                                        ])

                                        @include('admin.blocks._input_block', [
                                            'addClass' => 'col-md-6 col-sm-12 col-xs-12',
                                            'label' => __('Third-party percentage (%)'),
                                            'name' => 'percents',
                                            'type' => 'number',
                                            'step' => 0.001,
                                            'min' => 0,
                                            'max' => 100,
                                            'placeholder' => __('Third-party percentage (%)'),
                                            'value' => isset($data['sub_task']) && $data['sub_task']->percents ? $data['sub_task']->percents : 0
                                        ])

                                        @include('admin.blocks._date_block', [
                                            'addClass' => 'col-md-6 col-sm-12 col-xs-12',
                                            'label' => __('Getting started'),
                                            'name' => 'start_time',
                                            'value' => isset($data['sub_task']) ? $data['sub_task']->start_time : time()
                                        ])

                                        @include('admin.blocks._date_block', [
                                            'addClass' => 'col-md-6 col-sm-12 col-xs-12',
                                            'label' => __('Estimated completion time'),
                                            'name' => 'completion_time',
                                            'value' => isset($data['sub_task']) ? $data['sub_task']->completion_time : time()
                                        ])
                                    </div>
                                </div>
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h4 class="text-grey-300">{{ __('This field is optional') }}</h4>
                                    </div>
                                    <div class="panel-body">
                                        @include('admin.blocks._textarea_block', [
                                            'label' => __('Task description'),
                                            'name' => 'description',
                                            'value' => isset($data['sub_task']) ? $data['sub_task']->description : '',
                                            'max' => 2000,
                                            'simple' => false
                                        ])
                                    </div>
                                </div>
                                @include('admin.blocks._checkbox_send_mail_block',['checked' => (isset($data['sub_task']) ? $data['sub_task']->send_email : true)])

                                @if (isset($data['sub_task']))
                                    <p>
                                        {{ __('Subtask has been created') }}: <b>{{ $data['sub_task']->created_at }}</b><br>
                                        {{ __('Last edition') }}: <b>{{ $data['sub_task']->updated_at }}</b>
                                    </p>
                                @endif

                            @else
                                <h4>{{ __('Task name') }}: <b>{{ $data['sub_task']->name }}</b></h4>
                                <h4>{{ __('Issue price') }}: <b>{{ $data['sub_task']->value }}₽</b></h4>
                                <h4>{{ __('Getting started') }}: <b>{{ date('d.m.Y',$data['sub_task']->start_time) }}</b><br>
                                    {{ __('Estimated completion time') }}: <b>{{ date('d.m.Y',$data['task']->completion_time) }}</b>
                                </h4>
                                <hr>
                                <h6>{{ __('Description') }}</h6>
                                @if ($data['sub_task']->description)
                                    {!! $data['sub_task']->description !!}
                                @else
                                    <p>{{ __('No description') }}</p>
                                @endif
                            @endcan
                        </div>
                    </div>
                </div>

                @can('owner-or-user-task',$data['task'])
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="panel-body">
                            @include('admin.blocks._button_block', ['type' => 'submit', 'icon' => ' icon-floppy-disk', 'text' => trans('admin_content.save'), 'addClass' => 'pull-right'])
                        </div>
                    </div>
                @endcan
            </form>
        </div>
    </div>
@endsection
