@extends('layouts.app')

@section('content')

<div class="container">

    <h1 class="mb-4">

        Activity Logs

    </h1>

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <table class="table table-bordered">

                <thead>

                    <tr>

                        <th>User</th>

                        <th>Activity</th>

                        <th>Date</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($logs as $log)

                    <tr>

                        <td>

                            {{ $log->user_name }}

                        </td>

                        <td>

                            {{ $log->activity }}

                        </td>

                        <td>

                            {{ $log->created_at }}

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

            <div class="mt-3">

                {{ $logs->links() }}

            </div>

        </div>

    </div>

</div>

@endsection