<?php

namespace App\Http\Controllers\ATM;

use App\Models\Branch;
use Illuminate\Http\Request;
use App\Models\AtmClientBanks;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\AtmTransactionAction;
use Illuminate\Support\Facades\Auth;
use App\Models\DataTransactionAction;
use App\Models\DataTransactionSequence;
use Yajra\DataTables\Facades\DataTables;
use App\Models\PassbookForCollectionTransaction;
use App\Models\PassbookForCollectionTransactionApproval;

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
            ->whereHas('ClientInformation', function ($query) {
                $query->where('passbook_for_collection', 'yes');
            })
            ->where('status','1')
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
                            $atmTransactionActionName = optional($pendingApprovals->first()->DataTransactionAction)->name;
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

            $atmIds = $selectedAtmClientBanks->pluck('id');
            $branchIds = $selectedAtmClientBanks->pluck('branch_id')->unique();
            $transactionNumbers = $selectedAtmClientBanks->pluck('transaction_number');

            // Validate if all items belong to the same branch
            if ($branchIds->count() > 1) {
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

            // Loop through each selected ATM client bank and create transactions
            foreach ($selectedAtmClientBanks as $atmClientBank) {
                $PassbookForCollectionTransaction = PassbookForCollectionTransaction::create([
                    'client_banks_id' => $atmClientBank->id,
                    'branch_id' => $branchId,
                    'request_number' => $RequestNumber,
                    'reference_no' => $atmClientBank->transaction_number,
                    'request_by_employee_id' => Auth::user()->employee_id,
                    'scan_by_employee_id' => NULL,
                    'scan_status' => NULL,
                    'remarks' => NULL,
                    'cancelled_by_employee_id' => NULL,
                    'cancelled_date' => NULL,
                    'status' => 'ON GOING',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                $DataTransactionSequence = DataTransactionSequence::where('transaction_actions_id', 19)
                    ->orderBy('sequence_no')
                    ->get();

                foreach ($DataTransactionSequence as $transactionSequence)
                {
                    $status = ($transactionSequence->sequence_no == '1') ? 'Pending' : 'Stand By';

                    PassbookForCollectionTransactionApproval::create([
                        'passbook_transactions_id' => $PassbookForCollectionTransaction->id,
                        'transaction_actions_id' => 19,
                        'employee_id' => NULL,
                        'date_approved' => NULL,
                        'user_groups_id' => $transactionSequence->user_group_id,
                        'sequence_no' => $transactionSequence->sequence_no,
                        'status' => $status,
                        'type' => $transactionSequence->type,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }
        }
        else {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaction Created Failed!'  // Changed message to reflect update action
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Transaction Created Successfully!'  // Changed message to reflect update action
        ]);



    }

}
