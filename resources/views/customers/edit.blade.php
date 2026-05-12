@extends('layouts.app')

@section('content')

<div class="container">

    <h1 class="mb-4">

        Edit Customer

    </h1>

    <form method="POST" action="/customers/{{ $customer->customer_id }}">

        @csrf
        @method('PUT')

        <input type="text" name="first_name" value="{{ $customer->first_name }}" class="form-control mb-3">

        <input type="text" name="last_name" value="{{ $customer->last_name }}" class="form-control mb-3">

        <input type="text" name="phone" value="{{ $customer->phone }}" class="form-control mb-3">

        <input type="email" name="email" value="{{ $customer->email }}" class="form-control mb-3">

        <input type="text" name="street" value="{{ $customer->street }}" class="form-control mb-3">

        <input type="text" name="city" value="{{ $customer->city }}" class="form-control mb-3">

        <input type="text" name="state" value="{{ $customer->state }}" class="form-control mb-3">

        <input type="text" name="zip_code" value="{{ $customer->zip_code }}" class="form-control mb-3">

        <button class="btn btn-primary">

            Update Customer

        </button>

    </form>

</div>

@endsection