<?php

if ($status) {
    $label = 'success';
    $status = $trueLabel;
} else {
    $label = 'warning';
    $status = $falseLabel;
}
?>

<span class="label label-{{ $label }}">{{ $status }}</span>