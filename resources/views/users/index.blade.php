@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h1 class="fw-bold">
        User Management
    </h1>

    <a
        href="{{ route('users.create') }}"
        class="btn btn-primary"
    >
        Add User
    </a>

</div>

<form method="GET" action="/users" class="mb-3">

    <div class="row">

        <div class="col-md-4">

            <input
                type="text"
                id="users-live-search"
                name="search"
                class="form-control"
                placeholder="Search name / email / role..."
                value="{{ $search ?? '' }}"
            >

        </div>

        <div class="col-md-2">

            <button class="btn btn-primary">
                Search
            </button>

        </div>

    </div>

</form>

<div class="card-custom">

    <table class="table table-hover align-middle">

        <thead>

            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th width="180">Action</th>
            </tr>

        </thead>

        <tbody id="users-table-body">

            @foreach($users as $user)

            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="badge bg-primary">
                        {{ $user->role }}
                    </span>
                </td>
                <td>
                    <div class="d-flex gap-2">

                        <a
                            href="{{ route('users.edit', $user->id) }}"
                            class="btn btn-warning btn-sm"
                        >
                            Edit
                        </a>

                        <form
                            action="{{ route('users.destroy', $user->id) }}"
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

            @endforeach

        </tbody>

    </table>

</div>

<script>

function bindDeleteConfirmation(){

    document.querySelectorAll('.delete-form')

    .forEach(form => {

        form.addEventListener('submit', function(e){

            e.preventDefault();

            Swal.fire({

                title: 'Are you sure?',

                text: "Data will be deleted permanently",

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#d33',

                cancelButtonColor: '#6b7280',

                confirmButtonText: 'Yes Delete'

            }).then((result) => {

                if(result.isConfirmed){

                    form.submit();

                }

            });

        });

    });

}

bindDeleteConfirmation();

document
.getElementById('users-live-search')
.addEventListener('keyup', function(){

    let search = this.value;

    fetch(`/users-live-search?search=${encodeURIComponent(search)}`)

    .then(response => response.json())

    .then(data => {

        let html = '';

        if(data.length === 0){

            html = `
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        No user found
                    </td>
                </tr>
            `;

        } else {

            data.forEach(user => {

                html += `
                    <tr>
                        <td>${user.id}</td>
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td>
                            <span class="badge bg-primary">
                                ${user.role}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">

                                <a
                                    href="/users/${user.id}/edit"
                                    class="btn btn-warning btn-sm"
                                >
                                    Edit
                                </a>

                                <form
                                    action="/users/${user.id}"
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
                `;

            });

        }

        document
        .getElementById('users-table-body')
        .innerHTML = html;

        bindDeleteConfirmation();

    });

});

</script>

@endsection