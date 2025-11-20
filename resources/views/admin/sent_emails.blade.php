@extends('layouts.admin')

@section('content')

    @include('admin.blocks._modal_delete_block',['modalId' => 'delete-modal', 'function' => 'delete-sent-email', 'head' => __('Are you sure you want to delete this E-mail')])

    <div class="panel panel-flat">
        <div class="panel-heading">
            <h3 class="panel-title pull-left">{{ __('Sent emails') }}</h3>
        </div>
        <div class="panel-body">
            <table class="table datatable-basic table-items">
                <tr>
                    <th class="text-center">Id</th>
                    <th class="text-center">{{ __('Whom') }}</th>
                    <th class="text-center">{{ __('From whom') }}</th>
                    <th class="text-center">{{ __('Content') }}</th>
                    <th class="text-center">{{ __('Departure time') }}</th>
                    <th class="delete">{{ __('Delete') }}</th>
                </tr>
                @foreach ($data['emails'] as $email)
                    <tr role="row" id="{{ 'email_'.$email->id }}">
                        <td class="id">{{ $email->id }}</td>
                        <td class="text-center"><a href="{{ url('/admin/sent-emails?id='.$email->id) }}">{{ $email->email }}</a></td>
                        <td class="text-center">@include('admin.blocks._cropped_content_block',['croppingContent' => $email->html, 'length' => 100])</td>
                        <td class="text-center">@include('admin.blocks._email_user_block',['email' => $email])</td>
                        <td class="text-center">{{ $email->created_at }}</td>
                        <td class="delete"><span del-data="{{ $email->id }}" modal-data="delete-modal" class="glyphicon glyphicon-remove-circle"></span></td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
