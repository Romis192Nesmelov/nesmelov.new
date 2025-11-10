@if (Helper::moneyFormat(Helper::calculateOverTaskVal($task, true)))
    @if ($task->save_convention && $task->convention)
        @include('admin.blocks._docs_button_block', [
            'text' => 'Распечатать каст.соглашение',
            'href' => 'saved_convention?id='.$task->id
        ])
    @else
        @include('admin.blocks._docs_button_block', [
            'text' => 'Распечатать доп.соглашение',
            'href' => 'convention?id='.$task->id.'&tax_type='.$task->tax_type
        ])
        @include('admin.blocks._docs_button_block', [
            'text' => 'Распечатать доп.сог. '.( (isset($task) && $task->tax_type) || (int)Settings::getSettings()['my_status'] ? 'с печатью' : 'с подписью' ),
            'href' => 'convention?id='.$task->id.( (isset($task) && $task->tax_type) || (int)Settings::getSettings()['my_status'] ? '&stamp=true' : '&signature=true').'&tax_type='.$task->tax_type
        ])
    @endif
@endif