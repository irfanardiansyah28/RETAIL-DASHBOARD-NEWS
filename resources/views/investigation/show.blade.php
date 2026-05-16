@extends('layouts.app')

@section('content')

<div class="container-fluid">

<div class="card">

<div class="card-body">

<h3>

{{$case->case_number}}

</h3>

<p>

{{$case->description}}

</p>

<hr>

<h5>

Risk Detail

</h5>

<div>

Type:

{{$case->riskFlag->risk_type}}

</div>

<div>

Severity:

{{$case->riskFlag->severity}}

</div>

<div>

Status:

{{$case->status}}

</div>

<hr>

<form
method="POST"
action="/investigation/{{$case->id}}/assign"
>

@csrf

<select
name="assigned_to"
class="form-control mb-3"
>

<option>

Assign Investigator

</option>

@foreach($users as $u)

<option
value="{{$u->id}}"
>

{{$u->name}}

</option>

@endforeach

</select>

<button class="btn btn-primary">

Assign

</button>

</form>

<hr>

<form
method="POST"
action="/investigation/{{$case->id}}/status"
>

@csrf

<select
name="status"
class="form-control mb-3"
>

<option>Open</option>

<option>Investigating</option>

<option>Resolved</option>

<option>Escalated</option>

</select>

<textarea
name="note"
class="form-control mb-3"
rows="5"
placeholder="Investigation notes"
></textarea>

<button
class="btn btn-success"
>

Update Case

</button>

</form>

</div>

</div>

</div>

@endsection