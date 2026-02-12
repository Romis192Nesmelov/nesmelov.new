@if (auth()->user()->is_admin && $task->percents)
    <span title="{{ $task->percents }}₽" class="percents {{ $task->paid_percents ? 'paid' : '' }}">{{ $task->percents }}₽</span>
@endif
