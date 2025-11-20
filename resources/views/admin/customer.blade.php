@extends('layouts.admin')

@section('content')
    @include('admin.blocks._modal_delete_block',['modalId' => 'delete-modal', 'function' => 'delete-task', 'head' => __('Are you sure you want to delete this task?')])

    <div class="panel panel-flat">
        <div class="panel-heading">
            <h4 class="panel-title">{{ isset($data['customer']) ? __('Editing the customer').' '.$data['customer']->name : __('Adding a customer') }}</h4>
            @include('admin.blocks._heading_elements_block')
        </div>
        <div class="panel-body">
            <form class="form-horizontal" enctype="multipart/form-data" action="{{ url('/admin/customer') }}" method="post">
                @csrf
                @if (isset($data['customer']))
                    <input type="hidden" name="id" value="{{ $data['customer']->id }}">
                @endif

                <div class="col-md-4 col-sm-6 col-xs-12">
                    @if (auth()->user()->is_admin)
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h5 class="panel-title">{{ __('Status') }}</h5>
                            </div>
                            <div class="panel-body">
                                @include('admin.blocks._radio_button_block', [
                                    'name' => 'type',
                                    'values' => [
                                        ['val' => 1, 'descript' => __('Premium')],
                                        ['val' => 2, 'descript' => __('The usual')],
                                        ['val' => 3, 'descript' => __('Not important')],
                                        ['val' => 4, 'descript' => __('Problematic')],
                                        ['val' => 5, 'descript' => __('Under sanctions')]
                                    ],
                                    'activeValue' => isset($data['customer']) ? $data['customer']->type : 2
                                ])
                            </div>
                        </div>
                    @endif
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h5 class="panel-title">{{ __('Type') }}</h5>
                        </div>
                        <div class="panel-body">
                            @include('admin.blocks._radio_button_block', [
                                'addClass' => 'customer-type',
                                'name' => 'ltd',
                                'values' => [
                                    ['val' => 0, 'descript' => __('IE')],
                                    ['val' => 1, 'descript' => __('LLC')],
                                    ['val' => 2, 'descript' => __('P/P')],
                                    ['val' => 3, 'descript' => __('CJSC')],
                                ],
                                'activeValue' => isset($data['customer']) ? $data['customer']->ltd : 2
                            ])
                        </div>
                    </div>

                    @if (isset($data['customer']) && $data['customer']->ltd != 2)
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                @include('admin.blocks._docs_buttons_contract_block',['customer' => $data['customer']])
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-md-8 col-sm-6 col-xs-12">
                    <div class="panel panel-flat">
                        <div class="panel-body">
                            @include('admin.blocks._input_block', [
                                'addClass' => 'col-md-4 col-sm-12 col-xs-12',
                                'label' => 'Имя',
                                'name' => 'name',
                                'type' => 'text',
                                'max' => 255,
                                'placeholder' => __('Customer\'s name'),
                                'value' => isset($data['customer']) ? $data['customer']->name : ''
                            ])

                            @include('admin.blocks._input_block', [
                                'addClass' => 'col-md-4 col-sm-12 col-xs-12',
                                'label' => 'E-mail',
                                'name' => 'email',
                                'type' => 'email',
                                'max' => 100,
                                'placeholder' => 'E-mail',
                                'value' => isset($data['customer']) ? $data['customer']->email : ''
                            ])

                            @include('admin.blocks._input_block', [
                                'addClass' => 'col-md-4 col-sm-12 col-xs-12',
                                'label' => __('Phone'),
                                'name' => 'phone',
                                'type' => 'tel',
                                'placeholder' => '+7(___)__-__-__',
                                'value' => isset($data['customer']) ? $data['customer']->phone : ''
                            ])

                            @include('admin.blocks._input_block', [
                                'addClass' => 'col-4-to-12 col-md-'.(isPrivatePersonTheCustomer($data) ? ' 12' : '4').' col-sm-12 col-xs-12',
                                'label' => __('Contact person'),
                                'name' => 'contact_person',
                                'type' => 'text',
                                'max' => 255,
                                'placeholder' => __('Contact person'),
                                'value' => isset($data['customer']) ? $data['customer']->contact_person : ''
                            ])

                            @include('admin.blocks._input_block', [
                                'addClass' => 'hiding col-md-6 col-sm-12 col-xs-12'.(isPrivatePersonTheCustomer($data) ? ' hidden' : ''),
                                'label' => __('Director'),
                                'name' => 'director',
                                'type' => 'text',
                                'max' => 255,
                                'placeholder' => __('Director'),
                                'value' => isset($data['customer']) ? $data['customer']->director : ''
                            ])

                            @include('admin.blocks._input_block', [
                                'addClass' => 'hiding col-md-6 col-sm-12 col-xs-12'.(isPrivatePersonTheCustomer($data) ? ' hidden' : ''),
                                'label' => __('Director (genitive case)'),
                                'name' => 'director_case',
                                'type' => 'text',
                                'max' => 255,
                                'placeholder' => __('Company director in the genitive case'),
                                'value' => isset($data['customer']) ? $data['customer']->director_case : ''
                            ])

                            @include('admin.blocks._input_block', [
                                'addClass' => 'col-md-12 col-sm-12 col-xs-12',
                                'label' => __('Company address'),
                                'name' => 'address',
                                'type' => 'text',
                                'max' => 255,
                                'placeholder' => __('Company address'),
                                'value' => isset($data['customer']) ? $data['customer']->address : ''
                            ])

                            @include('admin.blocks._input_block', [
                                'addClass' => 'hiding col-md-6 col-sm-12 col-xs-12'.(isPrivatePersonTheCustomer($data) ? ' hidden' : ''),
                                'label' => __('Contract number'),
                                'name' => 'contract_number',
                                'type' => 'text',
                                'max' => 255,
                                'placeholder' => __('Contract number'),
                                'value' => isset($data['customer']) ? $data['customer']->contract_number : date('d-m-y').((int)getSettings()['my_status'] ? ' '.__('IE'): '')
                            ])

                            @include('admin.blocks._date_block', [
                                'addClass' => 'hiding col-md-6 col-sm-12 col-xs-12'.(isPrivatePersonTheCustomer($data) ? ' hidden' : ''),
                                'label' => __('Date of conclusion of the contract'),
                                'name' => 'contract_date',
                                'value' => isset($data['customer']) ? $data['customer']->contract_date : time()
                            ])
                        </div>
                    </div>

                    <div class="hiding panel panel-flat {{ !isset($data['customer']) || ($data['customer']->ltd == 2)  ? ' hidden' : '' }}">
                        <div class="panel-heading">
                            <h5 class="panel-title">{{ __('Requisites') }}</h5>
                        </div>
                        <div class="panel-body">
                            @include('admin.blocks._input_block', [
                                'addClass' => 'col-md-4 col-sm-12 col-xs-12',
                                'label' => __('OGRN of the company'),
                                'name' => 'ogrn',
                                'type' => 'text',
                                'max' => 13,
                                'placeholder' => __('OGRN of the company'),
                                'value' => isset($data['customer']) ? $data['customer']->ogrn : ''
                            ])

                            @include('admin.blocks._input_block', [
                                'addClass' => 'col-md-4 col-sm-12 col-xs-12',
                                'label' => __('OKPO of the company'),
                                'name' => 'okpo',
                                'type' => 'text',
                                'max' => 8,
                                'placeholder' => __('OKPO of the company'),
                                'value' => isset($data['customer']) ? $data['customer']->okpo : ''
                            ])

                            @include('admin.blocks._input_block', [
                                'addClass' => 'col-md-4 col-sm-12 col-xs-12',
                                'label' => __('OKVED of the company'),
                                'name' => 'okved',
                                'type' => 'text',
                                'max' => 255,
                                'placeholder' => __('OKVED of the company'),
                                'value' => isset($data['customer']) ? $data['customer']->okved : ''
                            ])

                            @include('admin.blocks._input_block', [
                                'addClass' => 'col-md-4 col-sm-12 col-xs-12',
                                'label' => __('OKTMO of the company'),
                                'name' => 'oktmo',
                                'type' => 'text',
                                'max' => 8,
                                'placeholder' => __('OKTMO of the company'),
                                'value' => isset($data['customer']) ? $data['customer']->oktmo : ''
                            ])

                            @include('admin.blocks._input_block', [
                                'addClass' => 'col-md-4 col-sm-12 col-xs-12',
                                'label' => __('TIN of the company'),
                                'name' => 'inn',
                                'type' => 'text',
                                'max' => 10,
                                'placeholder' => __('INN of the company'),
                                'value' => isset($data['customer']) ? $data['customer']->inn : ''
                            ])

                            @include('admin.blocks._input_block', [
                                'addClass' => 'col-md-4 col-sm-12 col-xs-12',
                                'label' => __('KPP of the company'),
                                'name' => 'kpp',
                                'type' => 'text',
                                'max' => 10,
                                'placeholder' => __('KPP of the company'),
                                'value' => isset($data['customer']) ? $data['customer']->kpp : ''
                            ])

                            @include('admin.blocks._input_block', [
                                'addClass' => 'col-md-4 col-sm-12 col-xs-12',
                                'label' => __('Payment account'),
                                'name' => 'payment_account',
                                'type' => 'text',
                                'max' => 20,
                                'placeholder' => __('Payment account'),
                                'value' => isset($data['customer']) ? $data['customer']->payment_account : ''
                            ])

                            @include('admin.blocks._input_block', [
                                'addClass' => 'col-md-4 col-sm-12 col-xs-12',
                                'label' => __('Correspondent account'),
                                'name' => 'correspondent_account',
                                'type' => 'text',
                                'max' => 20,
                                'placeholder' => __('Correspondent account'),
                                'value' => isset($data['customer']) ? $data['customer']->correspondent_account : ''
                            ])

                            @include('admin.blocks._select_block', [
                                'addClass' => 'col-md-4 col-sm-12 col-xs-12',
                                'label' => __('Bank'),
                                'name' => 'bank_id',
                                'values' => $data['banks'],
                                'selected' => isset($data['customer']) ? $data['customer']->bank_id : 1
                            ])
                        </div>
                    </div>
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h5 class="panel-title">{{ __('Description') }}</h5>
                        </div>
                        <div class="panel-body">
                            @include('admin.blocks._textarea_block', [
                                'name' => 'description',
                                'value' => isset($data['customer']) ? $data['customer']->description : '',
                                'max' => 2000,
                                'simple' => false
                            ])
                        </div>
                    </div>
                    @if (isset($data['customer']))
                        @include('admin.blocks._custom_contract_fields_block',[
                            'addClass' => 'hiding'.($data['customer']->ltd == 2 ? ' hidden' : ''),
                            'customer' => $data['customer']
                        ])
                    @endif
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    @include('admin.blocks._button_block', ['type' => 'submit', 'icon' => ' icon-floppy-disk', 'text' => trans('admin_content.save'), 'addClass' => 'pull-right'])
                </div>
            </form>
        </div>
    </div>

    @if (isset($data['customer']) && ( auth()->user()->is_admin ? count($data['customer']->tasks) : count($data['customer']->userTasks) ) )
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h4 class="panel-title pull-left">{{ __('Tasks').' '.$data['customer']->name }}</h4>
                @include('admin.blocks._heading_elements_block')
            </div>
            <div class="panel-body customer" data-customer="{{ $data['customer']->name }}">
                <?php $sum = 0; $percents = 0; ?>
                <table class="table datatable-basic table-items">
                    @include('admin.blocks._tasks_table_header_block')
                    @foreach ( (auth()->user()->is_admin ? $data['customer']->tasks : $data['customer']->userTasks) as $k => $task)
                        <?php
                        $percents += $task->percents ? $task->value/100*$task->percents : 0;
                        $sum += $task->value;

                        if (count($task->subTasks)) {
                            foreach($task->subTasks as $subTask) {
                                $sum += $subTask->value;
                            }
                        }
                        ?>
                        @include('admin.blocks._tasks_table_item_block',['uri' => 'tasks', 'task' => $task])
                    @endforeach
                @include('admin.blocks._end_tasks_table_block', ['customer' => $data['customer']->name, 'slug' => $data['customer']->slug, 'useDuty' => $task->use_duty])
    @endif
@endsection
