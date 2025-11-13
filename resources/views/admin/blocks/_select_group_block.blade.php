<div class="{{ $addClass ?? '' }} form-group has-feedback {{ count($errors) && $errors->has($name) ? 'has-error' : '' }}">
    @if (isset($label))
        <label class="control-label col-md-12 text-semibold">{{ $label }}</label>
    @endif
    <select {{ isset($disabled) && $disabled ? 'disabled' : '' }} name="{{ $name }}" class="form-control">
        @foreach ($groups as $group => $items)
            <optgroup label="{{ $group }}">
                @foreach ($items as $value => $options)
                    <option value="{{ $value }}" {{ (!count($errors) ? $value == $selected : $value == old($name)) ? 'selected' : '' }}>{{ $options }}</option>
                @endforeach
            </optgroup>
        @endforeach
    </select>
    @include('admin.blocks._input_error_block')
</div>
