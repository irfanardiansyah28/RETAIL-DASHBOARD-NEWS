@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="fw-bold">
                Edit Scenario Rule
            </h1>

            <p class="text-muted mb-0">
                Update scenario condition and risk flag action
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
                action="/scenario-rules/{{ $rule->id }}"
            >
                @csrf
                @method('PUT')

                @include('scenario-rules.form', [
                    'rule' => $rule
                ])

                <button class="btn btn-primary">
                    Update Rule
                </button>

            </form>

        </div>

    </div>

</div>

@endsection