@extends('layouts.admin')

@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h4 class="panel-title">{{ isset($data['user']) ? __('Editing the user').' '.$data['user']->email : __('Adding a user') }}</h4>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" enctype="multipart/form-data" action="{{ url('/admin/user') }}" method="post">
                @csrf
                @if (isset($data['user']))
                    <input type="hidden" name="id" value="{{ $data['user']->id }}">
                @endif

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="panel panel-flat">
                        <div class="panel-body">
                            @include('admin.blocks._input_block', [
                                'label' => __('User\'s name'),
                                'name' => 'name',
                                'type' => 'text',
                                'max' => 255,
                                'placeholder' => __('User\'s name'),
                                'value' => isset($data['user']) ? $data['user']->name : ''
                            ])

                            @include('admin.blocks._input_block', [
                                'label' => __('User\'s e-mail'),
                                'name' => 'email',
                                'type' => 'email',
                                'max' => 100,
                                'placeholder' => __('User\'s e-mail'),
                                'value' => isset($data['user']) ? $data['user']->email : ''
                            ])

                            @include('admin.blocks._input_block', [
                                'label' => __('Contact person\'s phone'),
                                'name' => 'phone',
                                'type' => 'tel',
                                'placeholder' => '+7(___)__-__-__',
                                'value' => isset($data['user']) ? $data['user']->phone : ''
                            ])

                            <div class="panel panel-flat">
                                @if (isset($data['user']))
                                    <div class="panel-heading">
                                        <h4 class="text-grey-300">{{ __('If you do not want to change your password, then leave these fields blank') }}</h4>
                                    </div>
                                @endif

                                <div class="panel-body">
                                    @if (isset($data['user']) && !auth()->user()->is_admin)
                                        @include('admin.blocks._input_block', [
                                            'label' => 'Старый пароль',
                                            'name' => 'old_password',
                                            'type' => 'password',
                                            'max' => 50,
                                            'placeholder' => __('The user\'s old password'),
                                            'value' => ''
                                        ])
                                    @endif
                                    @include('admin.blocks._input_block', [
                                        'label' => __('The user\'s new password'),
                                        'name' => 'password',
                                        'type' => 'password',
                                        'max' => 50,
                                        'placeholder' => __('The user\'s new password'),
                                        'value' => ''
                                    ])

                                    @include('admin.blocks._input_block', [
                                        'label' => __('Password confirmation'),
                                        'name' => 'password_confirmation',
                                        'type' => 'password',
                                        'max' => 50,
                                        'placeholder' => __('Password confirmation'),
                                        'value' => ''
                                    ])
                                </div>
                            </div>
                        </div>
                    </div>

                    @can('is-admin')
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                @include('admin.blocks._radio_button_block', [
                                    'name' => 'is_admin',
                                    'values' => [
                                        ['val' => 1, 'descript' => __('Administrator')],
                                        ['val' => 0, 'descript' => __('User')]
                                    ],
                                    'activeValue' => isset($data['user']) ? $data['user']->is_admin : 0
                                ])
                            </div>
                        </div>
                    @endcan
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    @include('admin.blocks._button_block', ['type' => 'submit', 'icon' => ' icon-floppy-disk', 'text' => trans('admin_content.save'), 'addClass' => 'pull-right'])
                </div>
            </form>
        </div>
    </div>
@endsection
