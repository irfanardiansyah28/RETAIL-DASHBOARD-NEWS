@extends('layouts.app')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div class="d-flex gap-2">

            <form
                method="POST"
                action="/customers/run-segmentation"
            >
                @csrf

                <button class="btn btn-dark">
                    <i class="bi bi-diagram-3"></i>
                    Run Segmentation
                </button>
            </form>

            <form
                method="POST"
                action="/customers/run-risk-score"
            >
                @csrf

                <button class="btn btn-danger">
                    <i class="bi bi-shield-exclamation"></i>
                    Run Risk Score
                </button>
            </form>

        </div>

        <div class="text-center">

            <h1 class="fw-bold mb-0">
                Customers
            </h1>

            <p class="text-muted mb-0">
                Manage customer information
            </p>

        </div>

        <a
            href="/customers/create"
            class="btn btn-primary"
        >
            Add Customer
        </a>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <form method="GET" class="mb-4">

                <input
                    type="text"
                    id="live-search"
                    name="search"
                    class="form-control"
                    placeholder="Search customer..."
                    value="{{ $search }}"
                >

            </form>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-dark">

                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Segment</th>
                            <th>Risk</th>
                            <th>City</th>
                            <th width="240">Action</th>
                        </tr>

                    </thead>

                    <tbody id="customers-table-body">

                        @forelse($customers as $c)

                            <tr>

                                <td>
                                    {{ $c->first_name }}
                                    {{ $c->last_name }}
                                </td>

                                <td>
                                    {{ $c->email ?? '-' }}
                                </td>

                                <td>
                                    {{ $c->phone ?? '-' }}
                                </td>

                                <td>
                                    @if(($c->segment ?? 'New') == 'VIP')

                                        <span class="badge bg-warning text-dark">
                                            VIP
                                        </span>

                                    @elseif(($c->segment ?? 'New') == 'Regular')

                                        <span class="badge bg-success">
                                            Regular
                                        </span>

                                    @elseif(($c->segment ?? 'New') == 'Dormant')

                                        <span class="badge bg-secondary">
                                            Dormant
                                        </span>

                                    @elseif(($c->segment ?? 'New') == 'High Risk')

                                        <span class="badge bg-danger">
                                            High Risk
                                        </span>

                                    @else

                                        <span class="badge bg-primary">
                                            New
                                        </span>

                                    @endif
                                </td>

                                <td>
                                    @if(($c->risk_level ?? 'Low') == 'High')

                                        <span class="badge bg-danger">
                                            High - {{ $c->risk_score ?? 0 }}
                                        </span>

                                    @elseif(($c->risk_level ?? 'Low') == 'Medium')

                                        <span class="badge bg-warning text-dark">
                                            Medium - {{ $c->risk_score ?? 0 }}
                                        </span>

                                    @else

                                        <span class="badge bg-success">
                                            Low - {{ $c->risk_score ?? 0 }}
                                        </span>

                                    @endif
                                </td>

                                <td>
                                    {{ $c->city ?? '-' }}
                                </td>

                                <td>
                                    <div class="d-flex gap-2">

                                        <a
                                            href="/customers/{{ $c->customer_id }}"
                                            class="btn btn-info btn-sm"
                                        >
                                            Detail
                                        </a>

                                        <a
                                            href="/customers/{{ $c->customer_id }}/edit"
                                            class="btn btn-warning btn-sm"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            action="/customers/{{ $c->customer_id }}"
                                            method="POST"
                                            class="delete-form"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-danger btn-sm"
                                            >
                                                Delete
                                            </button>

                                        </form>

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="7"
                                    class="text-center text-muted"
                                >
                                    No customers found
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $customers->links() }}
            </div>

        </div>

    </div>

</div>

<script>

function bindDeleteButton(){

    document
    .querySelectorAll('.delete-form')
    .forEach(form=>{

        form.addEventListener(
            'submit',
            function(e){

                e.preventDefault();

                Swal.fire({
                    title:'Delete Customer?',
                    text:'Data cannot be restored',
                    icon:'warning',
                    showCancelButton:true,
                    confirmButtonText:'Delete',
                    confirmButtonColor:'#dc2626'
                })
                .then((result)=>{

                    if(result.isConfirmed){
                        form.submit();
                    }

                });

            }
        );

    });

}

bindDeleteButton();

document
.getElementById('live-search')
.addEventListener(
    'keyup',
    function(){

        let search=this.value;

        fetch(
            `/customers-live-search?search=${encodeURIComponent(search)}`
        )

        .then(response=>response.json())

        .then(data=>{

            let html='';

            if(data.length===0){

                html=`

                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            No customers found
                        </td>
                    </tr>

                `;

            }

            data.forEach(customer=>{

                let segmentBadge='';

                if(customer.segment=='VIP'){

                    segmentBadge=`
                        <span class="badge bg-warning text-dark">
                            VIP
                        </span>
                    `;

                }

                else if(customer.segment=='Regular'){

                    segmentBadge=`
                        <span class="badge bg-success">
                            Regular
                        </span>
                    `;

                }

                else if(customer.segment=='Dormant'){

                    segmentBadge=`
                        <span class="badge bg-secondary">
                            Dormant
                        </span>
                    `;

                }

                else if(customer.segment=='High Risk'){

                    segmentBadge=`
                        <span class="badge bg-danger">
                            High Risk
                        </span>
                    `;

                }

                else{

                    segmentBadge=`
                        <span class="badge bg-primary">
                            New
                        </span>
                    `;

                }

                let riskBadge='';

                if(customer.risk_level=='High'){

                    riskBadge=`
                        <span class="badge bg-danger">
                            High - ${customer.risk_score ?? 0}
                        </span>
                    `;

                }

                else if(customer.risk_level=='Medium'){

                    riskBadge=`
                        <span class="badge bg-warning text-dark">
                            Medium - ${customer.risk_score ?? 0}
                        </span>
                    `;

                }

                else{

                    riskBadge=`
                        <span class="badge bg-success">
                            Low - ${customer.risk_score ?? 0}
                        </span>
                    `;

                }

                html +=`

                    <tr>

                        <td>
                            ${customer.first_name ?? ''}
                            ${customer.last_name ?? ''}
                        </td>

                        <td>
                            ${customer.email ?? '-'}
                        </td>

                        <td>
                            ${customer.phone ?? '-'}
                        </td>

                        <td>
                            ${segmentBadge}
                        </td>

                        <td>
                            ${riskBadge}
                        </td>

                        <td>
                            ${customer.city ?? '-'}
                        </td>

                        <td>
                            <div class="d-flex gap-2">

                                <a
                                    href="/customers/${customer.customer_id}"
                                    class="btn btn-info btn-sm"
                                >
                                    Detail
                                </a>

                                <a
                                    href="/customers/${customer.customer_id}/edit"
                                    class="btn btn-warning btn-sm"
                                >
                                    Edit
                                </a>

                                <form
                                    action="/customers/${customer.customer_id}"
                                    method="POST"
                                    class="delete-form"
                                >
                                    <input
                                        type="hidden"
                                        name="_token"
                                        value="{{ csrf_token() }}"
                                    >

                                    <input
                                        type="hidden"
                                        name="_method"
                                        value="DELETE"
                                    >

                                    <button class="btn btn-danger btn-sm">
                                        Delete
                                    </button>

                                </form>

                            </div>
                        </td>

                    </tr>

                `;

            });

            document
            .getElementById(
                'customers-table-body'
            )
            .innerHTML=html;

            bindDeleteButton();

        });

    }
);

</script>

@endsection