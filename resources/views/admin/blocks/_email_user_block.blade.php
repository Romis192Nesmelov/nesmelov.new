@if ($email->user_id)
    <a href="/admin/users?id={{ $email->user_id }}">{{ $email->user->email }}</a>
@else
    <span class="label label-warning">{{ __('Unknown') }}</span>
@endif
