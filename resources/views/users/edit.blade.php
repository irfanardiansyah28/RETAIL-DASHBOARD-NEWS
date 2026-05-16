@extends('layouts.app')

@section('content')

<div class="card-custom">

    <h2 class="mb-4">

        Edit User

    </h2>

    <form
        action="{{ route('users.update', $user->id) }}"
        method="POST"
    >

        @csrf
        @method('PUT')

        <div class="mb-3">

            <label class="form-label">

                Name

            </label>

            <input
                type="text"
                name="name"
                class="form-control"
                value="{{ $user->name }}"
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
                value="{{ $user->email }}"
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

                <option
                    value="admin"
                    {{ $user->role == 'admin' ? 'selected' : '' }}
                >

                    Admin

                </option>

                <option
                    value="staff"
                    {{ $user->role == 'staff' ? 'selected' : '' }}
                >

                    Staff

                </option>

            </select>

        </div>

        <button
            type="submit"
            class="btn btn-success"
        >

            Update User

        </button>

    </form>

</div>

@endsection