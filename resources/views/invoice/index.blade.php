@extends('layouts.normal')

@section('title', 'Order Invoice')
@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
@endsection
@section('content')
      <div class="container-fluid">
        <div class="row">
          <div class="col-12"> 
           <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>
                    <i class="fas fa-globe"></i> {{ config('app.name') }}.
                    <small class="float-right">{{ date('d/m/Y') }}</small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                  Customer
                  <address>
                    <strong>{{$customer}}</strong>
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  User
                  <address>
                    <strong>{{$user}}</strong>
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <b>Invoice #{{$invoice}}</b>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th>Qty</th>
                      <th>Product</th>
                      <th>Unit Price</th>
                      <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $item)
                    <tr>
                      <td>{{$item->qty}}</td>
                      <td>{{$item->name}}</td>
                      <td>&#8358;{{number_format($item->price,2)}}</td>
                      <td>&#8358; {{number_format($item->amount,2)}}</td>
                    </tr>
                    @endforeach
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <div class="row">
                <!-- /.col -->
                <div class="col-6">
                  <br>
                  <h3>Thank You</h3>
                </div>
                <div class="col-6">
                  <p class="lead">Amount Due {{ date('d/m/Y') }}</p>

                  <div class="table-responsive">
                    <table class="table">
                      <tr>
                        <th style="width:50%">Subtotal:</th>
                        <td>&#8358; {{number_format($sum,2)}}</td>
                      </tr>
                      <tr>
                        <th>Total:</th>
                        <td>&#8358; {{number_format($sum,2)}}</td>
                      </tr>
                    </table>
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- this row will not appear when printing -->
              <div class="row no-print">
                <div class="col-12">
                  <button class="btn btn-default float-right" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
                </div>
              </div>
            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
      @endsection