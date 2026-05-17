@extends('layouts.app')

@section('content')

<div
    class="container-fluid d-flex justify-content-center align-items-center"
    style="min-height:80vh;"
>

<div
    class="card border-0 shadow-lg"
    style="
    width:450px;
    border-radius:25px;
    overflow:hidden;
    "
>

<div
class="p-4 text-center text-white"
style="
background:
linear-gradient(
135deg,
#2563eb,
#1d4ed8
);
"
>

<h2 class="fw-bold mb-1">
RetailOps
</h2>

<p class="mb-0">
Retail Fraud & Analytics Platform
</p>

</div>

<div class="card-body p-5">

@if(session('status'))

<div class="alert alert-success">

{{ session('status') }}

</div>

@endif


<form
method="POST"
action="{{ route('login') }}"
>

@csrf

<div class="mb-3">

<label class="form-label fw-semibold">

Email

</label>

<input
type="email"
name="email"
class="form-control"
value="{{ old('email') }}"
required
autofocus
>

@error('email')

<div class="text-danger mt-1">

{{ $message }}

</div>

@enderror

</div>


<div class="mb-3">

<label class="form-label fw-semibold">

Password

</label>

<input
type="password"
name="password"
class="form-control"
required
>

@error('password')

<div class="text-danger mt-1">

{{ $message }}

</div>

@enderror

</div>


<div
class="
d-flex
justify-content-between
align-items-center
mb-4
"
>

<div class="form-check">

<input
class="form-check-input"
type="checkbox"
name="remember"
id="remember"
>

<label
class="form-check-label"
for="remember"
>

Remember me

</label>

</div>


@if(
Route::has(
'password.request'
)
)

<a
href="{{ route('password.request') }}"
class="small"
>

Forgot Password?

</a>

@endif

</div>


<button
class="btn btn-primary w-100"
>

Login

</button>

</form>

</div>

</div>

</div>

@endsection