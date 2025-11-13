@if ( (isset($task) && $task->customer->save_contract && $task->customer->contract) || (isset($customer) && $customer->save_contract && $customer->contract) )
    @include('admin.blocks._docs_button_block', [
        'text' => __('Print the custom contract'),
        'href' => 'saved_contract?id='.(isset($task) ? $task->customer->id : $customer->id)
    ])
@else
    @include('admin.blocks._docs_button_block', [
        'text' => __('Print the contract'),
        'href' => 'contract?id='.(isset($task) ? $task->customer->id : $customer->id).'&tax_type='.(isset($task) ? $task->tax_type : (int)getSettings()['my_status'])
    ])
    @include('admin.blocks._docs_button_block', [
        'text' => __('Print the contract').' '.( (isset($task) && $task->tax_type) || (int)getSettings()['my_status'] ? __('with a seal') : __('with a sign.') ),
        'href' => 'contract?id='.(isset($task) ? $task->customer->id : $customer->id).( (isset($task) && $task->tax_type) || (int)getSettings()['my_status'] ? '&stamp=true' : '&signature=true').'&tax_type='.(isset($task) ? $task->tax_type : (int)getSettings()['my_status'])
    ])
@endif
