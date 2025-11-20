@extends('layouts.admin')

@section('content')

    @include('admin.blocks._modal_delete_block',['modalId' => 'delete-modal', 'function' => 'delete-bank', 'head' => __('Do you really want to delete this bank?')])

    <div class="panel panel-flat">
        <div class="panel-heading">
            <h3 class="panel-title pull-left">{{ __('Banks') }}</h3>
        </div>
        <div class="panel-body">
            <table class="table table-striped">
                <tr>
                    <th class="id">Id</th>
                    <th class="text-center">{{ __('Name') }}</th>
                    <th class="text-center hidden-xs">{{ __('Date and time of creation') }}</th>
                    <th class="delete hidden-xs">{{ __('Delete') }}</th>
                </tr>
                @foreach ($data['banks'] as $bank)
                    <tr role="row" id="{{ 'bank_'.$bank->id }}">
                        <td class="id">{{ $bank->id }}</td>
                        <td class="text-center"><a href="{{ url('/admin/banks?id='.$bank->id) }}">{{ $bank->name }}</a></td>
                        <td class="text-center hidden-xs">{{ $bank->created_at }}</td>
                        <td class="delete hidden-xs"><span del-data="{{ $bank->id }}" modal-data="delete-modal" class="glyphicon glyphicon-remove-circle"></span></td>
                    </tr>
                @endforeach
            </table>
            @include('admin.blocks._add_button_block',['href' => 'banks/add', 'text' => __('Add a bank')])
        </div>
    </div>
@endsection
