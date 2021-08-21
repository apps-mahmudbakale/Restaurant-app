@extends('layouts.admin')

@section('title', 'Open POS')
@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
@endsection
@section('content')
<style>
  #result
  {
      width:100%;
      display:none;
      margin-top:-1px;
      border-top:0px;
      overflow:hidden;
      border:1px #CDCDCD solid;
      background-color: white;
  }
</style>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-6">
        <div class="card">
            <div class="card-header">
              <select name="" id="customer" class="form-control"  style="width: 100%; border-radius: 3px;">
                <option value="0">Walking Customer</option>
                @foreach ($customers as $customer)
                  <option value="{{ $customer->id }}">{{$customer->first_name}} {{$customer->last_name}}</option>
                @endforeach
              </select>
            </div>
            <!-- /.card-header -->
            <div id="status"><br></div>
            <div class="card-body">
              <table class="table  table-striped">
                <thead>
                <tr>
                  <th>S/N</th>
                  <th>Item</th>
                  <th>Price</th>
                  <th>Quantity</th>
                  <th>Amount</th>
                  <th></th>
                </tr>
                </thead>
                <tbody id="cart-list">
                
                </tbody>
              </table>
              <br>
              
              <br>

              <div class="btn-group pull-right">
                  <button class="btn btn-danger" id="cancel"><i class="fa fa-times"></i> Cancel</button>
                  <button id='save' class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                  <button id="save_print" class="btn btn-info"><i class="fa fa-print"></i> Save And Print</button>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
        </div>
        <div class="col-md-6">
        <div class="card">
            <div class="card-header">
              <select name="" id="menu" class="form-control"  style="width: 100%; border-radius: 3px;">
                <option value="0">Meal Menu</option>
                @foreach ($categories as $category)
                  <option value="{{ $category->id }}">{{$category->name}}</option>
                @endforeach
              </select>
            </div>
            <!-- /.card-header -->
            <div id="status"><br></div>
            <div class="card-body">
              <ul class="nav flex-column" id="meal-menu">
                 @foreach ($menus as $menu)
                 <li class="nav-item">
                    <button onclick="addtoCart({{ $menu->id }})" class="btn btn-success btn-sm">
                      <i class="fa fa-plus-circle"></i> 
                    </button>
                      <strong>{{ $menu->name}}</strong>
                      <span class="float-right badge bg-primary">&#8358; {{ $menu->price }}</span>
                  </li>
                 @endforeach
              </ul>
              <br>
              {{ $menus->render() }}
            </div>
            <!-- /.card-body -->
          </div>
        </div>
        </div>
          <!-- /.card -->
    </div><!-- /.container-fluid -->
<script>
  
    var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let uri = 'cart/list'
          fetch(uri, {
            headers: {
              "Content-Type": "application/json",
              "Accept": "application/json, text-plain, */*",
              "X-Requested-With": "XMLHttpRequest",
              "X-CSRF-TOKEN": csrf
            },
            method: 'get',
            credentials: "same-origin"
          })
          .then((data)=> data.text())
          .then((data) => {
              console.log(data);
              document.querySelector('#cart-list').innerHTML=data;
          })
          .catch((error)=>{
              console.log(error);
          });

let menu = document.querySelector('#menu'), save = document.querySelector('#save'), print = document.querySelector('#save_print'), cancel = document.querySelector('#cancel'), customer = document.querySelector('#customer');
menu.addEventListener('change', (e)=>{
  let url = 'cart/filter';
  fetch(url, {
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json, text-plain, */*",
      "X-Requested-With": "XMLHttpRequest",
      "X-CSRF-TOKEN": csrf
    },
    method: 'post',
    credentials: "same-origin",
    body: JSON.stringify({
        category : menu.value
      })
  })
  .then((data)=> data.text())
  .then((data) => {
      console.log(data);
      document.querySelector('#meal-menu').innerHTML=data;
  })
  .catch((error)=>{
      console.log(error);
  })
});
save.addEventListener('click', (e)=>{
    var url = 'cart/save';
    fetch(url, {
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json, text-plain, */*",
      "X-Requested-With": "XMLHttpRequest",
      "X-CSRF-TOKEN": csrf
    },
    method: 'post',
    credentials: "same-origin",
    body: JSON.stringify({
	customer : customer.value
      })
  })
  .then((data)=> data.text())
  .then((data) => {
  	Swal.fire('Saved!', '', 'success');
      	cart();
      
  })
  .catch((error)=>{
      console.log(error);
  })	
});
print.addEventListener('click', (e)=>{
	var data = {customer: customer.value};
	axios.post('cart/print', data).then(res => {
	var route = "cart/invoice/"+res.data;
	   window.open(route,'_blank','toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400');
            console.log(res.data)
            cart();
	});
});
cancel.addEventListener('click', (e)=>{
  const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
          confirmButton: 'btn btn-success',
          cancelButton: 'btn btn-danger'
      },
      buttonsStyling: false
      })
      swalWithBootstrapButtons.fire({
      title: 'Are you sure?',
      text: "Do you really want to cancel?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, cancel it!',
      cancelButtonText: 'No Dont cancel it!',
      reverseButtons: true
      }).then((result) => {
      if (result.value) {
          axios.get('cart/empty').then(res => {
            Swal.fire(res.data, '', 'success');
                    console.log(res.data)
                    cart();
          });
      }
  })

});
</script>
  <script>
    function cart() {
      let url = 'cart/list'
      fetch(url, {
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json, text-plain, */*",
          "X-Requested-With": "XMLHttpRequest",
          "X-CSRF-TOKEN": csrf
        },
        method: 'get',
        credentials: "same-origin"
      })
      .then((data)=> data.text())
      .then((data) => {
          console.log(data);
          document.querySelector('#cart-list').innerHTML=data;
      })
      .catch((error)=>{
          console.log(error);
      })
    }
  function addtoCart(id) {
    let url = 'cart/add';
    fetch(url, {
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json, text-plain, */*",
        "X-Requested-With": "XMLHttpRequest",
        "X-CSRF-TOKEN": csrf
      },
      method: 'post',
      credentials: "same-origin",
      body: JSON.stringify({
        menu_id : id
      })
    })
    .then((data)=> data.text())
    .then((data) => {
        console.log(data);
        cart();
    })
    .catch((error)=>{
        console.log(error);
    })
  }
  function plus(id) {
    var data = {product_id: id};
      axios.post('cart/plus', data).then(res => {
                console.log(res.data)
                cart();
      });
  }
  function minus(id) {
    var data = {product_id: id};
    axios.post('cart/minus', data).then(res => {
              console.log(res.data);
              cart();
    });
  }
</script>
@endsection
