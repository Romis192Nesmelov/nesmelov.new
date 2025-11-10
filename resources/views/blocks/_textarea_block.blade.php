@if (isset($label))
    <label>{{ $label }}</label>
@endif
<textarea class="form-control" name="{{ $name }}" placeholder="{{ isset($placeholder) ? $placeholder : '' }}">{{ isset($value) ? $value : '' }}</textarea>
<div class="error {{ $name }}"></div>