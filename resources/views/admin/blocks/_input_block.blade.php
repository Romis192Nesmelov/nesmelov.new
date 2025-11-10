@php ob_start(); @endphp
<input
        {{ isset($disabled) && $disabled ? 'disabled=disabled' : '' }}
        {{ isset($icon) && $icon && (!isset($label) || !$label) ? 'style=padding-left:35px' : '' }}
        {{ isset($step) && $step ? 'step='.$step : '' }}
        {{ isset($min) ? 'min='.$min : '' }}
        {{ isset($max) ? 'max='.$max : '' }}
        name="{{ $name }}"
        type="{{ $type }}"
        class="form-control"
        placeholder="{{ isset($placeholder) && $placeholder ? $placeholder : '' }}"
        value="{{ isset($value) && !count($errors) ? $value : (Session::has($name) ? Session::get($name) : old($name)) }}">
@include('admin.blocks._input_cover_block',['content' => ob_get_clean()])