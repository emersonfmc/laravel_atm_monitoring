<?php

namespace App\Http\Controllers\ATM;

use App\Models\Branch;
use App\Models\DataArea;
use App\Models\SystemLogs;
use Illuminate\Http\Request;

use App\Models\DataBankLists;

use App\Models\AtmClientBanks;
use Illuminate\Support\Carbon;
use App\Models\DataReleaseOption;
use App\Models\DataCollectionDate;
use App\Models\AtmBanksTransaction;
use App\Http\Controllers\Controller;
use App\Models\AtmTransactionAction;
use Illuminate\Support\Facades\Auth;
use App\Models\DataPensionTypesLists;
use App\Models\DataTransactionAction;
use App\Models\AtmTransactionSequence;
use App\Models\DataTransactionSequence;
use Yajra\DataTables\Facades\DataTables;
use App\Models\AtmTransactionBalanceLogs;
use App\Models\AtmBanksTransactionApproval;

class AtmTransactionController extends Controller
{
    public function TransactionPage()
    {
        $branch_id = Auth::user()->branch_id;

        $Branches = Branch::where('status', 'Active')->get();
        $DataTransactionAction = DataTransactionAction::where('status', 'Active')->get();

        $DataBankLists = DataBankLists::where('status','Active')->get();
        $DataReleaseOption = DataReleaseOption::where('status','Active')->get();
        $DataPensionTypesLists = DataPensionTypesLists::where('status','Active')->get();
        $DataCollectionDate = DataCollectionDate::where('status','Active')->get();

        return view('pages.pages_backend.atm.atm_transactions',
                    compact('Branches','DataTransactionAction','branch_id',
                            'DataBankLists','DataPensionTypesLists','DataCollectionDate'));
    }

    public function TransactionData(Request $request)
    {
        $userGroup = Auth::user()->UserGroup->group_name;
        $branch_id = Auth::user()->branch_id;

        // Start building the query with conditional branch, transaction, and status filters
        $query = AtmBanksTransaction::with([
            'AtmClientBanks',
            'AtmClientBanks.ClientInformation',
            'DataTransactionAction',
            'AtmBanksTransactionApproval.DataUserGroup',
            'Branch'
        ])->latest('updated_at');

        // Apply branch filter based on user branch_id or request input
        if ($branch_id) {
            $query->where('branch_id', $branch_id);
        } elseif ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('transaction_actions_id')) {
            $query->where('transaction_actions_id', $request->transaction_actions_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return DataTables::of($query)
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

                if (in_array($userGroup, ['Developer', 'Admin', 'Everfirst Admin'])) {
                    // Add button for creating a transaction
                    $action .= '<a href="#" class="text-success editAdminTransaction me-2 mb-2"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="Edit Transaction"
                                    data-id="' . $row->id . '">
                                    <i class="fas fa-edit fs-5"></i>
                                 </a>';
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
                $atmTransactionActionName = optional($row->DataTransactionAction)->name;

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
                'DataTransactionAction',
                'AtmBanksTransactionApproval.DataUserGroup', // Include DataUserGroup for efficient loading
                'AtmBanksTransactionApproval.Employee',
                'AtmBanksTransactionApproval.AtmTransactionApprovalsBalanceLogs',
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
        $BankName = $AtmClientBanks->bank_name;
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
            if($reason_for_pull_out == 3 || $reason_for_pull_out == 16)
            {
                // Releasing with Balance
                if($reason_for_pull_out == 16) {
                    $reason = 'Release With Balance';
                } else {
                    $reason = 'Release';
                }

                $selected_atm_ids = $request->atm_checkboxes; // Array of checked ATM IDs

                if (is_array($selected_atm_ids) && count($selected_atm_ids) > 0) {
                    foreach ($selected_atm_ids as $selected_atm_id) {
                        // Fetch the ATM client bank with status 1
                        $SelectedAtmClientBanks = AtmClientBanks::findOrFail($selected_atm_id);
                        $SelectedAtmClientBanks->update([ 'updated_at' => Carbon::now(), ]);

                        // Retrieve details from the selected ATM client bank
                        $FetchTransactionNumber = $SelectedAtmClientBanks->transaction_number;
                        $FetchBankAccountNumber = $SelectedAtmClientBanks->bank_account_no;
                        $FetchBankAtmType = $SelectedAtmClientBanks->atm_type;
                        $FetchBranchId = $SelectedAtmClientBanks->branch_id;

                        // Create a new AtmBanksTransaction entry
                        $AtmBanksTransaction = AtmBanksTransaction::create([
                            'client_banks_id' => $selected_atm_id,
                            'transaction_actions_id' => $reason_for_pull_out,
                            'request_by_employee_id' => Auth::user()->employee_id,
                            'transaction_number' => $FetchTransactionNumber,
                            'atm_type' => $FetchBankAtmType,
                            'bank_account_no' => $FetchBankAccountNumber ?? NULL,
                            'branch_id' => $FetchBranchId,
                            'aprb_no' => $aprb_no ?? NULL,
                            'status' => 'ON GOING',
                            'reason' => $reason ?? NULL,
                            'reason_remarks' => $release_reason ?? NULL,
                            'yellow_copy' => NULL,
                            'created_at' => Carbon::now(),
                        ]);

                        // Retrieve transaction sequences for approvals
                        $AtmTransactionSequences = DataTransactionSequence::where('transaction_actions_id', $reason_for_pull_out)
                            ->orderBy('sequence_no')
                            ->get();

                        foreach ($AtmTransactionSequences as $transactionSequence) {
                            // Set the status based on the sequence number
                            $status = ($transactionSequence->sequence_no == '1') ? 'Pending' : 'Stand By';

                            // Create approval entries for the transaction
                            AtmBanksTransactionApproval::create([
                                'banks_transactions_id' => $AtmBanksTransaction->id,
                                'transaction_actions_id' => $reason_for_pull_out,
                                'employee_id' => NULL,
                                'date_approved' => NULL,
                                'user_groups_id' => $transactionSequence->user_group_id,
                                'sequence_no' => $transactionSequence->sequence_no,
                                'status' => $status,
                                'type' => $transactionSequence->type,
                                'created_at' => Carbon::now(),
                            ]);
                        }

                        // Create a balance log entry for the transaction
                        AtmTransactionBalanceLogs::create([
                            'banks_transactions_id' => $AtmBanksTransaction->id,
                            'check_by_user_id' => Auth::user()->employee_id,
                            'balance' => 0,
                            'remarks' => $remarks ?? NULL,
                            'created_at' => Carbon::now(),
                        ]);
                    }
                }
            }
            else if($reason_for_pull_out == 4)
            {
                $replacement_status_value = $request->replacement_status_value ?? NULL;

                if($replacement_status_value == 'for_replacement')
                {
                    $replacementTypes = $request->replacement_type_action ?? NULL;

                    if($replacementTypes == '4' || $replacementTypes == '12')
                    {
                        $atm_number_new_raw         = $request->new_atm_number ?? NULL;
                        $new_bank_list              = $request->new_bank_list ?? NULL;
                        $new_pin_code               = $request->new_pin_code ?? NULL;
                        $new_balance_replacement    = $request->new_balance ?? NULL;
                        $replace_collection_date    = $request->new_collection_date ?? NULL;
                        $new_atm_status             = $request->new_atm_status ?? NULL;
                        $new_atm_type               = $request->new_atm_type ?? NULL;
                    }
                    else
                    {
                        $atm_number_new_raw         = NULL;
                        $new_bank_list              = NULL;
                        $new_pin_code               = NULL;
                        $new_balance_replacement    = $request->new_balance ?? NULL;
                        $replace_collection_date    = NULL;
                        $new_atm_type               = $request->new_atm_type ?? NULL;
                    }
                    $atm_number_new = str_replace("-", "", $atm_number_new_raw);

                    // Get the branch abbreviation
                    $BranchGet = Branch::where('id', $branch_id)->first();
                    $branch_abbreviation = $BranchGet->branch_abbreviation;

                    // Fetch the last transaction number based on the branch_id and branch_code
                    $lastTransaction = AtmClientBanks::where('branch_id', $branch_id)
                        ->orderBy('transaction_number', 'desc') // Order by transaction_number in descending order
                        ->first();

                    if ($lastTransaction) {
                        $lastPart = substr($lastTransaction->transaction_number, strrpos($lastTransaction->transaction_number, '-') + 1);
                        $lastadded = (int)$lastPart;
                    } else {
                        $lastadded = 0;
                    }

                    $newinsert = $lastadded + 1;
                    $reference_number_formatted = sprintf('%05d', $newinsert); // Ensure the new number is 5 digits with leading zeros
                    $reference_number = $branch_abbreviation . '-' . date('mdy') . '-' . $reference_number_formatted;

                    $replacement_same_atm = $request->replacement_same_atm ?? NULL;

                    $selectBankName = null; // or use an appropriate default value
                    $selectBanknumber = null; // or use an appropriate default value
                    $replacement_count_update = ""; // or use an appropriate default value

                    if ($replacement_same_atm === 'replacement_same_atm') {
                        $selectBanknumber = $BankAccountNo;
                        $selectBankName = $BankName;

                        // Increment replacement_count for the existing bank account
                        AtmClientBanks::where('bank_account_no', $BankAccountNo)->increment('replacement_count');
                    } else {
                        // Check if the new ATM number already exists
                        $existingBankAccountNo = AtmClientBanks::where('bank_account_no', $atm_number_new)->first();
                        if ($existingBankAccountNo) {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Bank Account No Already Exists',
                            ]);
                        }

                        $selectBankName = $new_bank_list;
                        $selectBanknumber = $atm_number_new;
                    }

                        $preventReplacementDuplication = AtmBanksTransaction::where('transaction_actions_id', 17)
                            ->where('transaction_number', $TransactionNumber)
                            ->where('bank_account_no', $selectBanknumber)
                            ->where('status', 'ON GOING')
                            ->first(); // Fetch the first matching record

                        if ($preventReplacementDuplication)
                        {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Transaction Already Processed'  // Changed message to reflect update action
                            ]);
                        }
                        else
                        {
                            $AtmClientBanks = AtmClientBanks::create([
                                'client_information_id' => $ClientInformationId,
                                'transaction_number' => $reference_number,
                                'branch_id' => $branch_id ?? NULL,
                                'atm_type' => $new_atm_type ?? NULL,
                                'atm_status' => $new_atm_status ?? NULL,
                                'location' => 'Branch',
                                'bank_account_no' => $selectBanknumber ?? NULL,
                                'bank_name' => $selectBankName ?? NULL,
                                'pin_no' => $new_pin_code ?? NULL,
                                'expiration_date' => NULL,
                                'collection_date' => $replace_collection_date ?? NULL,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);

                                // Append the replacement count update if applicable
                            if ($replacement_count_update && $replacement_count_update !== ',') {
                                $AtmClientBanks .= ", $replacement_count_update";
                            }

                            $AtmBanksTransaction = AtmBanksTransaction::create([
                                'client_banks_id' => $AtmClientBanks->id,
                                'transaction_actions_id' => $replacementTypes,
                                'request_by_employee_id' => Auth::user()->employee_id,
                                'transaction_number' => $reference_number,
                                'atm_type' => $new_atm_type ?? NULL,
                                'bank_account_no' => $selectBanknumber ?? NULL,
                                'branch_id' => $branch_id,
                                'status' => 'ON GOING',
                                'reason' => 'New ATM ( Replacement )',
                                'reason_remarks' =>'New ATM ( Replacement ) - From Borrow Transaction',
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);

                            $DataTransactionSequence = DataTransactionSequence::where('transaction_actions_id', $replacementTypes)
                                ->orderBy('sequence_no')
                                ->get();

                            foreach ($DataTransactionSequence as $transactionSequence)
                            {
                                // Set the status based on the sequence number
                                $status = ($transactionSequence->sequence_no == '1') ? 'Pending' : 'Stand By';

                                AtmBanksTransactionApproval::create([
                                    'banks_transactions_id' => $AtmBanksTransaction->id,
                                    'transaction_actions_id' => $replacementTypes,
                                    'employee_id' => NULL,
                                    'date_approved' => NULL,
                                    'user_groups_id' => $transactionSequence->user_group_id,
                                    'sequence_no' => $transactionSequence->sequence_no,
                                    'status' => $status,
                                    'type' => $transactionSequence->type,
                                    'created_at' => Carbon::now(),
                                ]);
                            }

                            $balance = floatval(preg_replace('/[^\d]/', '', $request->new_balance));

                            AtmTransactionBalanceLogs::create([
                                'banks_transactions_id' => $AtmBanksTransaction->id,
                                'check_by_user_id' => Auth::user()->id,
                                'balance' => $balance,
                                'remarks' => $request->new_remarks ?? NULL,
                                'created_at' => Carbon::now(),
                            ]);
                        }
                }
                // Returning Of Borrowed ATM / Passbook
                else
                {
                    $reason = "Returning of Borrowed $BankType";

                    $AtmBanksTransaction = AtmBanksTransaction::create([
                        'client_banks_id' => $atm_id,
                        'transaction_actions_id' => $reason_for_pull_out,
                        'transaction_number' => $TransactionNumber,
                        'request_by_employee_id' => Auth::user()->employee_id,
                        'bank_account_no' => $BankAccountNo,
                        'atm_type' => $BankType,
                        'branch_id' => $branch_id,
                        'aprb_no' => $aprb_no ?? NULL,
                        'reason' => $reason,
                        'reason_remarks' => '',
                        'status' => 'ON GOING',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                    $DataTransactionSequence = DataTransactionSequence::where('transaction_actions_id', $reason_for_pull_out)
                        ->orderBy('sequence_no')
                        ->get();

                    foreach ($DataTransactionSequence as $transactionSequence)
                    {
                        // Set the status based on the sequence number
                        $status = ($transactionSequence->sequence_no == '1') ? 'Pending' : 'Stand By';

                        AtmBanksTransactionApproval::create([
                            'banks_transactions_id' => $AtmBanksTransaction->id,
                            'transaction_actions_id' => $reason_for_pull_out,
                            'employee_id' => NULL,
                            'date_approved' => NULL,
                            'user_groups_id' => $transactionSequence->user_group_id,
                            'sequence_no' => $transactionSequence->sequence_no,
                            'status' => $status,
                            'type' => $transactionSequence->type,
                            'created_at' => Carbon::now(),
                        ]);
                    }

                    $balance = floatval(preg_replace('/[^\d]/', '', $request->balance ?? 0));

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
                        'title' => 'Create Transaction'. $reason,
                        'description' => 'Creation of New Transaction'.' : ' . $TransactionNumber .' | '.$reason,
                        'employee_id' => Auth::user()->employee_id,
                        'ip_address' => $request->ip(),
                        'created_at' => Carbon::now(),
                        'company_id' => Auth::user()->company_id,
                    ]);
                }
            }
            else
            {
                if($reason_for_pull_out == '1') {
                    $reason = 'Borrow ATM/PB';
                    $remarks = $borrow_reason;
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

                $DataTransactionSequence = DataTransactionSequence::where('transaction_actions_id', $reason_for_pull_out)
                    ->orderBy('sequence_no')
                    ->get();

                foreach ($DataTransactionSequence as $transactionSequence)
                {
                    // Set the status based on the sequence number
                    $status = ($transactionSequence->sequence_no == '1') ? 'Pending' : 'Stand By';

                    AtmBanksTransactionApproval::create([
                        'banks_transactions_id' => $AtmBanksTransaction->id,
                        'transaction_actions_id' => $reason_for_pull_out,
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
