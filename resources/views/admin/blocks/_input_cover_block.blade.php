<?php
if (isset($addAttr) && count($addAttr)) {
    $attrString = '';
    foreach ($addAttr as $attrName => $attrValue) {
        $attrString .= $attrName.'="'.$attrValue.'" ';
    }
}
?>

@if ((isset($addClass) && $addClass) || (isset($addAttr) && $attrString))
    <div class="{{ $addClass }}" {!! isset($attrString) ? $attrString : '' !!}>
@endif

@if (isset($label) && $label)
    <div class="description input-label">
        {{ $label }}
        @if (isset($star) && $star)
            <span class="star">*</span>
        @endif
    </div>
@endif
<div class="form-group has-feedback has-feedback-left {{ count($errors) && $errors->has($name) ? "has-error" : '' }}">
    {!! $content !!}
    @if ((isset($icon) && $icon) && (!isset($label) || !$label))
        <div class="form-control-feedback">
            <i class="{{ count($errors) && $errors->has($name) ? 'text-danger-800 '.$icon : $icon }} text-muted"></i>
        </div>
    @endif
    @include('admin.blocks._input_error_block')
</div>

@if ((isset($addClass) && $addClass) || (isset($addAttr) && $attrString))
    </div>
@endif