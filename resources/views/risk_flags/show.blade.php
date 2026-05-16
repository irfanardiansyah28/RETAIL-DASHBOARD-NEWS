@extends('layouts.app')

@section('content')

<div class="container-fluid">

<div class="d-flex justify-content-between mb-4">

<div>

<h1 class="fw-bold">

Risk Investigation

</h1>

<p class="text-muted">

Detailed fraud evidence

</p>

</div>

<a
href="/risk-flags"
class="btn btn-secondary"
>

Back

</a>

</div>


<div class="card border-0 shadow-sm mb-4">

<div class="card-body">

<div class="row">

<div class="col-md-3">

<small class="text-muted">
Risk Type
</small>

<h5>
{{ $risk->risk_type }}
</h5>

</div>


<div class="col-md-3">

<small class="text-muted">
Severity
</small>

<h5>

{{ $risk->severity }}

</h5>

</div>


<div class="col-md-3">

<small class="text-muted">
Module
</small>

<h5>

{{ $risk->module }}

</h5>

</div>


<div class="col-md-3">

<small class="text-muted">
Status
</small>

<h5>

{{ $risk->status }}

</h5>

</div>

</div>


<hr>


<h5>

{{ $risk->title }}

</h5>

<p>

{{ $risk->description }}

</p>

</div>

</div>



<div class="card border-0 shadow-sm">

<div class="card-body">

<h5 class="mb-4">

Investigation Evidence

</h5>


@if(count($details)==0)

<div class="alert alert-warning">

No detailed evidence available

</div>

@endif


@foreach($details as $item)

<div class="border rounded p-3 mb-3">

@foreach((array)$item as $key=>$value)

<div class="mb-1">

<strong>

{{ ucfirst(
str_replace(
'_',
' ',
$key
)
) }}

:

</strong>

{{ $value }}

</div>

@endforeach

</div>

@endforeach


</div>

</div>

</div>

@endsection