@if (isset($fields['parent_id']) && $fields['parent_id'])
    {{ $case ? __('subtasks') : __('subtask') }} <a href="{{ url('/admin/tasks/sub_task?id='.$fields['id']) }}">{{ $fields['name'] }}</a> {{ __('as part of this task') }} <a href="{{ url('/admin/tasks?id='.$fields['parent_id']) }}">{{ $fields['parent_name'] }}</a>
@else
    {{ __('for this task') }} <a href="{{ url('/admin/tasks?id='.$fields['id']) }}">«{{ $fields['name'] }}»</a>
@endif
