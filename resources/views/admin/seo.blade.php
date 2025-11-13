@extends('layouts.admin')

@section('content')
    <div class="panel panel-flat">
        <div class="panel-body">
            <form class="form-horizontal" action="{{ url('/admin/seo') }}" method="post">
                @csrf
                <div class="panel-body">
                    @include('admin.blocks._input_block', [
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'placeholder' => 'Title',
                        'value' => $data['seo']['title']
                    ])
                </div>
                <div class="panel-heading">
                    <h4 class="panel-title">{{ __('Meta-tags') }}</h4>
                </div>
                <div class="panel-body">
                    @foreach($data['metas'] as $meta => $params)
                        @if ($params['name'] == 'description' || $params['name'] == 'keywords' || $params['property'] == 'og:description')
                            @include('admin.blocks._textarea_block', [
                                'label' => $params['name'] ? 'name="'.$params['name'].'"' : 'property="'.$params['property'].'"',
                                'name' => $meta,
                                'value' => $data['seo'][$meta],
                                'simple' => true
                            ])
                        @else
                            @include('admin.blocks._input_block', [
                                'label' => $params['name'] ? 'name="'.$params['name'].'"' : 'property="'.$params['property'].'"',
                                'name' => $meta,
                                'type' => 'text',
                                'placeholder' => $params['name'] ? 'name="'.$params['name'].'"' : 'property="'.$params['property'].'"',
                                'value' => $data['seo'][$meta]
                            ])
                        @endif
                    @endforeach
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    @include('admin.blocks._button_block', ['type' => 'submit', 'icon' => ' icon-floppy-disk', 'text' => trans('admin_content.save'), 'addClass' => 'pull-right'])
                </div>
            </form>
        </div>
    </div>
@endsection
