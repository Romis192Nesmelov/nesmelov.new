@if (count($bill->task->subTasks))
    <ul>
        @foreach($bill->task->subTasks as $subTask)
            <li><b>{{ $subTask->name }};</b></li>
        @endforeach
    </ul>
@else
    <b>{{ $bill->task->paid_off && !count($bill->task->bills) ? 'Предоплата за '.mbFirstStrToLower(preg_replace('/тка\s/ui','тку ',$bill->task->name)) : $bill->task->name }}</b>
@endif
