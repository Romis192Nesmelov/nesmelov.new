<div class="{{ isset($addClass) && $addClass ? $addClass : '' }} panel panel-flat panel-collapsed">
    <div class="panel-heading">
        <h4 class="panel-title">Кастомный договор</h4>
        @include('admin.blocks._heading_elements_block')
    </div>
    <div class="panel-body">
        @php if(!isset($customer)) $customer = $task->customer; @endphp
        @include('admin.blocks._textarea_block', [
            'name' => 'contract',
            'value' => $customer->contract ? $customer->contract : view('docs.blocks._contract_body_block',['item' => $customer, 'taxType' => isset($task) ? $task->tax_type : (int)Settings::getSettings()['my_status']])->render(),
            'height' => 1000
        ])
        @include('admin.blocks._checkbox_block', ['label' => 'Сохранить договор', 'name' => 'save_contract','checked' => $customer->save_contract])
    </div>
</div>