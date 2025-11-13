<?php
$labels = ['bg-success-400' => __('Read it'),'bg-danger' => __('Not read'),'bg-grey-400' => __('Deleted')];
$k = 0;
?>

@foreach ($labels as $label => $description)
    @if ($active_to_owner == $k)
        <span class="label {{ $label }}" style="margin-bottom: 5px;">{{ $description.' '.__('responsible for the execution') }}</span><br>
    @endif

    @if ($active_to_user == $k)
        <span class="label {{ $label }}">{{ $description.' '.__('as a performer') }}</span>
    @endif

    <?php $k++; ?>
@endforeach
