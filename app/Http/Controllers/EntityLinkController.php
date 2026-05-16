<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class EntityLinkController extends Controller
{
    public function index()
    {
        if(auth()->user()->role !== 'admin'){
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | SAME PHONE PREFIX
        |--------------------------------------------------------------------------
        */

        $phoneGroups = DB::table('sales.customers')
            ->selectRaw("
                LEFT(
                    REPLACE(phone,' ',''),
                    3
                ) as phone_prefix,

                COUNT(*) as total
            ")
            ->whereNotNull('phone')
            ->where('phone','!=','')
            ->groupByRaw("
                LEFT(
                    REPLACE(phone,' ',''),
                    3
                )
            ")
            ->having('total','>',1)
            ->orderByDesc('total')
            ->get();

        $results=[];

        foreach($phoneGroups as $group){

            $customers=DB::table('sales.customers')

                ->select(
                    'customer_id',
                    'first_name',
                    'last_name',
                    'phone',
                    'city',
                    'risk_level',
                    'risk_score'
                )

                ->whereRaw("
                    LEFT(
                        REPLACE(phone,' ',''),
                        3
                    )=?
                ",[
                    $group->phone_prefix
                ])

                ->get();

            $cities=
            $customers
            ->pluck('city')
            ->filter()
            ->unique()
            ->values();

            $results[]=[

                'type'=>'Phone Prefix',

                'shared'=>
                $group->phone_prefix,

                'total'=>
                $group->total,

                'cities'=>
                $cities,

                'customers'=>
                $customers

            ];
        }

        return view(
            'entity-links.index',
            compact(
                'results'
            )
        );
    }
}