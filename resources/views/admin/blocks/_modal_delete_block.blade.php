<?php ob_start(); ?>
<div class="modal-body modal-delete" del-function="{{ $function }}" >
    <h3>{{ $head }}</h3>
</div>
<!-- Футер модального окна -->
<div class="modal-footer">
    @include('admin.blocks._button_block', ['type' => 'button', 'text' => 'Да', 'addClass' => 'delete-yes'])
    @include('admin.blocks._button_block', ['type' => 'button', 'text' => 'Нет', 'addAttr' => ['data-dismiss' => 'modal']])
</div>
@include('blocks._modal_block',['id' => $modalId, 'title' => trans('admin_content.warning'), 'content' => ob_get_clean()])