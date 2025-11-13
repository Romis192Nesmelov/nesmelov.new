@extends('layouts.admin')

@section('content')

    @if (auth()->user()->is_admin)
        @include('admin.blocks._modal_delete_block',['modalId' => 'delete-modal', 'function' => 'delete-customer', 'head' => __('Are you sure you want to delete this customer?')])
    @endif

    <div class="panel panel-flat">
        <div class="panel-heading">
            <h3 class="panel-title pull-left">{{ __('Customers') }}</h3>
        </div>
        <div class="panel-body">
            <table class="table datatable-basic table-items">
                <tr>
                    <th class="text-center">{{ __('Name') }}</th>
                    <th class="text-center">E-mail</th>
                    <th class="text-center">{{ __('Phone') }}</th>
                    <th class="text-center">{{ __('Contact person') }}</th>
                    <th class="text-center">{{ auth()->user()->is_admin ? __('Status') : __('Legal entity') }}</th>
                    <th class="delete">{{ auth()->user()->is_admin ? __('Delete') : __('Description') }}</th>
                </tr>
                @foreach ($data['customers'] as $customer)
                    <tr role="row" id="{{ 'customer_'.$customer->id }}">
                        <td class="text-center head"><a href="/admin/customers/{{ $customer->slug }}">{{ $customer->name }}</a></td>
                        <td class="text-center head">@include('admin.blocks._email_href_block',['email' => $customer->email])</td>
                        <td class="text-center">@include('admin.blocks._phone_href_block',['phone' => $customer->phone])</td>
                        <td class="text-center">{{ $customer->contact_person }}</td>
                        <td class="text-center">
                            @if (auth()->user()->is_admin)
                                @include('admin.blocks._customers_type_block',['type' => $customer->type])
                            @else
                                @include('admin.blocks._customer_form_block',['customer' => $customer])
                            @endif
                        </td>
                        @if (auth()->user()->is_admin)
                            <td class="text-center delete"><span del-data="{{ $customer->id }}" modal-data="delete-modal" class="glyphicon glyphicon-remove-circle"></span></td>
                        @else
                            <td width="15%" class="text-center">@include('admin.blocks._cropped_content_block',['croppingContent' => $customer->description, 'length' => 100])</td>
                        @endif
                    </tr>
                @endforeach
            </table>
            @include('admin.blocks._add_button_block',['href' => 'customers/add', 'text' => __('Add a customer')])
        </div>
    </div>
@endsection
