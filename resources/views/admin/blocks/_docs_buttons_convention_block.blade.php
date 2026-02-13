@if (moneyFormat(calculateOverTaskVal($task)))
    @if ($task->convention)
        @include('admin.blocks._docs_button_block', [
            'text' => __('Print the custom agreement'),
            'href' => 'saved_convention?id='.$task->id
        ])
    @else
        @include('admin.blocks._docs_button_block', [
            'text' => __('Print the add.agreement'),
            'href' => 'convention?id='.$task->id.'&tax_type='.$task->tax_type
        ])
        @include('admin.blocks._docs_button_block', [
            'text' => __('Print the add.agreement').' '.( (isset($task) && $task->tax_type) || (int)getSettings()['my_status'] ? __('with a seal') : __('with a sign.')),
            'href' => 'convention?id='.$task->id.( (isset($task) && $task->tax_type) || (int)getSettings()['my_status'] ? '&stamp=true' : '&signature=true').'&tax_type='.$task->tax_type
        ])
    @endif
@endif
