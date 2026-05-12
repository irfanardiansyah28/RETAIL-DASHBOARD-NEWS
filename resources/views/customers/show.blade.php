@extends('layouts.app')

@section('content')

<div class="container">

    <h1 class="mb-4">

        Customer Detail

    </h1>

    <div class="card p-4">

        <p><strong>Name:</strong>
            {{ $customer->first_name }}
            {{ $customer->last_name }}
        </p>

        <p><strong>Email:</strong>
            {{ $customer->email }}
        </p>

        <p><strong>Phone:</strong>
            {{ $customer->phone }}
        </p>

        <p><strong>Address:</strong>
            {{ $customer->street }},
            {{ $customer->city }},
            {{ $customer->state }},
            {{ $customer->zip_code }}
        </p>

    </div>

</div>

@endsection