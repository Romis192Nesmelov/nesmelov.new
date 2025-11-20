@if ($task->paid_off && !$fullVal)
    {{ ruNumScript($task->paid_off) }}
@else
    {{ ruNumScript(calculateOverTaskVal($task, $fullVal)) }}
@endif
рублей, 00 копеек
