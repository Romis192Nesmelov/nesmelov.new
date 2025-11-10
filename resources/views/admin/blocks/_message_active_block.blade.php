<?php
$labels = ['bg-success-400' => 'Прочитано','bg-danger' => 'Не прочитано','bg-grey-400' => 'Удалено'];
$k = 0;
?>

@foreach ($labels as $label => $description)
    @if ($active_to_owner == $k)
        <span class="label {{ $label }}" style="margin-bottom: 5px;">{{ $description }} ответственным за исполнение</span><br>
    @endif

    @if ($active_to_user == $k)
        <span class="label {{ $label }}">{{ $description }} исполнителем</span>
    @endif

    <?php $k++; ?>
@endforeach