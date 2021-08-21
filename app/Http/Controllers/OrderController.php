<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderStoreRequest;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request) {
        $orders = Order::distinct('invoice_no')->get();

        return view('orders.index', compact('orders'));
    }

    public function store(OrderStoreRequest $request)
    {
        $order = Order::create([
            'customer_id' => $request->customer_id,
            'user_id' => $request->user()->id,
        ]);

        $cart = $request->user()->cart()->get();
        foreach ($cart as $item) {
            $order->items()->create([
                'price' => $item->price,
                'quantity' => $item->pivot->quantity,
                'product_id' => $item->id,
            ]);
            $item->quantity = $item->quantity - $item->pivot->quantity;
            $item->save();
        }
        $request->user()->cart()->detach();
        $order->payments()->create([
            'amount' => $request->amount,
            'user_id' => $request->user()->id,
        ]);
        return 'success';
    }
    public function show($invoice)
    {
        $orders = Order::all()->where('invoice_no', '=', $invoice);
        $order = Order::find($invoice);
        $user = User::find($order->user_id);
        $sum = Order::where('invoice_no', $invoice)->sum('amount');

        return view('orders.show', [
                'invoice' => $invoice,
                'user' => $user->getFullname(),
                'customer' => $order->getCustomerName(),
                'date' => $order->created_at,
                'data' => $orders,
                'sum' => $sum
        ]);
    }

    public function addPayment($invoice)
    {
        dd($invoice);
    }
}
