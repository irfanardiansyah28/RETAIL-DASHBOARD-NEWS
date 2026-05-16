@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h1 class="fw-bold">
            System Settings
        </h1>

        <p class="text-muted mb-0">
            Manage global RetailOps configuration
        </p>
    </div>

</div>

<div class="card">

    <div class="card-body">

        <form
            method="POST"
            action="{{ route('settings.update') }}"
        >

            @csrf
            @method('PUT')

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">
                        Store Name
                    </label>

                    <input
                        type="text"
                        name="store_name"
                        class="form-control"
                        value="{{ old('store_name', $settings['store_name'] ?? 'RetailOps') }}"
                        required
                    >

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">
                        Currency
                    </label>

                    <input
                        type="text"
                        name="currency"
                        class="form-control"
                        value="{{ old('currency', $settings['currency'] ?? 'Rp') }}"
                        required
                    >

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">
                        Low Stock Threshold
                    </label>

                    <input
                        type="number"
                        name="low_stock_threshold"
                        class="form-control"
                        value="{{ old('low_stock_threshold', $settings['low_stock_threshold'] ?? 10) }}"
                        min="1"
                        required
                    >

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">
                        Tax Percentage
                    </label>

                    <input
                        type="number"
                        name="tax_percentage"
                        class="form-control"
                        value="{{ old('tax_percentage', $settings['tax_percentage'] ?? 11) }}"
                        min="0"
                        max="100"
                        step="0.01"
                        required
                    >

                </div>

            </div>

            <div class="d-flex justify-content-end mt-3">

                <button class="btn btn-primary px-4">
                    Save Settings
                </button>

            </div>

        </form>

    </div>

</div>

@endsection