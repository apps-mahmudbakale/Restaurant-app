@extends('layouts.admin')

@section('title', 'Invoice Payment')
@section('content-header', 'Invoice Payment')

@section('content')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('store.payment') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="invoice">Invoice No</label>
                    <input type="text" name="invoice" readonly class="form-control @error('invoice') is-invalid @enderror"
                           id="invoice"
                           placeholder="Invoice" value="{{ $invoice }}">
                    @error('invoice')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="text" name="amount" readonly class="form-control @error('amount') is-invalid @enderror"
                           id="amount"
                           placeholder="Amount" value="{{ $sum }}">
                           &#8358; {{ number_format($sum,2)}} {{strtoupper($words)." NAIRA ONLY"}}
                    @error('amount')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <button class="btn btn-primary" type="submit">Sumbit Payment</button>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            bsCustomFileInput.init();
        });
    </script>
@endsection
