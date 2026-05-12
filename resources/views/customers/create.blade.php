@extends('layouts.app')

@section('content')

<div class="container">

    <h1 class="mb-4">

        Add Customer

    </h1>

    <form method="POST" action="/customers">

        @csrf

        <input type="text" name="first_name" class="form-control mb-3" placeholder="First Name">

        <input type="text" name="last_name" class="form-control mb-3" placeholder="Last Name">

        <input type="text" name="phone" class="form-control mb-3" placeholder="Phone">

        <input type="email" name="email" class="form-control mb-3" placeholder="Email">

        <input type="text" name="street" class="form-control mb-3" placeholder="Street">

        <input type="text" name="city" class="form-control mb-3" placeholder="City">

        <input type="text" name="state" class="form-control mb-3" placeholder="State">

        <input type="text" name="zip_code" class="form-control mb-3" placeholder="Zip Code">

        <button class="btn btn-primary">

            Save Customer

        </button>

    </form>

</div>

@endsection