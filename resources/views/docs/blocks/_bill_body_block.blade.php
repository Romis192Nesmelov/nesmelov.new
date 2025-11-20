<h1>СЧЕТ № {{ $item->number }} от @include('docs.blocks._date_block',['date' => $item->date])</h1>

@include('docs.blocks._my_creds_block', ['taxType' => $item->task->tax_type])
@include('docs.blocks._customer_creds_block', ['customer' => $item->task->customer])

@include('docs.blocks._bill_table_block', [
    'task' => $item->task,
    'useTotal' => true,
    'fullVal' => isFinalBill($item),
    'billMode' => true
])
<p><b>Итоговая сумма прописью: @include('docs.blocks._task_value_words_format_block',['task' => $item->task, 'fullVal' => false])</b></p>
