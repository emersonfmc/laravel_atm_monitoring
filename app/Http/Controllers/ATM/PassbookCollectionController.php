<?php

namespace App\Http\Controllers\ATM;

use App\Models\Branch;
use Illuminate\Http\Request;
use App\Models\AtmClientBanks;
use App\Http\Controllers\Controller;
use App\Models\AtmTransactionAction;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\PassbookForCollectionTransaction;

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

    public function PassbookForCollectionCreate(Request $request)
    {
        $item_ids = $request->items;

        if (is_array($item_ids) && count($item_ids) > 0) {
            // Retrieve all selected ATM client banks based on the provided IDs
            $selectedAtmClientBanks = AtmClientBanks::whereIn('id', $item_ids)->get();

            $branchIds = $selectedAtmClientBanks->pluck('branch_id')->unique();

            if ($branchIds->count() > 1) {
                // If there are multiple branch IDs, return an error response
                return response()->json([
                    'status' => 'error',
                    'message' => 'All selected items must belong to the same branch.',
                ]);
            }

            // Get the branch abbreviation for the single branch_id
            $branchId = $branchIds->first();
            $branch = Branch::where('id', $branchId)->first();
            $branchAbbreviation = $branch->branch_abbreviation;


            // Generate Request Number
                $request_number_initial = $branchAbbreviation . '-PB' . date('mdy');

                // Fetch the last `request_number` with the matching prefix
                $fetch_validate = PassbookForCollectionTransaction::select('request_number')
                    ->where('request_number', 'like', $request_number_initial . '%')
                    ->orderByDesc('id') // Ensure we get the latest one
                    ->first();

                if ($fetch_validate) {
                    // Extract the number part from the existing `request_number`
                    $last_number = substr($fetch_validate->request_number, -6); // Get the last 6 digits
                    $new_number = (int)$last_number + 1; // Increment the number
                } else {
                    // If no existing request number, start with 1
                    $new_number = 1;
                }

                $formatted_number = str_pad($new_number, 6, '0', STR_PAD_LEFT);
                $RequestNumber = $request_number_initial . '-' . $formatted_number;
            // Generate Request Number

            dd($RequestNumber);

            // Output the result

            // Handle case where the branch is not found
            return response()->json([
                'status' => 'error',
                'message' => 'Branch not found for the selected items.',
            ]);
        }

    }

}
