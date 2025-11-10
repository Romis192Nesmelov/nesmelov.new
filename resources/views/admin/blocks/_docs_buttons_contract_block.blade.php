@if ( (isset($task) && $task->customer->save_contract && $task->customer->contract) || (isset($customer) && $customer->save_contract && $customer->contract) )
    @include('admin.blocks._docs_button_block', [
        'text' => 'Распечатать каст.договор',
        'href' => 'saved_contract?id='.(isset($task) ? $task->customer->id : $customer->id)
    ])
@else
    @include('admin.blocks._docs_button_block', [
        'text' => 'Распечатать договор',
        'href' => 'contract?id='.(isset($task) ? $task->customer->id : $customer->id).'&tax_type='.(isset($task) ? $task->tax_type : (int)Settings::getSettings()['my_status'])
    ])
    @include('admin.blocks._docs_button_block', [
        'text' => 'Распечатать договор '.( (isset($task) && $task->tax_type) || (int)Settings::getSettings()['my_status'] ? 'с печатью' : 'с подписью' ),
        'href' => 'contract?id='.(isset($task) ? $task->customer->id : $customer->id).( (isset($task) && $task->tax_type) || (int)Settings::getSettings()['my_status'] ? '&stamp=true' : '&signature=true').'&tax_type='.(isset($task) ? $task->tax_type : (int)Settings::getSettings()['my_status'])
    ])
@endif