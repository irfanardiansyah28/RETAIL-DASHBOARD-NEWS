@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="fw-bold">
                Edit Customer
            </h1>

            <p class="text-muted mb-0">
                Update customer information
            </p>
        </div>

        <a
            href="/customers/{{ $customer->customer_id }}"
            class="btn btn-secondary"
        >
            Back
        </a>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <form
                action="/customers/{{ $customer->customer_id }}"
                method="POST"
            >

                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-semibold">
                            First Name
                        </label>

                        <input
                            type="text"
                            name="first_name"
                            class="form-control"
                            placeholder="Enter first name"
                            value="{{ old('first_name', $customer->first_name) }}"
                            required
                        >

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-semibold">
                            Last Name
                        </label>

                        <input
                            type="text"
                            name="last_name"
                            class="form-control"
                            placeholder="Enter last name"
                            value="{{ old('last_name', $customer->last_name) }}"
                            required
                        >

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-semibold">
                            Phone Number
                        </label>

                        <input
                            type="text"
                            name="phone"
                            class="form-control"
                            placeholder="Enter phone number, example: 08123456789"
                            value="{{ old('phone', $customer->phone) }}"
                        >

                        @if(empty($customer->phone))
                            <small class="text-danger">
                                Phone number is empty. Please complete this field.
                            </small>
                        @endif

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-semibold">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            placeholder="Enter email address, example: customer@email.com"
                            value="{{ old('email', $customer->email) }}"
                        >

                        @if(empty($customer->email))
                            <small class="text-danger">
                                Email is empty. Please complete this field.
                            </small>
                        @endif

                    </div>

                    <div class="col-md-12 mb-3">

                        <label class="form-label fw-semibold">
                            Street Address
                        </label>

                        <input
                            type="text"
                            name="street"
                            class="form-control"
                            placeholder="Enter street address, example: 15 Brown St."
                            value="{{ old('street', $customer->street) }}"
                        >

                        @if(empty($customer->street))
                            <small class="text-danger">
                                Street address is empty. Please complete this field.
                            </small>
                        @endif

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-semibold">
                            City
                        </label>

                        <input
                            type="text"
                            name="city"
                            class="form-control"
                            placeholder="Enter city"
                            value="{{ old('city', $customer->city) }}"
                        >

                        @if(empty($customer->city))
                            <small class="text-danger">
                                City is empty. Please complete this field.
                            </small>
                        @endif

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-semibold">
                            State
                        </label>

                        <input
                            type="text"
                            name="state"
                            class="form-control"
                            placeholder="Enter state / province"
                            value="{{ old('state', $customer->state) }}"
                        >

                        @if(empty($customer->state))
                            <small class="text-danger">
                                State is empty. Please complete this field.
                            </small>
                        @endif

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-semibold">
                            Zip Code
                        </label>

                        <input
                            type="text"
                            name="zip_code"
                            class="form-control"
                            placeholder="Enter zip code"
                            value="{{ old('zip_code', $customer->zip_code) }}"
                        >

                        @if(empty($customer->zip_code))
                            <small class="text-danger">
                                Zip code is empty. Please complete this field.
                            </small>
                        @endif

                    </div>

                </div>

                <div class="d-flex gap-2 mt-3">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Update Customer
                    </button>

                    <a
                        href="/customers/{{ $customer->customer_id }}"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection