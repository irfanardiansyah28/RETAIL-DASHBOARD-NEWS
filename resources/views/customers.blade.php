@extends('layouts.app')

@section('content')

<h1>Customers</h1>

<table class="table table-bordered">

<tr>

    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>City</th>

</tr>

@foreach($customers as $c)

<tr>

    <td>
        {{ $c->first_name }}
        {{ $c->last_name }}
    </td>

    <td>{{ $c->email }}</td>

    <td>{{ $c->phone }}</td>

    <td>{{ $c->city }}</td>

</tr>

@endforeach

</table>

@endsection