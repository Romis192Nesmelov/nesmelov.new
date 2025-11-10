@if (count($errors) && $errors->has($name))
    <span class="error help-block">{{ $errors->first($name) }}</span>
@endif