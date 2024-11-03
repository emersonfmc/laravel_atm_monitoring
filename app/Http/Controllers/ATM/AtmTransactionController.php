<?php

namespace App\Http\Controllers\ATM;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

use Yajra\DataTables\Facades\DataTables;

use App\Models\AtmTransactionSequence;
use App\Models\AtmClientBanks;
use App\Models\AtmTransactionBalanceLogs;
use App\Models\AtmBanksTransactionApproval;
use App\Models\AtmBanksTransaction;
use App\Models\AtmTransactionAction;
use App\Models\Branch;
use App\Models\SystemLogs;

class AtmTransactionController extends Controller
{
    public function TransactionPage()
    {

        $Branches = Branch::where('status', 'Active')->get();
        $AtmTransactionAction = AtmTransactionAction::where('status', 'Active')->get();

        return view('pages.pages_backend.atm.atm_transactions', compact('Branches','AtmTransactionAction'));
    }

    public function TransactionData()
    {
        $userGroup = Auth::user()->UserGroup->group_name;

        $AtmBanksTransaction = AtmBanksTransaction::with([
                'AtmClientBanks',
                'AtmClientBanks.ClientInformation',
                'AtmTransactionAction',
                'AtmBanksTransactionApproval.DataUserGroup', // Include DataUserGroup for efficient loading
                'Branch'
            ])
            ->latest('updated_at')
            ->get();

        return DataTables::of($AtmBanksTransaction)
            ->setRowId('id')
            ->addColumn('action', function($row) use ($userGroup) {
                $action = ''; // Initialize a variable to hold the buttons

                // Only show the button for users in specific groups
                if (in_array($userGroup, ['Developer', 'Admin', 'Branch Head', 'Everfirst Admin'])) {
                    // Add button for creating a transaction
                    $action .= '<a href="#" class="text-info viewTransaction me-2 mb-2"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="View Transaction"
                                    data-id="' . $row->id . '">
                                    <i class="fas fa-eye fs-5"></i>
                                 </a>';

                    // Check conditions for additional action buttons
                    if ($row->transaction_actions_id == 5 && $row->status === 'ON GOING') {
                        $action .= '<a href="#" class="text-warning editClientTransaction me-2 mb-2"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        title="Edit Client Information"
                                        data-id="' . $row->id . '">
                                    <i class="fas fa-edit fs-5"></i>
                                 </a>';
                    }

                    if ($row->status === 'ON GOING') {
                        $action .= '<a href="#" class="text-danger cancelledTransaction me-2 mb-2"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        title="Cancel Transaction"
                                        data-id="' . $row->id . '">
                                    <i class="fas fa-times-circle fs-5"></i>
                                 </a>';
                    }
                }
                return $action; // Return all the accumulated buttons
            })
            ->addColumn('pending_to', function ($row) {
                $groupName = ''; // Variable to hold the group name
                $atmTransactionActionName = ''; // Variable to hold the ATM transaction action name

                // Get the latest transaction with 'Pending' status
                $latestPendingTransaction = $row->AtmBanksTransactionApproval
                    ->where('status', 'Pending')
                    ->sortByDesc('id') // Sort by descending ID
                    ->first(); // Get the first one, which is the latest 'Pending'

                if ($latestPendingTransaction) {
                    // Get the group name from the latest pending approval if it exists
                    $groupName = optional($latestPendingTransaction->DataUserGroup)->group_name;
                }

                // Get the ATM transaction action name directly
                $atmTransactionActionName = optional($row->AtmTransactionAction)->name;

                // Return the ATM transaction action name and group name
                return $atmTransactionActionName . ' <div class="text-dark"> ' . $groupName . '</div>';
            })
            ->rawColumns(['action','pending_to']) // Render HTML in the pending_to column
            ->make(true);
    }

    public function TransactionGet(Request $request)
    {
        $transaction_id = $request->transaction_id;

        $AtmBanksTransaction = AtmBanksTransaction::with([
                'AtmClientBanks',
                'AtmClientBanks.ClientInformation',
                'AtmTransactionAction',
                'AtmBanksTransactionApproval.DataUserGroup', // Include DataUserGroup for efficient loading
                'Branch'
            ])->findOrFail($transaction_id);

        return response()->json($AtmBanksTransaction);

            // $TblArea = DataArea::findOrFail($id);
            // return response()->json($TblArea);
    }


    public function TransactionCreate(Request $request)
    {
        $reason_for_pull_out  = $request->reason_for_pull_out;

        $atm_id               = $request->atm_id;
        $aprb_no              = $request->aprb_no ?? NULL;
        $remarks              = $request->remarks ?? NULL;
        $release_reason       = $request->release_reason ?? NULL;
        $borrow_reason        = $request->borrow_reason ?? NULL;

        $AtmClientBanks = AtmClientBanks::with('ClientInformation','Branch')->findOrFail($atm_id);
        $AtmClientBanks->update([ 'updated_at' => Carbon::now(), ]);

        $BankAccountNo = $AtmClientBanks->bank_account_no;
        $BankType = $AtmClientBanks->atm_type;
        $PensionNumber = $AtmClientBanks->ClientInformation->pension_number;
        $TransactionNumber = $AtmClientBanks->transaction_number;
        $ClientInformationId = $AtmClientBanks->client_information_id;
        $branch_id = $AtmClientBanks->branch_id;

        // Prevention of Duplication
        $existingTransaction = AtmBanksTransaction::where('transaction_actions_id', $reason_for_pull_out)
            ->where('transaction_number', $TransactionNumber)
            ->where('bank_account_no', $BankAccountNo)
            ->where('status', 'ON GOING')
            ->first(); // Fetch the first matching record

        if ($existingTransaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaction Already Processed'  // Changed message to reflect update action
            ]);
        }
        else
        {
            // Releasing
            if($reason_for_pull_out == '3' || $reason_for_pull_out == '16')
            {
                // Releasing with Balance
                if($reason_for_pull_out == '16') {
                    $reason = 'Releasing of ATM/PB/Simcard With Outstanding Balance';
                } else {
                    $reason = $release_reason;
                }
            }
            else
            {
                if($reason_for_pull_out == '1') {
                    $reason = 'Borrow ATM/PB';
                } else if($reason_for_pull_out == '13')
                {
                    $reason = 'Cancelled Loan';
                } else if($reason_for_pull_out == '11')
                {
                    $reason = 'Replacement of ATM/PB';
                } else if($reason_for_pull_out == '6')
                {
                    $reason = 'For Safekeeping';
                } else if($reason_for_pull_out == '7')
                {
                    $reason = 'For Renewal';
                } else if($reason_for_pull_out == '19')
                {
                    $reason = 'Passbook Borrowed';
                } else if($reason_for_pull_out == '20')
                {
                    $reason = 'Returning of Passbook Borrowed';
                } else if($reason_for_pull_out == '9')
                {
                    $reason = 'Returning of Safekeep ATM';
                } else if($reason_for_pull_out == '4')
                {
                    $reason = 'Returning of Borrowed ATM';
                }
                else {
                    $reason = 'Unknown';
                }
                $AtmBanksTransaction = AtmBanksTransaction::create([
                    'client_banks_id' => $atm_id,
                    'transaction_actions_id' => $reason_for_pull_out,
                    'transaction_number' => $TransactionNumber,
                    'request_by_employee_id' => Auth::user()->employee_id,
                    'bank_account_no' => $BankAccountNo,
                    'atm_type' => $BankType,
                    'branch_id' => $branch_id,
                    'aprb_no' => $aprb_no,
                    'reason' => $reason,
                    'reason_remarks' => $remarks,
                    'status' => 'ON GOING',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                $AtmTransactionSequences = AtmTransactionSequence::where('atm_transaction_actions_id', $reason_for_pull_out)
                    ->orderBy('sequence_no')
                    ->get();

                foreach ($AtmTransactionSequences as $transactionSequence)
                {
                    // Set the status based on the sequence number
                    $status = ($transactionSequence->sequence_no == '1') ? 'Pending' : 'Stand By';

                    AtmBanksTransactionApproval::create([
                        'banks_transactions_id' => $AtmBanksTransaction->id,
                        'transaction_actions_id' => 5,
                        'employee_id' => NULL,
                        'date_approved' => NULL,
                        'user_groups_id' => $transactionSequence->user_group_id,
                        'sequence_no' => $transactionSequence->sequence_no,
                        'status' => $status,
                        'type' => $transactionSequence->type,
                        'created_at' => Carbon::now(),
                    ]);
                }

                $balance = floatval(preg_replace('/[^\d]/', '', $request->atm_balance ?? 0));

                AtmTransactionBalanceLogs::create([
                    'banks_transactions_id' => $AtmBanksTransaction->id,
                    'check_by_user_id' => Auth::user()->id,
                    'balance' => $balance,
                    'remarks' => $remarks ?? NULL,
                    'created_at' => Carbon::now(),
                ]);

                // Create System Logs used for Auditing of Logs
                SystemLogs::create([
                    'system' => 'ATM Monitoring',
                    'action' => 'Create',
                    'title' => 'Create Transaction',
                    'description' => 'Creation of New Transaction'.' : ' . $TransactionNumber .' | '.$reason,
                    'employee_id' => Auth::user()->employee_id,
                    'ip_address' => $request->ip(),
                    'created_at' => Carbon::now(),
                    'company_id' => Auth::user()->company_id,
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Transaction Created successfully!'  // Changed message to reflect update action
        ]);
    }
}
