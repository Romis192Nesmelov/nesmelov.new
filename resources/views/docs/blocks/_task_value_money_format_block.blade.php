@if ($task->paid_off && !$fullVal)
    {{ moneyFormat((int)$task->paid_off) }}
@else
    {{ moneyFormat(calculateOverTaskVal($task,(isset($billMode) && $billMode ? false : $fullVal))) }}
@endif
