@extends('layouts.admin')

@section('content')
    <form class="form-horizontal" action="{{ url('/admin/settings') }}" method="post">
        @csrf
        <div class="panel panel-flat">
            <div class="panel-body">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h4 class="panel-title">{{ __('Settings') }}</h4>
                    </div>
                    <div class="panel-body">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="panel panel-flat">
                                <div class="panel-heading">
                                    <h5 class="panel-title">{{ __('Type of taxation') }}</h5>
                                </div>
                                <div class="panel-body">
                                    @include('admin.blocks._radio_button_block', [
                                        'name' => 'my_status',
                                        'values' => [
                                            ['val' => 1, 'descript' => __('IE')],
                                            ['val' => 0, 'descript' => __('Self-emp.')],
                                        ],
                                        'activeValue' => (int)getSettings()['my_status']
                                    ])
                                </div>
                            </div>
                        </div>
                        <div class="ie-block {{ !(int)getSettings()['my_status'] ? 'hidden' : '' }}" >
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="panel panel-flat">
                                    <div class="panel-body">
                                        @include('admin.blocks._input_block', [
                                            'label' => __('Tax').' '.__('IE'),
                                            'name' => 'tax',
                                            'type' => 'number',
                                            'placeholder' => __('Tax').' '.__('IE'),
                                            'min' => 1,
                                            'max' => 90,
                                            'value' => $data['settings']['tax']
                                        ])
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="panel panel-flat">
                                    <div class="panel-body">
                                        @include('admin.blocks._input_block', [
                                            'label' => __('My percent'),
                                            'name' => 'my_percent',
                                            'type' => 'number',
                                            'placeholder' => __('My percent'),
                                            'min' => 10,
                                            'max' => 90,
                                            'value' => $data['settings']['my_percent']
                                        ])
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="panel panel-flat">
                                    <div class="panel-body">
                                        @include('admin.blocks._input_block', [
                                            'label' => __('Fix PFRF'),
                                            'name' => 'fix_tax',
                                            'type' => 'number',
                                            'placeholder' => __('Fix PFRF'),
                                            'min' => 0,
                                            'max' => 100000,
                                            'value' => $data['settings']['fix_tax']
                                        ])
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="se-block col-lg-9 col-md-9 col-sm-12 col-xs-12 {{ (int)getSettings()['my_status'] ? 'hidden' : '' }}">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="panel panel-flat">
                                    <div class="panel-body">
                                        @include('admin.blocks._input_block', [
                                            'label' => __('Tax for legal entities'),
                                            'name' => 'tax1',
                                            'type' => 'number',
                                            'placeholder' => __('Tax for legal entities'),
                                            'min' => 0,
                                            'max' => 90,
                                            'value' => $data['settings']['tax1']
                                        ])
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="panel panel-flat">
                                    <div class="panel-body">
                                        @include('admin.blocks._input_block', [
                                            'label' => __('Tax for individuals'),
                                            'name' => 'tax2',
                                            'type' => 'number',
                                            'placeholder' => __('Tax for self-emp.'),
                                            'min' => 1,
                                            'max' => 90,
                                            'value' => $data['settings']['tax2']
                                        ])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h4 class="panel-title">{{ __('Requisites') }}</h4>
                    </div>
                    <div class="panel-body">
                        @include('admin.blocks._input_block', [
                            'addClass' => 'col-md-6 col-sm-6 col-xs-12',
                            'label' => __('Address'),
                            'name' => 'address',
                            'type' => 'text',
                            'max' => 255,
                            'placeholder' => __('Address'),
                            'value' => $data['requisites']['address']
                        ])
                        @include('admin.blocks._input_block', [
                            'addClass' => 'col-md-6 col-sm-6 col-xs-12',
                            'label' => __('TIN of the company'),
                            'name' => 'tin',
                            'type' => 'text',
                            'max' => 12,
                            'placeholder' => __('TIN of the company'),
                            'value' => $data['requisites']['tin']
                        ])
                        <div class="ie-block {{ !(int)getSettings()['my_status'] ? 'hidden' : '' }}">
                            @include('admin.blocks._input_block', [
                                'addClass' => 'col-md-3 col-sm-6 col-xs-12',
                                'label' => __('Bank'),
                                'name' => 'bank_ie',
                                'type' => 'text',
                                'max' => 255,
                                'placeholder' => __('Bank'),
                                'value' => $data['requisites']['bank_ie']
                            ])
                            @include('admin.blocks._input_block', [
                                'addClass' => 'col-md-3 col-sm-6 col-xs-12',
                                'label' => __('Bank Id'),
                                'name' => 'bank_id_ie',
                                'type' => 'text',
                                'max' => 9,
                                'placeholder' => __('Bank Id'),
                                'value' => $data['requisites']['bank_id_se']
                            ])
                            @include('admin.blocks._input_block', [
                                'addClass' => 'col-md-3 col-sm-6 col-xs-12',
                                'label' => __('Payment account'),
                                'name' => 'checking_account_ie',
                                'type' => 'text',
                                'max' => 24,
                                'placeholder' => __('Payment account'),
                                'value' => $data['requisites']['checking_account_ie']
                            ])
                            @include('admin.blocks._input_block', [
                                'addClass' => 'col-md-3 col-sm-6 col-xs-12',
                                'label' => __('Correspondent account'),
                                'name' => 'correspondent_account_ie',
                                'type' => 'text',
                                'max' => 20,
                                'placeholder' => __('Correspondent account'),
                                'value' => $data['requisites']['correspondent_account_ie']
                            ])
                        </div>
                        <div class="se-block {{ (int)getSettings()['my_status'] ? 'hidden' : '' }}">
                            @include('admin.blocks._input_block', [
                                'addClass' => 'col-md-3 col-sm-6 col-xs-12',
                                'label' => __('Bank'),
                                'name' => 'bank_se',
                                'type' => 'text',
                                'max' => 255,
                                'placeholder' => __('Bank'),
                                'value' => $data['requisites']['bank_se']
                            ])
                            @include('admin.blocks._input_block', [
                                'addClass' => 'col-md-3 col-sm-6 col-xs-12',
                                'label' => __('Bank Id'),
                                'name' => 'bank_id_se',
                                'type' => 'text',
                                'max' => 9,
                                'placeholder' => __('Bank Id'),
                                'value' => $data['requisites']['bank_id_se']
                            ])
                            @include('admin.blocks._input_block', [
                                'addClass' => 'col-md-3 col-sm-6 col-xs-12',
                                'label' => __('Payment account'),
                                'name' => 'checking_account_se',
                                'type' => 'text',
                                'max' => 24,
                                'placeholder' => __('Payment account'),
                                'value' => $data['requisites']['checking_account_se']
                            ])
                            @include('admin.blocks._input_block', [
                                'addClass' => 'col-md-3 col-sm-6 col-xs-12',
                                'label' => __('Correspondent account'),
                                'name' => 'correspondent_account_se',
                                'type' => 'text',
                                'max' => 20,
                                'placeholder' => __('Correspondent account'),
                                'value' => $data['requisites']['correspondent_account_se']
                            ])
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    @include('admin.blocks._button_block', ['type' => 'submit', 'icon' => ' icon-floppy-disk', 'text' => trans('admin_content.save'), 'addClass' => 'pull-right'])
                </div>
            </div>
        </div>
    </form>
@endsection
