@extends('layouts.admin')

@section('content')
    @include('admin.blocks._modal_delete_block',['modalId' => 'delete-modal', 'function' => 'delete-task', 'head' => __('Are you sure you want to delete this task?')])
    <div class="container-detached">
        <div class="content-detached">
            <div id="tasks-chart-container" class="panel panel-flat">
                <div class="panel-heading">
                    <h3>{{ __('Task statistics') }}</h3>
                    @include('admin.blocks._heading_elements_block')
                </div>

                @include('admin.blocks._chart_container_block', [
                    'startPos' => 1,
                    'endPos' => ($data['year'] != date('Y') ? 12 : date('n')),
                    'legend' => $data['statuses_simple'],
                    'chartId' => 'tasks-chart'
                ])
            </div>
            @can('is-admin')
                @include('admin.blocks._tasks_table_block', [
                    'head' => __('Tasks'),
                    'prefixAnchor' => 'user',
                    'tasks' => $data['tasks'],
                    'useAddButton' => true,
                    'offset' => 1,
                    'chart' => 0,
                    'precision' => 'n'
                ])
            @elseif (count($data['tasks']) || count($data['own_tasks']))

                @if (count($data['tasks']))
                    @include('admin.blocks._tasks_table_block', [
                        'head' => count($data['own_tasks']) ? __('The tasks you perform') : __('Tasks'),
                        'prefixAnchor' => 'user',
                        'tasks' => $data['tasks'],
                        'useAddButton' => true,
                        'offset' => 1,
                        'chart' => 0,
                        'precision' => 'n'
                    ])
                @endif

                @if (count($data['own_tasks']))
                    @include('admin.blocks._tasks_table_block', [
                        'head' => count($data['tasks']) ? __('The tasks you set') : __('Tasks'),
                        'prefixAnchor' => 'owner',
                        'tasks' => $data['own_tasks'],
                        'useAddButton' => false,
                        'offset' => 1,
                        'chart' => 0,
                        'precision' => 'n'
                    ])
                @endif

                <div class="panel-heading">
                    @include('admin.blocks._add_button_block',['href' => 'tasks/add', 'text' => __('Add a task')])
                </div>
            @else
                <div class="panel-body">
                    <div class="panel-heading">
                        @include('admin.blocks._add_button_block',['href' => 'tasks/add', 'text' => __('Add a task')])
                    </div>
                    <h1 class="text-center text-grey-300">{{ __('No tasks') }}</h1>
                </div>
            @endcan
        </div>
        @include('admin.blocks._years_block')
    </div>

    @if (count($data['tasks']) || (isset($data['own_tasks']) && count($data['own_tasks'])))
        <div class="sidebar-detached">
            <div class="sidebar sidebar-default">
                <div class="sidebar-content">
                    @can('is-admin')
                        @include('admin.blocks._customers_sidebar_block', ['head' => __('Customers'), 'prefixAnchor' => 'user', 'tasks' => $data['tasks']])
                    @else
                        @if (count($data['tasks']))
                            @include('admin.blocks._customers_sidebar_block', ['head' => count($data['own_tasks']) ? __('Customers of the tasks you perform') : __('Customers'), 'prefixAnchor' => 'user', 'tasks' => $data['tasks']])
                        @endif

                        @if (count($data['own_tasks']))
                            @include('admin.blocks._customers_sidebar_block', ['head' => count($data['tasks']) ? __('Customers of the tasks you perform') : __('Customers'), 'prefixAnchor' => 'owner', 'tasks' => $data['own_tasks']])
                        @endif
                    @endcan
                </div>
            </div>
        </div>
    @endif
@endsection
