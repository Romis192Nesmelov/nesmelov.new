@extends('layouts.admin')

@section('content')
    @php $precision = $data['max_val']-$data['min_val'] == 1 ? 'j' : 'n'; @endphp
    <script>
        var maxMonth = parseInt("{{ $data['max_month'] }}"),
            minVal = parseInt("{{ $data['min_val'] }}"),
            maxVal = parseInt("{{ $data['max_val'] }}");
    </script>
    <script type="text/javascript" src="{{ asset('js/statistics.js') }}"></script>

    <div class="panel panel-flat">
        <div class="panel-heading">
            <h1 class="panel-title pull-left">{{ __('Statistics (months)') }}</h1>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" enctype="multipart/form-data" action="{{ url('/admin/statistics/'.$data['year']) }}" method="get">
                @include('admin.blocks._slider_block',['sliderName' => 'slider-months', 'sliderId' => 'slider-statistics'])
                @include('admin.blocks._button_block', ['type' => 'submit', 'icon' => 'icon-database-refresh', 'text' => 'Обновить', 'addClass' => 'pull-right'])
            </form>
        </div>
    </div>

    <div class="panel panel-flat">
        <div class="panel-heading">
            <h3 class="panel-title pull-left">{{ __('Issue statistics') }}</h3>
            @include('admin.blocks._heading_elements_block')
        </div>
        <div class="panel-body">
            @include('admin.blocks._chart_container_block', [
                'startPos' => $data['min_val'],
                'endPos' => $data['max_val'],
                'legend' => $data['statuses_simple'],
                'chartId' => 'tasks-chart'
            ])
        </div>
    </div>

    <div class="panel panel-flat">
        <div class="panel-heading">
            <h3 class="panel-title pull-left">{{ __('Income statistics') }}</h3>
            @include('admin.blocks._heading_elements_block')
        </div>
        <div class="panel-body">
            @include('admin.blocks._chart_container_block', [
                'startPos' => $data['min_val'],
                'endPos' => $data['max_val'],
                'legend' => $data['income_statuses'],
                'chartId' => 'income-chart'
            ])
        </div>
    </div>

    <div class="panel panel-flat">
        <div class="panel-heading">
            <h3 class="panel-title pull-left">{{ __('Statistics by year') }}</h3>
            @include('admin.blocks._heading_elements_block')
        </div>
        <div class="panel-body">
            @include('admin.blocks._chart_container_block', [
                'startPos' => $data['min_val'],
                'endPos' => $data['max_val'],
                'legend' => $data['years'],
                'chartId' => 'years-chart'
            ])
        </div>
    </div>

    <div class="panel panel-flat">
        <div class="panel-heading">
            @include('admin.blocks._heading_elements_block')
        </div>
        <div class="panel-body">
            @include('admin.blocks._tasks_table_block', [
                'head' => 'Задачи',
                'prefixAnchor' => 'user',
                'tasks' => $data['tasks'],
                'useAddButton' => false,
                'offset' => $data['min_val'],
                'chart' => 0,
                'precision' => $precision
            ])
        </div>
    </div>

    @foreach ($data['done_tasks'] as $task)
        <?php $month = $task->payment_time ? (int)date($precision,$task->payment_time) : (int)date($precision,$task->completion_time); ?>
        <script>window.statisticsData[1].dataHorAxis[0].data[parseInt("{{ $month - $data['min_val'] }}")] += parseInt("{{ calculateOverTaskVal($task, true, true, true, true) }}");</script>
    @endforeach

    @foreach ($data['wait_tasks'] as $task)
        <script>window.statisticsData[1].dataHorAxis[1].data[parseInt("{{ (int)date($precision,$task->completion_time)-$data['min_val'] }}")] += parseInt("{{ calculateOverTaskVal($task, true, true, true, true) }}");</script>
    @endforeach

    @foreach ($data['in_work_tasks'] as $task)
        <script>window.statisticsData[1].dataHorAxis[2].data[parseInt("{{ (int)date($precision,$task->start_time)-$data['min_val'] }}")] += parseInt("{{ $task->paid_off }}");</script>
    @endforeach

    @php $yearCounter = 0; @endphp
    @foreach ($data['done_tasks_for_all_years'] as $year => $months)
        @foreach ($months as $month => $val)
            <script>window.statisticsData[2].dataHorAxis[parseInt("{{ $yearCounter }}")].data[parseInt("{{ $month  - $data['min_val'] }}")] = parseInt("{{ $val }}");</script>
        @endforeach
        @php $yearCounter++; @endphp
    @endforeach

    @include('admin.blocks._delete_empty_chart_data_block',['chart' => 1])

    @include('admin.blocks._years_block')
@endsection
