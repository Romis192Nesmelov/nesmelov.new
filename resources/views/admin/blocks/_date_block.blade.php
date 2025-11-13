<div class="form-group has-feedback has-feedback-left {{ count($errors) && $errors->has($name) ? "has-error" : '' }} {{ $addClass ?? '' }}" {!! $attrString ?? '' !!}>
    @if (isset($label))
        <div class="description input-label">{{ $label }}</div>
    @endif
    <div class="input-group">
        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
        <input type="text" name="{{ $name }}" class="form-control daterange-single" value="{{ !count($errors) ? date('d.m.Y', $value) : old($name) }}" {{ isset($disabled) && $disabled ? 'disabled=disabled' : '' }}>
    </div>

    @if ($errors && $errors->has($name))
        {{--<div class="form-control-feedback">--}}
            {{--<i class="icon-cancel-circle2"></i>--}}
        {{--</div>--}}
        <span class="help-block">{{ $errors->first($name) }}</span>
    @endif
</div>
