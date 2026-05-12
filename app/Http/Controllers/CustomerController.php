<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{

    // LIST + SEARCH

    public function index(Request $request)
    {

        $search = $request->search;

        $customers = DB::table('sales.customers')

            ->when($search, function($query) use ($search) {

                $query->where(
                    'first_name',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'last_name',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'email',
                    'like',
                    "%{$search}%"
                );

            })

            ->paginate(10);

        return view(
            'customers.index',
            compact(
                'customers',
                'search'
            )
        );
    }

    // CREATE PAGE

    public function create()
    {

        return view('customers.create');
    }

    // STORE

    public function store(Request $request)
    {

        DB::table('sales.customers')

            ->insert([

                'first_name' =>
                    $request->first_name,

                'last_name' =>
                    $request->last_name,

                'phone' =>
                    $request->phone,

                'email' =>
                    $request->email,

                'street' =>
                    $request->street,

                'city' =>
                    $request->city,

                'state' =>
                    $request->state,

                'zip_code' =>
                    $request->zip_code

            ]);

        return redirect('/customers')

            ->with(
                'success',
                'Customer added successfully'
            );
    }

    // DETAIL

    public function show($id)
    {

        $customer = DB::table('sales.customers')

            ->where(
                'customer_id',
                $id
            )

            ->first();

        return view(
            'customers.show',
            compact('customer')
        );
    }

    // EDIT PAGE

    public function edit($id)
    {

        $customer = DB::table('sales.customers')

            ->where(
                'customer_id',
                $id
            )

            ->first();

        return view(
            'customers.edit',
            compact('customer')
        );
    }

    // UPDATE

    public function update(Request $request, $id)
    {

        DB::table('sales.customers')

            ->where(
                'customer_id',
                $id
            )

            ->update([

                'first_name' =>
                    $request->first_name,

                'last_name' =>
                    $request->last_name,

                'phone' =>
                    $request->phone,

                'email' =>
                    $request->email,

                'street' =>
                    $request->street,

                'city' =>
                    $request->city,

                'state' =>
                    $request->state,

                'zip_code' =>
                    $request->zip_code

            ]);

        return redirect('/customers')

            ->with(
                'success',
                'Customer updated successfully'
            );
    }

    // DELETE

    public function destroy($id)
    {

        DB::table('sales.customers')

            ->where(
                'customer_id',
                $id
            )

            ->delete();

        return redirect('/customers')

            ->with(
                'success',
                'Customer deleted successfully'
            );
    }
}