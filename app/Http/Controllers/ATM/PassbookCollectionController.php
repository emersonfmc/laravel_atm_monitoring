<?php

namespace App\Http\Controllers\ATM;

use App\Models\Branch;
use Illuminate\Http\Request;
use App\Models\AtmClientBanks;
use App\Http\Controllers\Controller;
use App\Models\AtmTransactionAction;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PassbookCollectionController extends Controller
{
    public function PassbookCollectionSetUpPage()
    {
        // $AtmClientBanks = AtmClientBanks::with('ClientInformation', 'Branch', 'AtmBanksTransaction')
        // ->where('passbook_for_collection', 'yes')
        // ->latest('updated_at')
        // ->get();

        // dd($AtmClientBanks);

        $userGroup = Auth::user()->UserGroup->group_name;

        $branch_id = Auth::user()->branch_id;
        $Branches = Branch::where('status','Active')->get();

        return view('pages.pages_backend.passbook.passbook_setup',compact('branch_id','Branches','userGroup'));
    }

    public function PassbookCollectionData(Request $request)
    {
        $userBranchId = Auth::user()->branch_id;
        $userGroup = Auth::user()->UserGroup->group_name;

        $query = AtmClientBanks::with('ClientInformation', 'Branch', 'PassbookForCollectionTransaction')
            ->where('passbook_for_collection', 'yes')
            ->latest('updated_at');

        if ($userBranchId) {
            $query->where('branch_id', $userBranchId);
        } elseif ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        $PassbookCollectionData = $query->get();

        return DataTables::of($PassbookCollectionData)
            ->setRowId('id')
            ->addColumn('action', function($row) use ($userGroup) {
                $hasOngoingTransaction = false;
                $latestTransactionId = null;
                $action = '';

                if ($row->PassbookForCollectionTransaction) {
                    $ongoingTransactions = $row->PassbookForCollectionTransaction->filter(function ($transaction) {
                        return $transaction->status === 'ON GOING';
                    })->sortByDesc('id');

                    if ($ongoingTransactions->isNotEmpty()) {
                        $hasOngoingTransaction = true;
                        $latestTransactionId = $ongoingTransactions->first()->id;
                    }
                }

                if (in_array($userGroup, ['Developer', 'Admin', 'Branch Head', 'Everfirst Admin'])) {
                    if ($hasOngoingTransaction) {
                        return '<i class="fas fa-spinner fa-spin fs-3 text-success"></i>';
                    } else {
                        $action .= '<input type="checkbox" class="check check-item" data-id="' . $row->id . '"/>';
                    }
                }
                return $action;
            })
            ->addColumn('pending_to', function($row) {
                $groupName = ''; // Variable to hold the group name
                $atmTransactionActionName = ''; // Variable to hold the ATM transaction action name

                if ($row->PassbookForCollectionTransaction) {
                    // Filter for ongoing transactions and sort by id in descending order
                    $ongoingTransactions = $row->PassbookForCollectionTransaction->filter(function ($transaction) {
                        return $transaction->status === 'ON GOING';
                    })->sortByDesc('id'); // Sort by id in descending order

                    // Get the first ongoing transaction details if it exists
                    if ($ongoingTransactions->isNotEmpty()) {
                        $firstOngoingTransaction = $ongoingTransactions->first();

                        // Get the approvals related to this transaction with status 'Pending'
                        $pendingApprovals = $firstOngoingTransaction->PassbookForCollectionTransactionApproval->filter(function ($approval) {
                            return $approval->status === 'Pending';
                        });

                        // If there are pending approvals, get the group name from the first one
                        if ($pendingApprovals->isNotEmpty()) {
                            // Use the relationship to get the group name
                            $groupName = optional($pendingApprovals->first()->DataUserGroup)->group_name;
                        }

                        // Get the ATM transaction action name if it exists
                        if (isset($firstOngoingTransaction->transaction_actions_id)) {
                            $atmTransactionAction = AtmTransactionAction::find($firstOngoingTransaction->transaction_actions_id);
                            if ($atmTransactionAction) {
                                $atmTransactionActionName = htmlspecialchars($atmTransactionAction->name);
                            }
                        }
                    }
                }

                // Prepare the output
                return $atmTransactionActionName . ' <div class="text-dark"> ' . $groupName .'</div>'; // Combine the group name and action name
            })
            ->rawColumns(['action','pending_to'])
            ->make(true);
    }

}
