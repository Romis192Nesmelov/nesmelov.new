<?php ob_start(); ?>
<form class="form-horizontal" action="/feedback" method="post">
    {{ csrf_field() }}
    <div class="modal-body">
{{--        @include('_input_block', [--}}
{{--            'label' => 'Перезвоним Вам в течении дня',--}}
{{--            'name' => 'phone',--}}
{{--            'type' => 'tel',--}}
{{--            'placeholder' => '+7(___)__-__-__',--}}
{{--        ])--}}
        @include('blocks._input_block', [
            'label' => 'Как к Вам можно обращаться?',
            'name' => 'name',
            'type' => 'text',
            'placeholder' => 'Напишите Ваше имя',
        ])

        @include('blocks._textarea_block', [
            'label' => 'Ваше сообщение:',
            'name' => 'message',
            'placeholder' => 'Напишите Ваше сообщение',
        ])
    </div>
    <div class="modal-footer">
        @include('blocks._button_block', ['type' => 'submit', 'text' => 'Отправить', 'disabled' => true])
        @include('blocks._agree_block')
    </div>
</form>
<?php $content = ob_get_clean(); ?>
@include('blocks._modal_block',['id' => 'feedback_modal', 'title' => 'Форма обратной связи', 'content' => $content, 'addClass' => $addClass ?? null])