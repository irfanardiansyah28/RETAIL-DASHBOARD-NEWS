@extends('layouts.app')

@section('content')

<div class="container">

<div class="d-flex justify-content-between align-items-center mb-4">

    <h1>
        Orders
    </h1>

    <div class="d-flex gap-2">

        <a
            href="/orders-export"
            class="btn btn-success"
        >
            Export Excel
        </a>

        <a
            href="/orders/create"
            class="btn btn-primary"
        >
            + Create Order
        </a>

    </div>

</div>

<form method="GET" class="mb-4">

    <input
        type="text"
        id="live-search"
        name="search"
        class="form-control"
        placeholder="Search Order ID..."
        value="{{ $search }}"
    >

</form>

<div class="card shadow-sm border-0">

<div class="card-body">

<table class="table table-bordered align-middle">

<thead class="table-dark">

<tr>
    <th>Order ID</th>
    <th>Date</th>
    <th>Customer</th>
    <th>Total</th>
    <th>Status</th>
    <th width="330">Action</th>
</tr>

</thead>

<tbody id="orders-table-body">

@forelse($orders as $o)

<tr>

<td>
    #{{ $o->order_id }}
</td>

<td>
    {{ $o->order_date }}
</td>

<td>
    {{ $o->customer_name }}
</td>

<td>
    Rp {{ number_format($o->total_amount,0,',','.') }}
</td>

<td>

@if($o->status=='Pending')

<span class="badge bg-warning text-dark">
Pending
</span>

@elseif($o->status=='Paid')

<span class="badge bg-primary">
Paid
</span>

@elseif($o->status=='Shipped')

<span class="badge bg-info text-dark">
Shipped
</span>

@elseif($o->status=='Completed')

<span class="badge bg-success">
Completed
</span>

@elseif($o->status=='Cancelled')

<span class="badge bg-danger">
Cancelled
</span>

@else

<span class="badge bg-secondary">
{{ $o->status }}
</span>

@endif

</td>

<td>

<div class="d-flex gap-2 flex-wrap">

<a
href="/orders/{{ $o->order_id }}"
class="btn btn-info btn-sm"
>
Detail
</a>

<a
href="/orders/{{ $o->order_id }}/invoice-preview"
class="btn btn-secondary btn-sm"
>
Preview
</a>

<a
href="/orders/{{ $o->order_id }}/invoice"
class="btn btn-dark btn-sm"
>
PDF
</a>

@if($o->status=='Pending')

<form
method="POST"
action="/orders/{{ $o->order_id }}/pay"
class="orders-form"
>

@csrf

<button class="btn btn-success btn-sm">
Pay
</button>

</form>

<form
method="POST"
action="/orders/{{ $o->order_id }}/cancel"
class="cancel-form"
>

@csrf

<button class="btn btn-danger btn-sm">
Cancel
</button>

</form>

@endif


@if($o->status=='Paid')

<form
method="POST"
action="/orders/{{ $o->order_id }}/ship"
class="ship-form"
>

@csrf

<button class="btn btn-primary btn-sm">
Ship
</button>

</form>

@endif


@if($o->status=='Shipped')

<form
method="POST"
action="/orders/{{ $o->order_id }}/complete"
class="complete-form"
>

@csrf

<button class="btn btn-success btn-sm">
Complete
</button>

</form>

@endif

</div>

</td>

</tr>

@empty

<tr>

<td colspan="6" class="text-center">

No orders found

</td>

</tr>

@endforelse

</tbody>

</table>

<div class="mt-4">

{{ $orders->links() }}

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

function bindPayButtons(){

document.querySelectorAll('.orders-form')

.forEach(form=>{

form.addEventListener('submit',function(e){

e.preventDefault();

Swal.fire({

title:'Payment Confirmation',

text:'Mark order as PAID ?',

icon:'question',

showCancelButton:true,

confirmButtonColor:'#16a34a'

})

.then((result)=>{

if(result.isConfirmed){

form.submit();

}

});

});

});

}

function bindCancelButtons(){

document.querySelectorAll('.cancel-form')

.forEach(form=>{

form.addEventListener('submit',function(e){

e.preventDefault();

Swal.fire({

title:'Cancel Order?',

icon:'warning',

showCancelButton:true,

confirmButtonColor:'#dc2626'

})

.then((result)=>{

if(result.isConfirmed){

form.submit();

}

});

});

});

}

function bindShipButtons(){

document.querySelectorAll('.ship-form')

.forEach(form=>{

form.addEventListener('submit',function(e){

e.preventDefault();

Swal.fire({

title:'Ship Order?',

icon:'info',

showCancelButton:true,

confirmButtonColor:'#2563eb'

})

.then((result)=>{

if(result.isConfirmed){

form.submit();

}

});

});

});

}

function bindCompleteButtons(){

document.querySelectorAll('.complete-form')

.forEach(form=>{

form.addEventListener('submit',function(e){

e.preventDefault();

Swal.fire({

title:'Complete Order?',

icon:'success',

showCancelButton:true,

confirmButtonColor:'#16a34a'

})

.then((result)=>{

if(result.isConfirmed){

form.submit();

}

});

});

});

}

bindPayButtons();
bindCancelButtons();
bindShipButtons();
bindCompleteButtons();

</script>

@endsection