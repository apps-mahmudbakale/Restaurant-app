@extends('layouts.admin')

@section('title', 'Payment List')
@section('content-header', 'Payment List')
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
                    <th>Processed By</th>
                    <th>Amount</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $payment)
                <tr>
                    <td>{{$payment->id}}</td>
                    <td>#{{$payment->invoice_no}}</td>
                    <td>{{$payment->getCustomerName()}}</td>
                    <td>{{$payment->user->getFullname()}}</td>
                    <td>&#8358; {{number_format($payment->amount,2)}}</td>
                    <td>{{$payment->created_at}}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th></th>
                    <th><strong>Total</strong></th>
                    <th>&#8358; {{ number_format($sum, 2) }}</th> 
                    <th></th>
                    <th>{{strtoupper($words)}}</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection

