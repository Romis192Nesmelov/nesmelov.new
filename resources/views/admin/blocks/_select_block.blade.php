<div class="form-group has-feedback has-feedback-left {{ count($errors) && $errors->has($name) ? "has-error" : '' }} {{ isset($addClass) ? $addClass : '' }}" {!! isset($attrString) ? $attrString : '' !!}>
    @if (isset($label))
        <div class="description input-label">{{ $label }}</div>
    @endif
    <select name="{{ $name }}" class="form-control" {{ isset($disabled) && $disabled ? 'disabled=disabled' : '' }}>
        @if (is_array($values))
            @foreach ($values as $value => $options)
                <option value="{{ $value }}" {{ (!count($errors) ? $value == $selected : $value == old($name)) ? 'selected' : '' }}>{{ $options }}</option>
            @endforeach
        @else
            @foreach ($values as $value)
                <option {{ isset($value->ltd) ? 'ltd='.$value->ltd : '' }} value="{{ $value->id }}" {{ (!count($errors) ? $value->id == $selected : $value->id == old($name)) ? 'selected' : '' }}>{{ $value->name }}</option>
            @endforeach
        @endif
    </select>
    @include('admin.blocks._input_error_block')
</div>