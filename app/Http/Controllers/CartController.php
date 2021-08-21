<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::all();
        $categories = Category::all();
        $menus = Product::latest()->paginate(10);
        return view('cart.index')->with('customers', $customers)->with('menus', $menus)->with('categories',$categories);
       
    }

    public function plus(Request $request)
    {
        $product_id = $request->product_id;
        $user_id = auth()->user()->id;

        $query = DB::table('user_cart')->updateOrInsert(
                    ['product_id' => $product_id],
                    [
                        'user_id' => $user_id,
                        'product_id' => $product_id,
                        'quantity' => \DB::raw('quantity + 1')
                    ]
                );
         if(!$query){
             return response('Error');
         }
        return response('Good');

    }

    public function minus(Request $request)
    {
        $product_id = $request->product_id;
                $user_id = auth()->user()->id;

                $query = DB::table('user_cart')->updateOrInsert(
                            ['product_id' => $product_id],
                            [
                                'user_id' => $user_id,
                                'product_id' => $product_id,
                                'quantity' => \DB::raw('quantity - 1')
                            ]
                        );
                 if(!$query){
                     return response('Error');
                 }
                return response('Good');
    }

    public function delete(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id'
        ]);
        $request->user()->cart()->detach($request->product_id);

        return response('', 204);
    }

    public function empty(Request $request)
    {
      $user_id = auth()->user()->id;
      $query  = \DB::table('user_cart')
                ->where('user_id', '=',$user_id)
                ->delete();
         if(!$query){
             return response('Cannot Cancel Sale');
         }
        return response('Sale Canceled');
    }
    public function add(Request $request)
    {
        $menu_id =$request->menu_id;
        $user_id = auth()->user()->id;
        $qty = '1';

        $query = \DB::table('user_cart')->updateOrInsert(
            ['product_id' => $menu_id],
            [
                'user_id' => $user_id,
                'product_id' => $menu_id,
                'quantity' => \DB::raw('quantity + 1')
            ]
        );
        if(!$query){
            return response('Error');
        }
       return response('Good');
    }

    public function getList()
    {
        $i =0;
        $total =0;
        $output = "";
        $words = new \NumberFormatter("En", \NumberFormatter::SPELLOUT);
        $lists = \DB::table('products')
                    ->join('user_cart', 'products.id', '=', 'user_cart.product_id')
                    ->select('products.*', 'user_cart.*')
                    ->get();
            foreach ($lists as $key => $list) {
                $i++;
                $total += $list->price * $list->quantity;
                $output.='<tr>'.
                '<td>'.$i.'</td>'.
                '<td>'.$list->name.'</td>'.
                '<td>'.$list->price.'</td>'.
                '<td>'.$list->quantity.'</td>'.
                '<td>'.number_format($list->price * $list->quantity, 2).'</td>'.
                '<td>
                    <div class="btn-group">
                                <button id="" onclick="plus('.$list->id.')" class="btn btn-info btn-sm">
                                <i class="fa fa-plus-circle"></i></button><button id="" onclick="minus('.$list->id.')" class="btn btn-danger btn-sm delete">
                                <i class="fa fa-minus-circle"></i></button>
                     </div></td>'.
                '<tr>';
            }

            $output.= '<tr>'.
                       '<td>Total</td>'.
                       '<td colspan="2">&#8358;'.number_format($total,2).'</td>'.
                       '<td colspan="5">'.strtoupper($words->format($total)).'</td>'.
                     '</tr>';


        return response($output);
    }


    public function filterMeal(Request $request)
    {
        $category = $request->category;
        $output = "";
        $meals = \DB::table('products')
                    ->where('category_id', $category)
                    ->get();
            foreach ($meals as $key => $meal) {
               $output.='<li class="nav-item">'.
                    '<button onclick="addtoCart('.$meal->id.')" class="btn btn-success btn-sm">
                      <i class="fa fa-plus-circle"></i> 
                    </button>'.
                    '<strong>'.$meal->name.'</strong>'.
                      '<span class="float-right badge bg-primary">&#8358; '.number_format($meal->price,2).'</span>'.
                  '</li>';
            }

            if (!$meals) {
                return response('No Meal Found');
            }

        return response($output);
    }

public function saveCart(Request $request)
{
    $user_id = auth()->user()->id;
    
    $max = Order::max('id');
    $invoice = str_pad($max +1, 4, '0', STR_PAD_LEFT);
    $carts = \DB::table('products')
                    ->join('user_cart', 'products.id', '=', 'user_cart.product_id')
                    ->select('products.price', 'user_cart.*')
                    ->get();


    foreach ($carts as $cart) {
        Order::create([
             'invoice_no' => $invoice,
             'customer_id' =>$request->customer,
             'user_id' =>$user_id,
             'amount' => $cart->price * $cart->quantity,
             'qty' => $cart->quantity,
             'product_id' => $cart->product_id
        ]);

    }
    \DB::table('user_cart')
    		->where('user_id', '=',$user_id)
    		->delete();
	}
public function saveCartPrint(Request $request)
{
    $user_id = auth()->user()->id;
    
    $max = Order::max('id');
    $invoice = str_pad($max +1, 4, '0', STR_PAD_LEFT);
    $carts = \DB::table('products')
                    ->join('user_cart', 'products.id', '=', 'user_cart.product_id')
                    ->select('products.price', 'user_cart.*')
                    ->get();


    foreach ($carts as $cart) {
        Order::create([
             'invoice_no' => $invoice,
             'customer_id' =>$request->customer,
             'user_id' =>$user_id,
             'amount' => $cart->price * $cart->quantity,
             'qty' => $cart->quantity,
             'product_id' => $cart->product_id
        ]);

    }
    \DB::table('user_cart')
    		->where('user_id', '=',$user_id)
    		->delete();
            return response($invoice);
	}


    public function getInvoice($invoice)
    {
        $order = Order::find($invoice);
        $sum = Order::where('invoice_no', $invoice)->sum('amount');
        $user = User::find(auth()->user()->id);

        $data = \DB::table('orders')
                    ->leftjoin('products', 'products.id', '=', 'orders.product_id')
                    ->select('orders.*','products.*')
                    ->where('orders.invoice_no', $invoice)
                    ->get();
                    
                    // dd($order->getCustomerName());
                    // dd($user->getFullname());
        return view('invoice.index', [
                    'invoice' => $invoice,
                    'customer' => $order->getCustomerName(),
                    'user' => $user->getFullname(),
                    'date' => $order->created_at,
                    'data' => $data,
                    'sum' => $sum
        ]);
    }
	
}