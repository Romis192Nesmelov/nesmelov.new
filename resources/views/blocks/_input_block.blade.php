@if (isset($label))
    <label>{{ $label }}</label>
@endif
<input class="valid" {{ isset($min) ? 'min='.$min : '' }} {{ isset($max) && $max ? 'max='.$max : '' }} name="{{ $name }}" type="{{ $type }}" placeholder="{{ isset($placeholder) && $placeholder ? $placeholder : '' }}" value="{{ isset($value) ? $value : '' }}">
<div class="error {{ $name }}"></div>