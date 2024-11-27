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
                        return $transaction->status === 'On Going';
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
                        return $transaction->status === 'On Going';
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
                    'status' => 'On Going',
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

    public function PassbookCollectionAllTransactionPage()
    {
        $userBranchId = Auth::user()->branch_id;
        $userGroup = Auth::user()->UserGroup->group_name;


        $Branches = Branch::where('status', 'Active')->get();

        return view('pages.pages_backend.passbook.passbook_transaction_all',compact('userBranchId','userGroup','Branches'));
    }

    public function PassbookCollectionAllTransactionData(Request $request)
    {
        $userBranchId = Auth::user()->branch_id;
        $userGroup = Auth::user()->UserGroup->group_name;

        $branchId = $request->input('branch_id', $userBranchId);

        $PassbookCollectionData = PassbookForCollectionTransaction::with('AtmClientBanks', 'AtmClientBanks.ClientInformation', 'Branch', 'DataTransactionAction', 'PassbookForCollectionTransactionApproval')
            ->when($branchId, function ($query) use ($branchId) {
                return $query->where('branch_id', $branchId); // Apply branch_id filter
            })
            ->latest('created_at')
            ->get()
            ->groupBy('request_number')
            ->map(function ($group) {
                // Get the latest record in the group based on updated_at
                $latestRecord = $group->sortByDesc('updated_at')->first();
                // Add count of transactions
                $latestRecord->setAttribute('transaction_count', $group->count());
                // Determine the overall status for the group
                $statusCount = $group->groupBy('status')->map(function ($statusGroup) {
                    return $statusGroup->count();
                });

                // Handle overall status logic
                if ($statusCount->has('Cancelled') && $statusCount->get('Cancelled') == $group->count()) {
                    $latestRecord->setAttribute('overall_status', 'Cancelled');
                } elseif ($statusCount->has('Completed') && $statusCount->get('Completed') == $group->count()) {
                    $latestRecord->setAttribute('overall_status', 'Completed');
                } elseif ($statusCount->has('On Going') && $statusCount->get('On Going') == $group->count()) {
                    $latestRecord->setAttribute('overall_status', 'On Going');
                } elseif ($statusCount->has('On Going') && $statusCount->get('On Going') > 0 && $statusCount->has('Cancelled') && $statusCount->get('Cancelled') == 1) {
                    $latestRecord->setAttribute('overall_status', 'On Going');
                } elseif ($statusCount->has('Completed') && $statusCount->get('Completed') > 0 && $statusCount->has('Cancelled') && $statusCount->get('Cancelled') == 1) {
                    $latestRecord->setAttribute('overall_status', 'Completed');
                } else {
                    $latestRecord->setAttribute('overall_status', 'On Going');
                }
                return $latestRecord;
            });


        return DataTables::of($PassbookCollectionData->values()) // Reset the keys for DataTables compatibility
            ->setRowId('id') // Use the unique `id` column as the row ID
            ->addColumn('request_number', function ($row) {
                return '<a href="#" class="btn btn-primary viewPassbookTransaction"
                            data-request_number="' . htmlspecialchars($row->request_number) . '">' . htmlspecialchars($row->request_number) .
                        '</a>';
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at
                    ? \Carbon\Carbon::parse($row->created_at)->format('F j, Y - h:i A')
                    : 'N/A';
            })
            ->addColumn('transaction_count', function ($row) {
                return $row->transaction_count;
            })
            ->addColumn('branch_location', function ($row) {
                return $row->Branch->branch_location ?? 'N/A'; // Handle null branch gracefully
            })
            ->addColumn('overall_status', function ($row) {
                return $row->overall_status;
            })
            ->rawColumns(['request_number']) // Allow rendering raw HTML for the request_number column
            ->make(true);

    }

    public function PassbookCollectionTransactionGet(Request $request)
    {
        $request_number = $request->request_number;

        // Fetch the passbook collection data with the required relationships
        $PassbookCollectionData = PassbookForCollectionTransaction::with([
            'AtmClientBanks',
            'AtmClientBanks.ClientInformation',
            'Branch',
            'DataTransactionAction',
            'PassbookForCollectionTransactionApproval.DataUserGroup' // Include DataUserGroup relationship
        ])
            ->where('request_number', $request_number)
            ->get()
            ->map(function ($row) {
                $groupName = null; // Initialize group name
                $atmTransactionActionName = null; // Initialize ATM transaction action name

                // Filter approvals with 'Pending' status
                $pendingApprovals = $row->PassbookForCollectionTransactionApproval->filter(function ($approval) {
                    return $approval->status === 'Pending';
                });

                // Get the last pending approval based on the highest ID
                if ($pendingApprovals->isNotEmpty()) {
                    $lastPendingApproval = $pendingApprovals->sortByDesc('id')->first();
                    $groupName = optional($lastPendingApproval->DataUserGroup)->group_name;

                    // Now, retrieve the transaction action name from the last pending approval
                    if (isset($lastPendingApproval->transaction_actions_id)) {
                        $atmTransactionAction = DataTransactionAction::find($lastPendingApproval->transaction_actions_id);

                        if ($atmTransactionAction) {
                            $atmTransactionActionName = htmlspecialchars($atmTransactionAction->name);
                        }
                    }
                }

                $row->setAttribute('pending_to', $groupName ?: null);
                $row->setAttribute('transaction_action', $atmTransactionActionName ?: null);
                return $row;
            });



            return response()->json([
                'request_number' => $request_number,
                'passbook_collection_data' => $PassbookCollectionData
            ]);
    }

    public function PassbookCollectionTransactionPage()
    {
        $userBranchId = Auth::user()->branch_id;
        $userGroup = Auth::user()->UserGroup->group_name;
        $Branches = Branch::where('status', 'Active')->get();

        return view('pages.pages_backend.passbook.passbook_transaction',compact('userBranchId','userGroup','Branches'));
    }

    public function PassbookCollectionTransactionData(Request $request)
    {
        $userBranchId = Auth::user()->branch_id;
        $userGroup = Auth::user()->UserGroup->group_name;

        $query = PassbookForCollectionTransaction::with(
                'AtmClientBanks', 'AtmClientBanks.ClientInformation',
                'Branch',
                'DataTransactionAction',
                'PassbookForCollectionTransactionApproval')
            ->when($userBranchId, function ($query) use ($userBranchId) {
                return $query->where('branch_id', $userBranchId); // Apply branch_id filter
            })
            ->orderBy('request_number');

            // Apply branch filter based on user branch_id or request input
            if ($userBranchId) {
                $query->where('branch_id', $userBranchId);
            } elseif ($request->filled('branch_id')) {
                $query->where('branch_id', $request->branch_id);
            }

        // Get the filtered data
        $PassbookCollectionData = $query->get();

        return DataTables::of($PassbookCollectionData->values()) // Reset the keys for DataTables compatibility
            ->setRowId('id') // Use the unique `id` column as the row ID
            ->addColumn('branch_location', function ($row) {
                return $row->Branch->branch_location ?? 'N/A'; // Handle null branch gracefully
            })
            ->addColumn('pending_to', function($row) {
                $groupName = ''; // Variable to hold the group name
                $atmTransactionActionName = ''; // Variable to hold the ATM transaction action name

                // Check if there are pending approvals
                $pendingApprovals = $row->PassbookForCollectionTransactionApproval->filter(function ($approval) {
                    return $approval->status === 'Pending';
                });

                // If there are pending approvals, get the group name from the first one
                if ($pendingApprovals->isNotEmpty()) {
                    // Get the group name from the first approval's DataUserGroup relationship
                    $groupName = optional($pendingApprovals->first()->DataUserGroup)->group_name ?? 'N/A';

                    // Get the ATM transaction action using the first approval's transaction_actions_id
                    $atmTransactionAction = DataTransactionAction::find($pendingApprovals->first()->transaction_actions_id);
                    if ($atmTransactionAction) {
                        $atmTransactionActionName = htmlspecialchars($atmTransactionAction->name);
                    }
                }
                return $atmTransactionActionName . ' <div class="text-dark"> ' . $groupName . '</div>';
            })
            ->rawColumns(['branch_location']) // Allow rendering raw HTML for the request_number column
            ->make(true);

    }




}
