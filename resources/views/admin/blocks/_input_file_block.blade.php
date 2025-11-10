<div class="form-group has-feedback {{ $errors && $errors->has($name) ? 'has-error' : '' }}">
    @if (isset($label))
        <label class="col-md-12 col-sm-12 col-xs-12">{{ $label }}</label>
    @endif
    <div class="col-md-12">
        <input {{ isset($inputId) ? 'id='.$inputId : '' }} type="file" name="{{ $name }}" class="file-styled">
        @include('admin.blocks._input_error_block')
    </div>
</div>