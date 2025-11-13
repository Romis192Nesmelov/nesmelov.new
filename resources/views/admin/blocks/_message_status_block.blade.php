<?php
$labels = ['bg-danger' => __('The task is overdue'),'bg-grey-400' => __('Time is running out'),'bg-blue' => __('Task has been created'),'bg-success-400' => __('Status changed')];
$k = 1;
foreach ($labels as $label => $description) {
    if ($status == $k) break;
    $k++;
}
?>
<span class="label {{ $label }}">{{ $description }}</span>
