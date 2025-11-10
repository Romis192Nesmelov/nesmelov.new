<div class="modal-content">
    <!-- Заголовок модального окна -->
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h1>{!! isset($title) && $title ? $title : '' !!}</h1>
    </div>
    <!-- Основное содержимое модального окна -->
    @if (isset($message))
        <div class="modal-body"><h2>{!! $message !!}</h2></div>
        <!-- Футер модального окна -->
        <div class="modal-footer">
            @include('blocks._button_block', ['type' => 'button', 'text' => trans('content.close'), 'addAttr' => ['data-dismiss' => 'modal']])
        </div>
    @elseif (isset($content))
        {!! $content !!}
    @endif
</div>