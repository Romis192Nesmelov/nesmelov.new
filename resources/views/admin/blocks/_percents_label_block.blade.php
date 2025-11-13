@if (auth()->user()->is_admin && $task->percents)
    <span title="{{ calculateTaskPercents(calculateOverTaskVal($task, true, false, ( (isset($task->task) && $task->task->use_duty) || (isset($task->use_duty) && $task->use_duty) ), true), $task->percents) }}₽" class="percents {{ $task->paid_percents ? 'paid' : '' }}">{{ $task->percents }}%</span>
@endif
