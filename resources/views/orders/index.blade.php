@extends('layouts.admin')

@section('title', 'Orders List')
@section('content-header', 'Order List')
@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-7"></div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Invoice</th>
                    <th>Customer Name</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                <tr>
                    <td>{{$order->id}}</td>
                    <td>#{{$order->invoice_no}}</td>
                    <td>{{$order->getCustomerName()}}</td>
                    <td>
                        @if($order->payment == 0)
                         <span class="badge badge-danger">Not Paid</span>
                         @else
                         <span class="badge badge-danger">Not Paid</span>
                         @endif
                    </td>
                    <td>{{$order->created_at}}</td>
                    <td><a href="orders/view/{{$order->invoice_no}}" class="btn btn-primary"><i class="fa fa-eye"></i></a></td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    {{-- <th>{{ config('settings.currency_symbol') }} {{ number_format($total, 2) }}</th>
                    <th>{{ config('settings.currency_symbol') }} {{ number_format($receivedAmount, 2) }}</th> --}}
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection

