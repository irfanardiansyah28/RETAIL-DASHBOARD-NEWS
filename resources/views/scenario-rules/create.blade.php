@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="fw-bold">
                Create Scenario Rule
            </h1>

            <p class="text-muted mb-0">
                Define IF condition THEN risk flag action
            </p>
        </div>

        <a
            href="/scenario-rules"
            class="btn btn-secondary"
        >
            Back
        </a>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <form
                method="POST"
                action="/scenario-rules"
            >
                @csrf

                @include('scenario-rules.form', [
                    'rule' => null
                ])

                <button class="btn btn-primary">
                    Save Rule
                </button>

            </form>

        </div>

    </div>

</div>

@endsection