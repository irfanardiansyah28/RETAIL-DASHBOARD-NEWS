@extends('layouts.app')

@section('content')

<div class="card-custom">

    <h2 class="mb-4">

        Add User

    </h2>

    <form
        action="{{ route('users.store') }}"
        method="POST"
    >

        @csrf

        <div class="mb-3">

            <label class="form-label">

                Name

            </label>

            <input
                type="text"
                name="name"
                class="form-control"
                required
            >

        </div>

        <div class="mb-3">

            <label class="form-label">

                Email

            </label>

            <input
                type="email"
                name="email"
                class="form-control"
                required
            >

        </div>

        <div class="mb-3">

            <label class="form-label">

                Password

            </label>

            <input
                type="password"
                name="password"
                class="form-control"
                required
            >

        </div>

        <div class="mb-3">

            <label class="form-label">

                Role

            </label>

            <select
                name="role"
                class="form-control"
            >

                <option value="admin">

                    Admin

                </option>

                <option value="staff">

                    Staff

                </option>

            </select>

        </div>

        <button
            type="submit"
            class="btn btn-primary"
        >

            Save User

        </button>

    </form>

</div>

@endsection