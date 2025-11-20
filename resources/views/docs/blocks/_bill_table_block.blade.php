<table class="bill">
    <tr>
        <th>Наименование работ</th>
        <th class="value">Сумма</th>
    </tr>
    @if ($task->paid_off && !$fullVal)
        <tr>
            <td>{{ 'Предоплата за '.mbFirstStrToLower(preg_replace('/тка\s/ui','тку ',$task->name)) }}</td>
            <td class="value">@include('docs.blocks._task_value_money_format_block')₽</td>
        </tr>
    @elseif (count($task->subTasks))
        @foreach($task->subTasks as $subTask)
            <tr>
                <td>{{ $subTask->name }}</td>
                <td class="value">{{ moneyFormat((int)$subTask->value) }} ₽</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td>{{ $task->name }}</td>
            <td class="value">@include('docs.blocks._task_value_money_format_block', ['billMode' => ($billMode ?? false), 'fullVal' => true])₽</td>
        </tr>
    @endif
    @if ($useTotal)
        <tr>
            <td class="footer">Итого:</td>
            <td class="footer value">@include('docs.blocks._task_value_money_format_block')₽</td>
        </tr>
        <tr>
            <td class="footer">Всего к оплате:</td>
            <td class="footer value">@include('docs.blocks._task_value_money_format_block')₽</td>
        </tr>
    @endif
</table>
