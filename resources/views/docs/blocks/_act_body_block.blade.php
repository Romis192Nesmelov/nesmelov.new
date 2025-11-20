<h1 class="head">АКТ СДАЧИ-ПРИЕМКИ</h1>
<p class="sub-head">
    @if ($item->task->convention_number)
        по дополнительному соглашению №{{ $item->task->convention_number }} от @include('docs.blocks._date_block',['date' => $item->task->convention_date])<br>
    @endif
    @if ($item->task->customer->contract_number)
        @include('docs.blocks._contract_number_block', ['task' => $item->task])
    @endif
</p>
<p class="sub-head">
    <span class="city">г.Москва</span>
    <span class="date">@include('docs.blocks._date_block',['date' => $item->date, 'quotes' => true])</span>
</p>
<p class="body">@include('docs.blocks._intro_block', ['customer' => $item->task->customer, 'taxType' => $item->task->tax_type]) заключили настоящий Акт о
    @if ($item->task->convention_number)
        том, что Исполнитель выполнил, а Заказчик принял работы в соответствии с условиями {{ $item->task->convention_number ? 'Дополнительного соглашения №'.$item->task->convention_number : 'Договора №'.($item->task->customer->contract_number ? $item->task->customer->contract_number : '____') }} от {{ $item->task->convention_number ? date('d.m.Y', $item->task->convention_date) : ($item->task->customer->contract_number ? date('d.m.Y',$item->task->customer->contract_date) : view('docs._unknown_date_block')->render()) }} (далее – Дополнительное соглашение) на выполнение работ (далее – Договор) на общую сумму @include('docs.blocks._task_value_money_format_block',['task' => $item->task, 'fullVal' => true]) руб. (@include('docs.blocks._task_value_words_format_block',['task' => $item->task, 'fullVal' => true])), а именно:</p>
@else
    нижеследующем:
@endif
<ol>
    <li>Исполнитель оказал услуги:
        @include('docs.blocks._task_or_subtasks_names_block', ['bill' => $item])
        , а Заказчик принял оказанные услуги.
    </li>
    <li>Исполнитель передал, а Заказчик принял готовые Материалы в электронном виде.</li>
    <li>Стоимость услуг составила <b>@include('docs.blocks._task_value_money_format_block',['task' => $item->task, 'fullVal' => true]) руб. (@include('docs.blocks._task_value_words_format_block',['task' => $item->task, 'fullVal' => true])). НДС не облагается.</b></li>
    <li>Расчеты между Сторонами выполнены. Качество оказанных Исполнителем услуг соответствует требованиям Заказчика. Стороны не имеют взаимных претензий.</li>
</ol>
