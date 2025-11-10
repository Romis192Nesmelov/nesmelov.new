<div class="dropdown-content-heading">
    @if (count($messages))
        <a id="seen-all" href="#">Пометить все как прочитанные</a>
    @endif
</div>
<ul class="media-list dropdown-content-body">
    @foreach ($messages as $message)
        <li class="media" id="message{{ $message->id }}">
            <a href="/admin/tasks{{ $message->sub_task_id ? '/sub_task' : '' }}?id={{ $message->sub_task_id ? $message->sub_task_id : $message->task_id }}" class="media-heading">
                <span class="text-semibold">{{ $message->task->customer->name.' - «'.($message->sub_task_id ? $message->subTask->name : $message->task->name).'»' }}</span>
                <span class="media-annotation pull-right">{{ $message->created_at->format('d.m.Y') }}</span>
            </a>
            <span class="text-muted">{!! $message->message !!}</span>
        </li>
    @endforeach
</ul>

<div class="dropdown-content-footer">
    <a href="/admin/messages" data-popup="tooltip" title="Все сообщения"><i class="icon-menu display-block"></i></a>
</div>