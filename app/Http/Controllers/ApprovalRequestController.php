<?php

namespace App\Http\Controllers;

use App\Models\ApprovalRequest;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApprovalRequestController extends Controller
{
    private function adminOnly()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Only admin can access approval workflow.');
        }
    }

    public function index(Request $request)
    {
        $this->adminOnly();

        $status = $request->status;

        $approvalRequests = ApprovalRequest::query()
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $pendingCount = ApprovalRequest::where('status', 'Pending')->count();
        $approvedCount = ApprovalRequest::where('status', 'Approved')->count();
        $rejectedCount = ApprovalRequest::where('status', 'Rejected')->count();

        return view(
            'approvals.index',
            compact(
                'approvalRequests',
                'status',
                'pendingCount',
                'approvedCount',
                'rejectedCount'
            )
        );
    }

    public function approve($id)
    {
        $this->adminOnly();

        $approval = ApprovalRequest::findOrFail($id);

        if ($approval->status !== 'Pending') {
            return back()->with('error', 'This request has already been processed');
        }

        if ($approval->request_type === 'Large Stock Decrease') {

            $payload = $approval->payload;

            DB::table('production.stocks')
                ->where('store_id', $payload['store_id'])
                ->where('product_id', $payload['product_id'])
                ->update([
                    'quantity' => $payload['new_quantity'],
                ]);

            StockMovement::create([
                'store_id' => $payload['store_id'],
                'product_id' => $payload['product_id'],
                'store_name' => $payload['store_name'],
                'product_name' => $payload['product_name'],
                'old_quantity' => $payload['old_quantity'],
                'new_quantity' => $payload['new_quantity'],
                'difference' => $payload['difference'],
                'user_id' => $approval->requested_by,
                'user_name' => $approval->requested_by_name,
                'notes' => 'Approved stock decrease request',
            ]);

            createRiskFlag(
                'Approved Large Stock Decrease',
                'High',
                'Stock',
                $payload['product_id'],
                'Large stock decrease approved',
                $payload['product_name']
                    .' at '
                    .$payload['store_name']
                    .' decreased by '
                    .abs($payload['difference'])
                    .' units.'
            );

            logActivity(
                'Approve Stock Decrease',
                'Approval',
                'Approved large stock decrease request #'.$approval->id
            );
        }

        $approval->update([
            'status' => 'Approved',
            'approved_by' => Auth::id(),
            'approved_by_name' => Auth::user()->name,
            'decision_note' => 'Approved',
            'decided_at' => now(),
        ]);

        return back()->with('success', 'Approval request approved successfully');
    }

    public function reject($id)
    {
        $this->adminOnly();

        $approval = ApprovalRequest::findOrFail($id);

        if ($approval->status !== 'Pending') {
            return back()->with('error', 'This request has already been processed');
        }

        $approval->update([
            'status' => 'Rejected',
            'approved_by' => Auth::id(),
            'approved_by_name' => Auth::user()->name,
            'decision_note' => 'Rejected',
            'decided_at' => now(),
        ]);

        createRiskFlag(
            'Rejected Approval Request',
            'Medium',
            'Approval',
            $approval->id,
            'Approval request rejected',
            $approval->title
        );

        logActivity(
            'Reject Approval Request',
            'Approval',
            'Rejected approval request #'.$approval->id
        );

        return back()->with('success', 'Approval request rejected successfully');
    }
}