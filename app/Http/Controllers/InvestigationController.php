<?php

namespace App\Http\Controllers;

use App\Models\InvestigationCase;
use App\Models\User;
use Illuminate\Http\Request;

class InvestigationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        /*
        default:
        hide resolved case
        */

        $status =
        $request->status
        ??
        'active';

        $cases =
        InvestigationCase::with([
            'riskFlag',
            'investigator'
        ])

        ->when(
            $search,
            function($query) use($search){

                $query->where(function($q) use($search){

                    $q->where(
                        'case_number',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'title',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhereHas(
                        'riskFlag',
                        function($risk)
                        use($search){

                            $risk->where(
                                'risk_type',
                                'like',
                                "%{$search}%"
                            );

                        }
                    );

                });

            }
        )

        ->when(
            $status!='all',
            function($query)
            use($status){

                if(
                    $status=='active'
                ){

                    $query->whereNotIn(
                        'status',
                        ['Resolved']
                    );

                }

                else{

                    $query->where(
                        'status',
                        $status
                    );

                }

            }
        )

        ->latest()

        ->paginate(15)

        ->withQueryString();

        return view(
            'investigation.index',
            compact(
                'cases',
                'search',
                'status'
            )
        );
    }

    public function show($id)
    {
        $case=
        InvestigationCase::with([
            'riskFlag',
            'investigator'
        ])
        ->findOrFail($id);

        $users=
        User::all();

        return view(
            'investigation.show',
            compact(
                'case',
                'users'
            )
        );
    }

    public function assign(
        Request $request,
        $id
    ){

        $case=
        InvestigationCase::findOrFail($id);

        $case->update([

            'assigned_to'=>
            $request->assigned_to

        ]);

        return back()
        ->with(
            'success',
            'Case assigned'
        );
    }

    public function updateStatus(
        Request $request,
        $id
    ){

        $case=
        InvestigationCase::findOrFail($id);

        $case->status=
        $request->status;

        $case->investigation_note=
        $request->note;

        $case->save();

        return back()
        ->with(
            'success',
            'Case updated'
        );
    }
}