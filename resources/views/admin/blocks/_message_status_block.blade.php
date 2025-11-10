<?php
$labels = ['bg-danger' => 'Задача просрочена','bg-grey-400' => 'Время на исходе','bg-blue' => 'Задача создана','bg-success-400' => 'Изменен статус'];
$k = 1;
foreach ($labels as $label => $description) {
    if ($status == $k) break;
    $k++;
}
?>
<span class="label {{ $label }}">{{ $description }}</span>