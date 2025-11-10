<div class="{{ isset($addClass) && $addClass ? $addClass : '' }} panel panel-flat panel-collapsed">
    <div class="panel-heading">
        <h4 class="panel-title">Кастомное доп.соглашение</h4>
        @include('admin.blocks._heading_elements_block')
    </div>
    <div class="panel-body">
        @include('admin.blocks._textarea_block', [
            'name' => 'convention',
            'value' => $task->convention ? $task->convention : view('docs.blocks._convention_body_block',['item' => $task,'noPrint' => true])->render(),
            'height' => 1000
        ])
        @include('admin.blocks._checkbox_block', ['label' => 'Сохранить доп.соглашение', 'name' => 'save_convention','checked' => $task->save_convention])
    </div>
</div>