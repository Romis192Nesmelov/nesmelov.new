<div class="col-md-{{ isset($col) && $col ? $col : '12' }} {{ isset($addClass) && $addClass ? $addClass : '' }}">
    <label class="checkbox-inline">
        <input class="styled" type="checkbox" name="{{ $name }}" {{ !count($errors) ? (isset($checked) && $checked ? 'checked=checked' : '') : (old($name) == 'on' ? 'checked=checked' : '') }} {{ isset($disabled) && $disabled ? 'disabled=disabled' : '' }}>
        @if (isset($label) && $label)
            {!! $label !!}
        @endif
    </label>
</div>