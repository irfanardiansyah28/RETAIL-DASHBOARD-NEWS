@extends('layouts.app')

@section('content')

<div class="container-fluid">

<h2 class="fw-bold mb-4">

Rule Performance Analytics

</h2>

<div class="card">

<div class="card-body">

<table class="table table-hover">

<thead>

<tr>

<th>Rule</th>
<th>Triggered</th>
<th>High</th>
<th>Medium</th>
<th>Low</th>

</tr>

</thead>

<tbody>

@forelse($rules as $rule)

<tr>

<td>

{{$rule->rule_name}}

</td>

<td>

<span class="badge bg-dark">

{{$rule->trigger_count}}

</span>

</td>

<td>

<span class="badge bg-danger">

{{$rule->high_risk_count}}

</span>

</td>

<td>

<span class="badge bg-warning text-dark">

{{$rule->medium_risk_count}}

</span>

</td>

<td>

<span class="badge bg-primary">

{{$rule->low_risk_count}}

</span>

</td>

</tr>

@empty

<tr>

<td colspan="5">

No data

</td>

</tr>

@endforelse

</tbody>

</table>

{{$rules->links()}}

</div>

</div>

</div>

@endsection