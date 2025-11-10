@foreach ($values as $value)
    <div class="form-group {{ isset($addClass) ? $addClass : '' }} {{ $errors && $errors->has($name) ? 'has-error' : '' }}">
        <div class="col-md-{{ isset($colMd) ? $colMd : '12' }}">
            <input class="radio-input" value="{{ $value['val'] }}" type="radio" name="{{ $name }}" {{ $activeValue == $value['val'] || (count($errors) && $value['val'] == old($name)) ? 'checked' : '' }}>
            <span class="control-label">{!! $value['descript'] !!}</span>
            @if ($errors && $errors->has($name))
                <div class="form-control-feedback">
                    <i class="icon-cancel-circle2"></i>
                </div>
            @endif
        </div>
    </div>
@endforeach
@if ($errors && $errors->has($name))
    <div class="form-group has-feedback has-error">
        <span class="help-block">{{ $errors->first($name) }}</span>
    </div>
@endif