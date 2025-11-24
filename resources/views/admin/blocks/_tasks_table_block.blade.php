<div class="panel panel-flat">
    <div class="panel-body">
        <div class="panel-heading">
            <h3 class="panel-title pull-left">{{ $head }}</h3>
            @if ($useAddButton && auth()->user()->is_admin && $data['year'] == date('Y'))
                @include('admin.blocks._add_button_block',['href' => 'tasks/add', 'text' => __('Add a task')])
            @endif
        </div>
    </div>

    <div class="panel-body">
        @if (count($tasks))
            @php $totalSum = 0; $totalDuty = 0; $totalPercents = 0; $sum = 0; $duty = 0; $percents = 0; @endphp

            @foreach ($tasks as $k => $task)
                @if (count($task->statistics))
                    @foreach ($task->statistics as $statistic)
                        <script>window.statisticsData[parseInt("{{ $chart }}")].dataHorAxis[parseInt("{{ $statistic->status-1 }}")].data[parseInt("{{ (int)$statistic->created_at->format($precision)-$offset }}")]++;</script>
                    @endforeach
                @else
                    <script>window.statisticsData[parseInt("{{ $chart }}")].dataHorAxis[2].data[parseInt("{{ date($precision,$task->start_time)-$offset }}")]++;</script>

                    @php
                        if ($task->status == 1 && $task->payment_time) {
                            $status = 0;
                            $time = date($precision,$task->payment_time)-$offset;
                        } elseif (($task->status == 1 && $task->completion_time) || $task->status == 2) {
                            $status = $task->status-1;
                            $time = date($precision,$task->completion_time)-$offset;
                        } else {
                            $status = $task->status-1;
                            $time = $task->updated_at->format($precision)-$offset;
                        }
                    @endphp
                    <script>window.statisticsData[parseInt("{{ $chart }}")].dataHorAxis[parseInt("{{ $status }}")].data[parseInt("{{ $time }}")]++;</script>
                @endif

                @if ( (isset($customer) && $task->customer->name != $customer) || !isset($customer))
                    @if (isset($customer))
                        @include('admin.blocks._end_tasks_table_block',[
                            'sum' => $sum,
                            'duty' => $duty,
                            'percents' => $percents,
                            'slug' => $data['year'] == date('Y') && $tasks[$k-1]->customer->type < 5 ? $tasks[$k-1]->customer->slug : null,
                            'useDuty' => $task->use_duty,
                        ])

                        @php $sum = 0; $duty = 0; $percents = 0; @endphp
                    @endif

                    @php $customer = $task->customer->name; @endphp
                    <a name="{{ $prefixAnchor.'_'.$task->customer->slug }}"></a>
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h4 class="panel-title pull-left">{{  $customer }}</h4>
                        </div>
                        <div class="panel-body customer" data-customer="{{ $customer }}">
                            <table class="table datatable-basic table-items">
                                @include('admin.blocks._tasks_table_header_block')
                @endif
                @php
                    $presentSubTasks = false;
                    if (count($task->subTasks)) {
                        foreach ($task->subTasks as $subTask) {
                            if (date('Y',$subTask->completion_time) == date('Y')) {
                                $presentSubTasks = true;
                            }
                        }
                    }

                    if ($task->status == 1) {
                        $taskValue = calculateOverTaskVal($task);
                        $taskValueWithoutAll = calculateOverTaskVal($task, true, true, $task->use_duty, true);

                        $taskDuty = $task->use_duty ? calculateTaskDuty($taskValue, $task) : 0;
                        $taskPercents = $task->percents ? calculateTaskPercents($taskValue - $taskDuty, $task->percents) : 0;
                        if (count($task->subTasks) && $presentSubTasks) {
                            foreach ($task->subTasks as $subTask) {
                                if ($subTask->percents && ($subTask->status == 1)) {
                                        $subTaskValueWithoutAll = calculateOverTaskVal($subTask, true, false, $task->use_duty, true);
                                        $subTaskDuty = $task->use_duty ? calculateTaskDuty($subTask->value, $task) : 0;
                                        $taskPercents += $subTask->percents ? calculateTaskPercents($subTaskValueWithoutAll - $subTaskDuty, $subTask->percents) : 0;
                                }
                            }
                        }

                        $duty += $taskDuty;
                        $sum += $taskValueWithoutAll;
                        $percents += $taskPercents;

                        $totalDuty += $duty;
                        $totalSum += $taskValueWithoutAll;
                        $totalPercents += $taskPercents;
                    }
                @endphp

                @include('admin.blocks._tasks_table_item_block',['task' => $task,'uri' => 'tasks'])
            @endforeach

            @if (isset($data['work_tasks']))
                <script>window.statisticsData[parseInt("{{ $chart }}")].dataHorAxis[2].data[window.statisticsData[parseInt("{{ $chart }}")].dataHorAxis[0].data.length-1] = parseInt("{{ $data['work_tasks'] }}");</script>
            @endif

            @include('admin.blocks._delete_empty_chart_data_block',['chart' => $chart])

            @include('admin.blocks._end_tasks_table_block',[
                'sum' => $sum,
                'duty' => $duty,
                'percents' => $percents,
                'slug' => $data['year'] == date('Y') && $task->customer->type < 5 ? $task->customer->slug : null,
                'useDuty' => $task->use_duty
            ])

            @include('admin.blocks._sum_tasks_block',[
                'addTotalDesk' => __('Total profit'),
                'showAverageIncome' => true,
                'sum' => $totalSum,
                'duty' => $totalDuty,
                'percents' => $totalPercents
            ])
        @endif
    </div>
</div>
