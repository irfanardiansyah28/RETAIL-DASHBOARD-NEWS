@extends('layouts.app')

@section('content')

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2 class="fw-bold">

Investigation Center

</h2>

<p class="text-muted">

Manage investigation workflow

</p>

</div>

</div>


<div class="card border-0 shadow-sm mb-4">

<div class="card-body">

<form method="GET">

<div class="row">

<div class="col-md-6">

<input
type="text"
name="search"
class="form-control"
placeholder="Search case number / title / risk..."
value="{{ $search }}"
>

</div>

<div class="col-md-4">

<select
name="status"
class="form-control"
>

<option
value="active"
{{ $status=='active'?'selected':'' }}
>

Active Only

</option>

<option
value="Open"
{{ $status=='Open'?'selected':'' }}
>

Open

</option>

<option
value="Investigating"
{{ $status=='Investigating'?'selected':'' }}
>

Investigating

</option>

<option
value="Escalated"
{{ $status=='Escalated'?'selected':'' }}
>

Escalated

</option>

<option
value="Resolved"
{{ $status=='Resolved'?'selected':'' }}
>

Resolved

</option>

<option
value="all"
{{ $status=='all'?'selected':'' }}
>

All

</option>

</select>

</div>

<div class="col-md-2 d-flex gap-2">

<button
class="btn btn-primary w-100"
>

Filter

</button>

<a
href="/investigation"
class="btn btn-secondary"
>

Reset

</a>

</div>

</div>

</form>

</div>

</div>


<div class="card border-0 shadow-sm">

<div class="card-body">

<table class="table table-hover">

<thead>

<tr>

<th>Case</th>
<th>Priority</th>
<th>Status</th>
<th>Assigned</th>
<th width="120"></th>

</tr>

</thead>

<tbody>

@forelse($cases as $case)

<tr>

<td>

<div class="fw-bold">

{{$case->case_number}}

</div>

<small>

{{$case->title}}

</small>

</td>

<td>

@if($case->priority=="High")

<span class="badge bg-danger">

High

</span>

@elseif($case->priority=="Medium")

<span class="badge bg-warning text-dark">

Medium

</span>

@else

<span class="badge bg-primary">

Low

</span>

@endif

</td>

<td>

@if($case->status=="Open")

<span class="badge bg-danger">

Open

</span>

@elseif($case->status=="Investigating")

<span class="badge bg-warning text-dark">

Investigating

</span>

@elseif($case->status=="Escalated")

<span class="badge bg-dark">

Escalated

</span>

@else

<span class="badge bg-success">

Resolved

</span>

@endif

</td>

<td>

{{$case->investigator->name ?? 'Unassigned'}}

</td>

<td>

<a
href="/investigation/{{$case->id}}"
class="btn btn-dark btn-sm"
>

Open

</a>

</td>

</tr>

@empty

<tr>

<td
colspan="5"
class="text-center text-muted"
>

No cases found

</td>

</tr>

@endforelse

</tbody>

</table>

<div class="mt-3">

{{$cases->links()}}

</div>

</div>

</div>

</div>

@endsection