<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label fw-semibold">
            Rule Name
        </label>

        <input
            type="text"
            name="rule_name"
            class="form-control"
            value="{{ old('rule_name', $rule->rule_name ?? '') }}"
            placeholder="Example: High Risk Customer"
            required
        >

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label fw-semibold">
            Status
        </label>

        <div class="form-check mt-2">
            <input
                type="checkbox"
                name="is_active"
                value="1"
                class="form-check-input"
                id="is_active"
                {{ old('is_active', $rule->is_active ?? true) ? 'checked' : '' }}
            >

            <label
                class="form-check-label"
                for="is_active"
            >
                Active
            </label>
        </div>

    </div>

</div>

<div class="card border-0 shadow-sm mb-4">

    <div class="card-body">

        <h5 class="fw-bold mb-3">
            IF Condition
        </h5>

        <div class="row">

            <div class="col-md-4 mb-3">

                <label class="form-label fw-semibold">
                    Field
                </label>

                <select
                    name="condition_field"
                    class="form-control"
                    required
                >
                    @php
                        $field = old('condition_field', $rule->condition_field ?? '');
                    @endphp

                    <option value="">Select Field</option>
                    <option value="risk_score" {{ $field == 'risk_score' ? 'selected' : '' }}>Customer Risk Score</option>
                    <option value="total_orders_90" {{ $field == 'total_orders_90' ? 'selected' : '' }}>Total Orders Last 90 Days</option>
                    <option value="cancelled_orders_90" {{ $field == 'cancelled_orders_90' ? 'selected' : '' }}>Cancelled Orders Last 90 Days</option>
                    <option value="total_spent_90" {{ $field == 'total_spent_90' ? 'selected' : '' }}>Total Spent Last 90 Days</option>
                    <option value="open_risk_flags" {{ $field == 'open_risk_flags' ? 'selected' : '' }}>Open Risk Flags</option>
                    <option value="missing_profile" {{ $field == 'missing_profile' ? 'selected' : '' }}>Missing Profile</option>
                </select>

            </div>

            <div class="col-md-4 mb-3">

                <label class="form-label fw-semibold">
                    Operator
                </label>

                @php
                    $operator = old('operator', $rule->operator ?? '');
                @endphp

                <select
                    name="operator"
                    class="form-control"
                    required
                >
                    <option value="">Select Operator</option>
                    <option value=">=" {{ $operator == '>=' ? 'selected' : '' }}>>=</option>
                    <option value="<=" {{ $operator == '<=' ? 'selected' : '' }}><=</option>
                    <option value=">" {{ $operator == '>' ? 'selected' : '' }}>></option>
                    <option value="<" {{ $operator == '<' ? 'selected' : '' }}><</option>
                    <option value="=" {{ $operator == '=' ? 'selected' : '' }}>=</option>
                    <option value="!=" {{ $operator == '!=' ? 'selected' : '' }}>!=</option>
                </select>

            </div>

            <div class="col-md-4 mb-3">

                <label class="form-label fw-semibold">
                    Value
                </label>

                <input
                    type="text"
                    name="condition_value"
                    class="form-control"
                    value="{{ old('condition_value', $rule->condition_value ?? '') }}"
                    placeholder="Example: 70"
                    required
                >

                <small class="text-muted">
                    For Missing Profile use: 1
                </small>

            </div>

        </div>

    </div>

</div>

<div class="card border-0 shadow-sm mb-4">

    <div class="card-body">

        <h5 class="fw-bold mb-3">
            THEN Action
        </h5>

        <div class="row">

            <div class="col-md-4 mb-3">

                <label class="form-label fw-semibold">
                    Risk Type
                </label>

                <input
                    type="text"
                    name="risk_type"
                    class="form-control"
                    value="{{ old('risk_type', $rule->risk_type ?? '') }}"
                    placeholder="Example: Scenario High Risk Customer"
                    required
                >

            </div>

            <div class="col-md-4 mb-3">

                <label class="form-label fw-semibold">
                    Severity
                </label>

                @php
                    $severity = old('severity', $rule->severity ?? 'Medium');
                @endphp

                <select
                    name="severity"
                    class="form-control"
                    required
                >
                    <option value="High" {{ $severity == 'High' ? 'selected' : '' }}>High</option>
                    <option value="Medium" {{ $severity == 'Medium' ? 'selected' : '' }}>Medium</option>
                    <option value="Low" {{ $severity == 'Low' ? 'selected' : '' }}>Low</option>
                </select>

            </div>

            <div class="col-md-4 mb-3">

                <label class="form-label fw-semibold">
                    Title
                </label>

                <input
                    type="text"
                    name="title"
                    class="form-control"
                    value="{{ old('title', $rule->title ?? '') }}"
                    placeholder="Example: Customer needs review"
                    required
                >

            </div>

            <div class="col-md-12 mb-3">

                <label class="form-label fw-semibold">
                    Description
                </label>

                <textarea
                    name="description"
                    class="form-control"
                    rows="4"
                    placeholder="Explain why this scenario creates risk flag"
                >{{ old('description', $rule->description ?? '') }}</textarea>

            </div>

        </div>

    </div>

</div>