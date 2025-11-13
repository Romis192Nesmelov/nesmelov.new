@extends('layouts.admin')

@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h3 class="panel-title">{{ __('Landing\'s chapters') }}</h3>
        </div>
        <div class="panel-body">
            <table class="table datatable-basic table-items">
                <tr>
                    <th class="text-center">id</th>
                    <th class="text-center">{{ __('Image') }}</th>
                    <th class="text-center">{{ __('Name in the menu') }}</th>
                    <th class="text-center">{{ __('Status') }}</th>
                    <th class="text-center">{{ __('Last edition') }}</th>
                    <th class="text-center">{{ __('Created') }}</th>
                </tr>
                @foreach ($data['chapters'] as $chapter)
                    <tr role="row" id="{{ 'chapter_'.$chapter->id }}">
                        <td class="id">{{ $chapter->id }}</td>
                        <td class="image"><a class="img-preview" href="{{ asset($chapter->image) }}"><img src="{{ asset($chapter->image) }}" /></a></td>
                        <td class="text-center head"><a href="/admin/chapters/{{ $chapter->eng }}">{{ $chapter->rus }}</a></td>
                        <td class="text-center">@include('admin.blocks._status_block', ['status' => $chapter->active, 'trueLabel' => __('Active'), 'falseLabel' => __('Not active')])</td>
                        <td class="text-center">{{ $chapter->updated_at }}</td>
                        <td class="text-center">{{ $chapter->created_at }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
