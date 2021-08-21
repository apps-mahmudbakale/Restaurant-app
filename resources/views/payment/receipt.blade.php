<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <title>Purchase Receipt</title>
    </head>
    <body>
            <img src="./logo.png" alt="Logo" align="center">
            <p class="text-center">PURCHASE RECEIPT 
                <br>{{ config('app.name') }}
                <br>
                Date: <?php echo date('d/m/Y'); ?>
            </p>
                <p class="text-center text-success">
                <i class="fa fa-check-circle fa-5x"></i>
                </p>
                <br>
                <p class="text-center">Payment Received</p>
                <br>      
            <table class="table">
                <tbody>
                    <tr class="warning">
                        <td>Customer:</td>
                        <td>{{$customer}}</td>
                        <td>Amount Paid</td>
                        <td>&#8358; {{number_format($amount,)}}</td>
                    </tr>
                   
                    <tr>
                        <td>Order Invoice</td>
                        <td>#{{$invoice}}</td>
                        <td>Que No</td>
                        <td>{{$que}}</td>
                    </tr>
                </tbody>
            </table>
            <p>Processed By {{$user}}</p>
            <br>
            <p class="text-center">Thanks for your purchase!
                <br>{{config('app.name')}}</p>
        <button id="btnPrint" class="btn btn-primary"><i class="fa fa-print"></i> Print</button>
        <button onclick="window.history.back()" class="btn btn-danger"><i class="fa fa-arrow-left-circle"></i> Back</button>
        <script>
            const $btnPrint = document.querySelector("#btnPrint");
            $btnPrint.addEventListener("click", () => {
                window.print();
            });
        </script>
    </body>