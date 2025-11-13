<div class="sidebar-category">
    <div class="category-title">
        <h7>{{ $head }}</h7>
    </div>
    <div class="category-content">
        @if (count($tasks))
            @foreach ($tasks as $task)
                @if ( (isset($customer) && $task->customer->name != $customer) || !isset($customer))
                    <?php $customer = $task->customer->name; ?>
                    <p><a href="#{{ $prefixAnchor.'_'.$task->customer->slug }}">{{ $customer }}</a></p>
                @endif
            @endforeach
        @else
            <h6 class="text-center">{{ __('Not found') }}</h6>
        @endif
    </div>
</div>
