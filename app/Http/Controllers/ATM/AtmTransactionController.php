<?php

namespace App\Http\Controllers\ATM;

use App\Models\Branch;
use App\Models\SystemLogs;
use Illuminate\Http\Request;
use App\Models\DataBankLists;

use App\Models\AtmClientBanks;
use Illuminate\Support\Carbon;

use App\Models\ClientInformation;
use App\Models\DataReleaseOption;
use App\Models\DataCollectionDate;
use App\Models\AtmBanksTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\DataPensionTypesLists;
use App\Models\DataTransactionAction;
use App\Models\AtmReleasedClientImage;
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
            ->addColumn('full_name', function ($row) {
                // Check if the relationships and fields exist
                $clientInfo = $row->AtmClientBanks->ClientInformation ?? null;

                if ($clientInfo) {
                    $lastName = $clientInfo->last_name ?? '';
                    $firstName = $clientInfo->first_name ?? '';
                    $middleName = $clientInfo->middle_name ? ' ' . $clientInfo->middle_name : ''; // Add space if middle_name exists
                    $suffix = $clientInfo->suffix ? ', ' . $clientInfo->suffix : ''; // Add comma if suffix exists

                    // Combine the parts into the full name
                    $fullName = "{$lastName}, {$firstName}{$middleName}{$suffix}";
                } else {
                    // Fallback if client information is missing
                    $fullName = 'N/A';
                }

                return $fullName;
            })
            ->addColumn('pension_details', function ($row) {
                // Check if the relationships and fields exist
                $pensionDetails = $row->AtmClientBanks->ClientInformation ?? null;

                if ($pensionDetails) {
                    $PensionNumber = $pensionDetails->pension_number ?? '';
                    $PensionType = $pensionDetails->pension_account_type ?? '';
                    $AccountType = $pensionDetails->pension_type ?? '';

                    // Combine the parts into the full name
                    $pension_details = "<span class='fw-bold text-primary h6 pension_number_mask_display'>{$PensionNumber}</span><br>
                                       <span class='fw-bold'>{$PensionType}</span><br>
                                       <span class='fw-bold text-success'>{$AccountType}</span>";
                } else {
                    // Fallback if client information is missing
                    $pension_details = 'N/A';
                }

                return $pension_details;
            })
            ->rawColumns(['action','pending_to','full_name','pension_details']) // Render HTML in the pending_to column
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
        $PinCode = $AtmClientBanks->pin_no;
        $ExpirationDate = $AtmClientBanks->expiration_date;
        $CollectionDate = $AtmClientBanks->collection_date;
        $CreatedDate = $AtmClientBanks->created_at;
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
                            'check_by_employee_id' => Auth::user()->employee_id,
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
                        $atm_number_new_raw  = $request->new_atm_number ?? NULL;
                        $new_bank_list = $request->new_bank_name ?? NULL;
                        $new_pin_code = $request->new_pin_code ?? NULL;
                        $new_collection_date = $request->new_collection_date ?? NULL;
                        $new_atm_status = $request->new_atm_status ?? NULL;
                    }
                    // else
                    // {
                    //     $atm_number_new_raw = NULL;
                    //     $new_bank_list = NULL;
                    //     $new_pin_code = NULL;
                    //     $replace_collection_date = NULL;
                    //     $new_atm_status = $request->new_atm_status ?? NULL;
                    // }
                    $atm_number_new = str_replace("-", "", $atm_number_new_raw);

                    $new_balance = $request->new_balance ?? NULL;
                    $new_atm_type = $request->new_atm_type ?? NULL;
                    $new_remarks = $request->new_remarks ?? NULL;
                    $expirationDate = $request->new_expiration_date;
                    if ($expirationDate) {
                        $expirationDate .= '-01';
                    } else {
                        $expirationDate = null;
                    }


                    // Creation of Transaction Number
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
                    // Creation of Transaction Number

                    $replacement_same_atm = $request->replacement_same_atm ?? NULL;

                    $selectBankName = ''; // or use an appropriate default value
                    $selectBanknumber = ''; // or use an appropriate default value
                    $selectTransactionNumber = ''; // or use an appropriate default value

                    // Validate if Replace Same Bank Account No Or Different Bank Account
                    if ($replacement_same_atm === 'replacement_same_atm') {
                        $selectBanknumber = $BankAccountNo;
                        $selectBankName = $BankName;
                        $selectTransactionNumber = $TransactionNumber;

                        $existingAtmClientBank = AtmClientBanks::where('bank_account_no', $BankAccountNo)->first();
                        if ($existingAtmClientBank) {
                            $existingAtmClientBank->increment('replacement_count');
                        }
                    }
                    else {
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
                        $selectTransactionNumber = $reference_number;
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
                            // Creation of Transaction for The Replaced ATM
                                $AtmClientBanks = AtmClientBanks::create([
                                    'client_information_id' => $ClientInformationId,
                                    'transaction_number' => $selectTransactionNumber,
                                    'branch_id' => $branch_id ?? NULL,
                                    'atm_type' => $new_atm_type ?? NULL,
                                    'atm_status' => $new_atm_status ?? NULL,
                                    'location' => 'Branch',
                                    'bank_account_no' => $selectBanknumber ?? NULL,
                                    'bank_name' => $selectBankName ?? NULL,
                                    'pin_no' => $new_pin_code ?? NULL,
                                    'expiration_date' => $expirationDate,
                                    'collection_date' => $new_collection_date ?? NULL,
                                    'replacement_count' => $replacement_same_atm === 'replacement_same_atm' ? ($existingAtmClientBank->replacement_count ?? 0) : 0,
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ]);

                                $AtmBanksTransaction = AtmBanksTransaction::create([
                                    'client_banks_id' => $AtmClientBanks->id,
                                    'transaction_actions_id' => 17,
                                    'request_by_employee_id' => Auth::user()->employee_id,
                                    'transaction_number' => $selectTransactionNumber,
                                    'atm_type' => $new_atm_type ?? NULL,
                                    'bank_account_no' => $selectBanknumber ?? NULL,
                                    'branch_id' => $branch_id,
                                    'status' => 'ON GOING',
                                    'reason' => 'New ATM ( Replacement )',
                                    'reason_remarks' =>'New ATM ( Replacement ) - From Borrow Transaction',
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ]);

                                $DataTransactionSequence = DataTransactionSequence::where('transaction_actions_id', 17)->orderBy('sequence_no')->get();

                                    foreach ($DataTransactionSequence as $transactionSequence)
                                    {
                                        // Set the status based on the sequence number
                                        $status = ($transactionSequence->sequence_no == '1') ? 'Pending' : 'Stand By';

                                        AtmBanksTransactionApproval::create([
                                            'banks_transactions_id' => $AtmBanksTransaction->id,
                                            'transaction_actions_id' => 17,
                                            'employee_id' => NULL,
                                            'date_approved' => NULL,
                                            'user_groups_id' => $transactionSequence->user_group_id,
                                            'sequence_no' => $transactionSequence->sequence_no,
                                            'status' => $status,
                                            'type' => $transactionSequence->type,
                                            'created_at' => Carbon::now(),
                                        ]);
                                    }

                                    $balance = floatval(preg_replace('/[^\d]/', '', $new_balance));

                                    AtmTransactionBalanceLogs::create([
                                        'banks_transactions_id' => $AtmBanksTransaction->id,
                                        'check_by_user_id' => Auth::user()->id,
                                        'balance' => $balance,
                                        'remarks' => $new_remarks,
                                        'created_at' => Carbon::now(),
                                        'updated_at' => Carbon::now(),
                                    ]);
                            // Creation of Transaction for The Replaced ATM

                            // Old has Returned by Bank
                                if($replacementTypes == '12')
                                {
                                    $AtmReturnOldClientBanks = AtmClientBanks::findOrFail($atm_id);
                                    $AtmReturnOldClientBanks->update([
                                        'atm_status' => 'old',
                                        'status' => '6',
                                        'transaction_number' => $TransactionNumber.'-RO',
                                        'updated_at' => Carbon::now(),
                                    ]);

                                    $AtmBanksTransaction = AtmBanksTransaction::create([
                                        'client_banks_id' => $AtmReturnOldClientBanks->id,
                                        'transaction_actions_id' => 12,
                                        'request_by_employee_id' => Auth::user()->employee_id,
                                        'transaction_number' => $TransactionNumber.'-RO',
                                        'atm_type' => $BankType ?? NULL,
                                        'bank_account_no' => $BankAccountNo ?? NULL,
                                        'branch_id' => $branch_id,
                                        'status' => 'ON GOING',
                                        'reason' => 'Returning of Old ATM to Head Office',
                                        'reason_remarks' => 'Returning of Old ATM to Head Office - From Borrow Transaction',
                                        'created_at' => Carbon::now(),
                                        'updated_at' => Carbon::now(),
                                    ]);

                                    $DataTransactionSequence = DataTransactionSequence::where('transaction_actions_id', 12)->orderBy('sequence_no')->get();

                                    foreach ($DataTransactionSequence as $transactionSequence)
                                    {
                                        // Set the status based on the sequence number
                                        $status = ($transactionSequence->sequence_no == '1') ? 'Pending' : 'Stand By';

                                        AtmBanksTransactionApproval::create([
                                            'banks_transactions_id' => $AtmBanksTransaction->id,
                                            'transaction_actions_id' => 12,
                                            'employee_id' => NULL,
                                            'date_approved' => NULL,
                                            'user_groups_id' => $transactionSequence->user_group_id,
                                            'sequence_no' => $transactionSequence->sequence_no,
                                            'status' => $status,
                                            'type' => $transactionSequence->type,
                                            'created_at' => Carbon::now(),
                                        ]);
                                    }
                                }
                            // Old has Returned by Bank

                            // Old has not Return by Bank
                            if($replacementTypes == '4') {
                                $AtmOldClientBanks = AtmClientBanks::findOrFail($atm_id);
                                $AtmOldClientBanks->update([
                                    'location' => 'Released',
                                    'status' => '5'
                                ]);
                            }
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
                } else if($reason_for_pull_out == '9')
                {
                    $reason = 'Returning of Safekeep ATM';
                } else {
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
                    'check_by_employee_id' => Auth::user()->employee_id,
                    'balance' => $balance,
                    'remarks' => $remarks ?? NULL,
                    'created_at' => Carbon::now(),
                ]);

                // Create System Logs used for Auditing of Logs
                SystemLogs::create([
                    'system' => 'ATM Monitoring',
                    'action' => 'Create',
                    'title' => 'Create Transaction',
                    'description' => $reason .' | '.$TransactionNumber,
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

    public function TransactionReplacementCreate(Request $request)
    {
        $reason_for_pull_out  = $request->reason_for_pull_out;

        $atm_id               = $request->atm_id;
        $remarks              = $request->remarks ?? NULL;

        $AtmClientBanks = AtmClientBanks::with('ClientInformation','Branch')->findOrFail($atm_id);
        $AtmClientBanks->update([ 'updated_at' => Carbon::now(), ]);

        $BankAccountNo = $AtmClientBanks->bank_account_no;
        $BankName = $AtmClientBanks->bank_name;
        $BankType = $AtmClientBanks->atm_type;
        $PensionNumber = $AtmClientBanks->ClientInformation->pension_number;
        $TransactionNumber = $AtmClientBanks->transaction_number;
        $ClientInformationId = $AtmClientBanks->client_information_id;
        $PinCode = $AtmClientBanks->pin_no;
        $ExpirationDate = $AtmClientBanks->expiration_date;
        $CollectionDate = $AtmClientBanks->collection_date;
        $CreatedDate = $AtmClientBanks->created_at;
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
            $new_balance = $request->new_balance ?? NULL;
            $new_remarks = $request->new_remarks ?? NULL;

            if($reason_for_pull_out == '4' || $reason_for_pull_out == '12')
            {
                $atm_number_new_raw  = $request->new_atm_number ?? NULL;
                $new_bank_list = $request->new_bank_name ?? NULL;
                $new_pin_code = $request->new_pin_code ?? NULL;
                $new_collection_date = $request->new_collection_date ?? NULL;
                $new_atm_status = $request->new_atm_status ?? NULL;
                $atm_number_new = str_replace("-", "", $atm_number_new_raw);
                $new_atm_type = $request->new_atm_type ?? NULL;
                $expirationDate = $request->new_expiration_date;
                if ($expirationDate) {
                    $expirationDate .= '-01';
                } else {
                    $expirationDate = null;
                }

                // Creation of Transaction Number
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
                // Creation of Transaction Number

                $replacement_same_atm = $request->replacement_same_atm ?? NULL;

                $selectBankName = ''; // or use an appropriate default value
                $selectBanknumber = ''; // or use an appropriate default value
                $selectTransactionNumber = ''; // or use an appropriate default value

                // Validate if Replace Same Bank Account No Or Different Bank Account
                if ($replacement_same_atm === 'replacement_same_atm') {
                    $selectBanknumber = $BankAccountNo;
                    $selectBankName = $BankName;
                    $selectTransactionNumber = $TransactionNumber;

                    $existingAtmClientBank = AtmClientBanks::where('bank_account_no', $BankAccountNo)->first();
                    if ($existingAtmClientBank) {
                        $existingAtmClientBank->increment('replacement_count');
                    }
                }
                else {
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
                    $selectTransactionNumber = $reference_number;
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
                    // Creation of Transaction for The Replaced ATM
                        $AtmClientBanks = AtmClientBanks::create([
                            'client_information_id' => $ClientInformationId,
                            'transaction_number' => $selectTransactionNumber,
                            'branch_id' => $branch_id ?? NULL,
                            'atm_type' => $new_atm_type ?? NULL,
                            'atm_status' => $new_atm_status ?? NULL,
                            'location' => 'Branch',
                            'bank_account_no' => $selectBanknumber ?? NULL,
                            'bank_name' => $selectBankName ?? NULL,
                            'pin_no' => $new_pin_code ?? NULL,
                            'expiration_date' => $expirationDate,
                            'collection_date' => $new_collection_date ?? NULL,
                            'replacement_count' => $replacement_same_atm === 'replacement_same_atm' ? ($existingAtmClientBank->replacement_count ?? 0) : 0,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);

                        $AtmBanksTransaction = AtmBanksTransaction::create([
                            'client_banks_id' => $AtmClientBanks->id,
                            'transaction_actions_id' => 17,
                            'request_by_employee_id' => Auth::user()->employee_id,
                            'transaction_number' => $selectTransactionNumber,
                            'atm_type' => $new_atm_type ?? NULL,
                            'bank_account_no' => $selectBanknumber ?? NULL,
                            'branch_id' => $branch_id,
                            'status' => 'ON GOING',
                            'reason' => 'New ATM ( Replacement )',
                            'reason_remarks' =>'New ATM ( Replacement ) - From Borrow Transaction',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);

                        $DataTransactionSequence = DataTransactionSequence::where('transaction_actions_id', 17)->orderBy('sequence_no')->get();

                            foreach ($DataTransactionSequence as $transactionSequence)
                            {
                                // Set the status based on the sequence number
                                $status = ($transactionSequence->sequence_no == '1') ? 'Pending' : 'Stand By';

                                AtmBanksTransactionApproval::create([
                                    'banks_transactions_id' => $AtmBanksTransaction->id,
                                    'transaction_actions_id' => 17,
                                    'employee_id' => NULL,
                                    'date_approved' => NULL,
                                    'user_groups_id' => $transactionSequence->user_group_id,
                                    'sequence_no' => $transactionSequence->sequence_no,
                                    'status' => $status,
                                    'type' => $transactionSequence->type,
                                    'created_at' => Carbon::now(),
                                ]);
                            }

                            $balance = floatval(preg_replace('/[^\d]/', '', $new_balance));

                            AtmTransactionBalanceLogs::create([
                                'banks_transactions_id' => $AtmBanksTransaction->id,
                                'check_by_employee_id' => Auth::user()->employee_id,
                                'balance' => $balance,
                                'remarks' => $new_remarks,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);
                    // Creation of Transaction for The Replaced ATM

                    // Old has Returned by Bank
                        if($reason_for_pull_out == '12')
                        {
                            $AtmReturnOldClientBanks = AtmClientBanks::findOrFail($atm_id);
                            $AtmReturnOldClientBanks->update([
                                'atm_status' => 'old',
                                'status' => '6',
                                'transaction_number' => $TransactionNumber.'-RO',
                                'updated_at' => Carbon::now(),
                            ]);

                            $AtmBanksTransaction = AtmBanksTransaction::create([
                                'client_banks_id' => $AtmReturnOldClientBanks->id,
                                'transaction_actions_id' => 12,
                                'request_by_employee_id' => Auth::user()->employee_id,
                                'transaction_number' => $TransactionNumber.'-RO',
                                'atm_type' => $BankType ?? NULL,
                                'bank_account_no' => $BankAccountNo ?? NULL,
                                'branch_id' => $branch_id,
                                'status' => 'ON GOING',
                                'reason' => 'Returning of Old ATM to Head Office',
                                'reason_remarks' => 'Returning of Old ATM to Head Office - From Borrow Transaction',
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);

                            $DataTransactionSequence = DataTransactionSequence::where('transaction_actions_id', 12)->orderBy('sequence_no')->get();

                            foreach ($DataTransactionSequence as $transactionSequence)
                            {
                                // Set the status based on the sequence number
                                $status = ($transactionSequence->sequence_no == '1') ? 'Pending' : 'Stand By';

                                AtmBanksTransactionApproval::create([
                                    'banks_transactions_id' => $AtmBanksTransaction->id,
                                    'transaction_actions_id' => 12,
                                    'employee_id' => NULL,
                                    'date_approved' => NULL,
                                    'user_groups_id' => $transactionSequence->user_group_id,
                                    'sequence_no' => $transactionSequence->sequence_no,
                                    'status' => $status,
                                    'type' => $transactionSequence->type,
                                    'created_at' => Carbon::now(),
                                ]);
                            }
                        }
                    // Old has Returned by Bank

                    // Old has not Return by Bank
                    if($reason_for_pull_out == '4') {
                        $AtmOldClientBanks = AtmClientBanks::findOrFail($atm_id);
                        $AtmOldClientBanks->update([
                            'location' => 'Released',
                            'status' => '5'
                        ]);
                    }
                }
            }
            else
            {
                $reason = "$BankType ATM Did not Replaced";

                $AtmBanksTransaction = AtmBanksTransaction::create([
                    'client_banks_id' => $atm_id,
                    'transaction_actions_id' => $reason_for_pull_out,
                    'transaction_number' => $TransactionNumber,
                    'request_by_employee_id' => Auth::user()->employee_id,
                    'bank_account_no' => $BankAccountNo,
                    'atm_type' => $BankType,
                    'branch_id' => $branch_id,
                    'reason' => $reason,
                    'reason_remarks' => $new_remarks,
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
                    'check_by_employee_id' => Auth::user()->employee_id,
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

        return response()->json([
            'status' => 'success',
            'message' => 'Transaction Created successfully!'  // Changed message to reflect update action
        ]);
    }

    public function TransactionReleaseCreate(Request $request)
    {
        $reason_for_pull_out  = $request->reason_for_pull_out;

        $atm_id = $request->atm_id;
        $action_name = $request->action_name ?? NULL;
        $transaction_action_id = $request->transaction_action_id ?? NULL;
        $reason = $request->reason ?? NULL;
        $remarks = $request->remarks ?? NULL;

        // Fetch Data From AtmClientBanks
            $AtmClientBanks = AtmClientBanks::with('ClientInformation','Branch')->findOrFail($atm_id);
            $AtmClientBanks->update([ 'updated_at' => Carbon::now(), ]);

                $BankAccountNo = $AtmClientBanks->bank_account_no;
                $BankName = $AtmClientBanks->bank_name;
                $BankType = $AtmClientBanks->atm_type;
                $PensionNumber = $AtmClientBanks->ClientInformation->pension_number;
                $TransactionNumber = $AtmClientBanks->transaction_number;
                $ClientInformationId = $AtmClientBanks->client_information_id;
                $PinCode = $AtmClientBanks->pin_no;
                $ExpirationDate = $AtmClientBanks->expiration_date;
                $CollectionDate = $AtmClientBanks->collection_date;
                $CreatedDate = $AtmClientBanks->created_at;
                $branch_id = $AtmClientBanks->branch_id;
        // Fetch Data From AtmClientBanks

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
            if($reason_for_pull_out == 8)
            {
                $image_file = $request->file('upload_file');
                if ($image_file) {
                    // Get file extension and validate it
                    $fileExtension = strtolower($image_file->getClientOriginalExtension());
                    $validExtensions = ['jpg', 'jpeg', 'png'];

                    if (!in_array($fileExtension, $validExtensions)) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Invalid file type. Only JPG, JPEG, and PNG are allowed.'
                        ]); // Added status code for client error
                    }

                    // Get branch location
                    $BranchGet = Branch::where('id', $branch_id)->first();
                    if (!$BranchGet) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Branch not found'
                        ]);
                    }
                    $branchLocation = $BranchGet->branch_location;

                    // Define the folder path
                    $folderPath = 'upload/' . date('Y') . '/' . $branchLocation . '/' . $action_name . '/' . date('m-d-Y') . '/';

                    // Create folder if it doesn't exist
                    if (!File::exists($folderPath)) {
                        File::makeDirectory($folderPath, 0755, true, true); // Create directories with permissions
                    }

                    // Generate unique filename
                    $filename = $TransactionNumber . '_' . $transaction_action_id . '_' . date('mdy') . '_' . date('hiA') . '.' . $fileExtension;

                    // Define the target file path
                    $targetFilePath = $folderPath . $filename;

                    if ($image_file->move($folderPath, $filename)) {
                        // Save the file details in the database
                        $AtmBanksTransaction = AtmBanksTransaction::create([
                            'client_banks_id' => $atm_id,
                            'transaction_actions_id' => $reason_for_pull_out,
                            'transaction_number' => $TransactionNumber,
                            'request_by_employee_id' => Auth::user()->employee_id,
                            'bank_account_no' => $BankAccountNo,
                            'atm_type' => $BankType,
                            'branch_id' => $branch_id,
                            'aprb_no' => NULL,
                            'reason' => 'Sending of Yellow Paper',
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

                        $balance = floatval(preg_replace('/[^\d]/', '', $request->atm_balance ?? 0));

                        AtmTransactionBalanceLogs::create([
                            'banks_transactions_id' => $AtmBanksTransaction->id,
                            'check_by_employee_id' => Auth::user()->employee_id,
                            'balance' => $balance,
                            'remarks' => $remarks ?? NULL,
                            'created_at' => Carbon::now(),
                        ]);

                        AtmReleasedClientImage::create([
                            'banks_transactions_id' => $AtmBanksTransaction->id,
                            'filename' => $targetFilePath,  // Store file path
                            'image_name' => $filename,  // Store only the filename or you can store the full path
                            'update_by_employee_id' => Auth::user()->employee_id,
                            'type' => 'Sending of Yellow Paper',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);

                        $AtmClientBanks->update([
                            'location' => 'Released',
                            'status' => '0',
                        ]);

                        // Create System Logs used for Auditing of Logs
                        SystemLogs::create([
                            'system' => 'ATM Monitoring',
                            'action' => 'Create',
                            'title' => 'Create Transaction',
                            'description' => 'Creation of New Transaction'.' : ' . $TransactionNumber .' | '. 'Sending of Yellow Paper',
                            'employee_id' => Auth::user()->employee_id,
                            'ip_address' => $request->ip(),
                            'created_at' => Carbon::now(),
                            'company_id' => Auth::user()->company_id,
                        ]);
                    } else {
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Failed to Upload Image!'  // Changed message to reflect update action
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'No Image Found!'  // Changed message to reflect update action
                    ]);
                }

            }
            if($reason_for_pull_out == 15)
            {
                $image_file = $request->file('upload_file');
                if ($image_file) {
                    // Get file extension and validate it
                    $fileExtension = strtolower($image_file->getClientOriginalExtension());
                    $validExtensions = ['jpg', 'jpeg', 'png'];

                    if (!in_array($fileExtension, $validExtensions)) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Invalid file type. Only JPG, JPEG, and PNG are allowed.'
                        ]); // Added status code for client error
                    }

                    // Get branch location
                    $BranchGet = Branch::where('id', $branch_id)->first();
                    if (!$BranchGet) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Branch not found'
                        ]);
                    }
                    $branchLocation = $BranchGet->branch_location;

                    // Define the folder path
                    $folderPath = 'upload/' . date('Y') . '/' . $branchLocation . '/' . $action_name . '/' . date('m-d-Y') . '/';

                    // Create folder if it doesn't exist
                    if (!File::exists($folderPath)) {
                        File::makeDirectory($folderPath, 0755, true, true); // Create directories with permissions
                    }

                    // Generate unique filename
                    $filename = $TransactionNumber . '_' . $transaction_action_id . '_' . date('mdy') . '_' . date('hiA') . '.' . $fileExtension;

                    // Define the target file path
                    $targetFilePath = $folderPath . $filename;

                    if ($image_file->move($folderPath, $filename)) {
                        // Save the file details in the database
                        $AtmBanksTransaction = AtmBanksTransaction::create([
                            'client_banks_id' => $atm_id,
                            'transaction_actions_id' => $reason_for_pull_out,
                            'transaction_number' => $TransactionNumber,
                            'request_by_employee_id' => Auth::user()->employee_id,
                            'bank_account_no' => $BankAccountNo,
                            'atm_type' => $BankType,
                            'branch_id' => $branch_id,
                            'aprb_no' => NULL,
                            'reason' => 'Returning of Cancelled Form',
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

                        $balance = floatval(preg_replace('/[^\d]/', '', $request->atm_balance ?? 0));

                        AtmTransactionBalanceLogs::create([
                            'banks_transactions_id' => $AtmBanksTransaction->id,
                            'check_by_employee_id' => Auth::user()->employee_id,
                            'balance' => $balance,
                            'remarks' => $remarks ?? NULL,
                            'created_at' => Carbon::now(),
                        ]);

                        AtmReleasedClientImage::create([
                            'banks_transactions_id' => $AtmBanksTransaction->id,
                            'filename' => $targetFilePath,  // Store file path
                            'image_name' => $filename,  // Store only the filename or you can store the full path
                            'update_by_employee_id' => Auth::user()->employee_id,
                            'type' => 'Returning of Cancelled Form',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);

                        $AtmClientBanks->update([
                            'location' => 'Released',
                            'status' => '7',
                        ]);

                        // Create System Logs used for Auditing of Logs
                        SystemLogs::create([
                            'system' => 'ATM Monitoring',
                            'action' => 'Create',
                            'title' => 'Create Transaction',
                            'description' => 'Creation of New Transaction'.' : ' . $TransactionNumber .' | '. 'Returning of Cancelled Form',
                            'employee_id' => Auth::user()->employee_id,
                            'ip_address' => $request->ip(),
                            'created_at' => Carbon::now(),
                            'company_id' => Auth::user()->company_id,
                        ]);
                    } else {
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Failed to Upload Image!'  // Changed message to reflect update action
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'No Image Found!'  // Changed message to reflect update action
                    ]);
                }

            }
            else
            {
                if($reason_for_pull_out == '6')
                {
                    $reason = 'For Safekeeping';
                } else if($reason_for_pull_out == '7')
                {
                    $reason = 'For Renewal';
                } else {
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
                    'aprb_no' => NULL,
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
                    'check_by_employee_id' => Auth::user()->employee_id,
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

    public function TransactionAddAtm(Request $request)
    {
        $reason_for_pull_out  = $request->reason_for_pull_out;

        $atm_id = $request->atm_id;
        $action_name = $request->action_name ?? NULL;
        $transaction_action_id = $request->transaction_action_id ?? NULL;
        $reason = $request->reason ?? NULL;
        $remarks = $request->remarks ?? NULL;

        // Fetch Data From AtmClientBanks
            $AtmClientBanks = AtmClientBanks::with('ClientInformation','Branch')->findOrFail($atm_id);
            $AtmClientBanks->update([ 'updated_at' => Carbon::now(), ]);

                $BankAccountNo = $AtmClientBanks->bank_account_no;
                $BankName = $AtmClientBanks->bank_name;
                $BankType = $AtmClientBanks->atm_type;
                $PensionNumber = $AtmClientBanks->ClientInformation->pension_number;
                $TransactionNumber = $AtmClientBanks->transaction_number;
                $ClientInformationId = $AtmClientBanks->client_information_id;
                $PinCode = $AtmClientBanks->pin_no;
                $ExpirationDate = $AtmClientBanks->expiration_date;
                $CollectionDate = $AtmClientBanks->collection_date;
                $CreatedDate = $AtmClientBanks->created_at;
                $branch_id = $AtmClientBanks->branch_id;
        // Fetch Data From AtmClientBanks

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
                $BankAccountNo = str_replace('-', '', $request->atm_number);

                // Validate First Existing Bank Account No Start
                        $existingAccount = AtmClientBanks::where('bank_account_no', $BankAccountNo)
                            ->whereNotNull('bank_account_no')
                            ->first(); // Fetch the first match

                        // If a duplicate is found, return an error response
                        if ($existingAccount) {
                            return response()->json([
                                'status' => 'error',
                                'message' => "Duplicate ATM / Passbook / Sim Number: {$BankAccountNo},"
                            ]);
                        }
                // Validate First Existing Bank Account No End

                // Create Transaction Number
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

                    $transactionCounter = $lastadded + 1;
                    $TransactionNumber = $branch_abbreviation . '-' . date('mdy') . '-' . str_pad($transactionCounter, 5, '0', STR_PAD_LEFT);
                // Create Transaction Number

                $reason = 'Add ATM';

                $expirationDate = $request->expiration_date;

                if ($expirationDate) {
                    $expirationDate .= '-01';
                } else {
                    $expirationDate = null;
                }

                $AtmClientBanks = AtmClientBanks::create([
                    'client_information_id' => $ClientInformationId,
                    'transaction_number' => $TransactionNumber,
                    'branch_id' => $branch_id ?? NULL,
                    'atm_type' => $request->atm_type ?? NULL,
                    'atm_status' => $request->atm_status ?? NULL,
                    'location' => 'Branch',
                    'bank_account_no' => $BankAccountNo ?? NULL,
                    'bank_name' => $request->bank_name ?? NULL,
                    'pin_no' => $request->pin_code ?? NULL,
                    'expiration_date' => $expirationDate,
                    'collection_date' => $request->collection_date ?? NULL,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                $AtmBanksTransaction = AtmBanksTransaction::create([
                    'client_banks_id' => $AtmClientBanks->id,
                    'transaction_actions_id' => $reason_for_pull_out,
                    'transaction_number' => $TransactionNumber,
                    'request_by_employee_id' => Auth::user()->employee_id,
                    'bank_account_no' => $BankAccountNo ?? NULL,
                    'atm_type' => $request->atm_type ?? NULL,
                    'branch_id' => $branch_id,
                    'aprb_no' => NULL,
                    'reason' => $reason,
                    'reason_remarks' => NULL,
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
                    'check_by_employee_id' => Auth::user()->employee_id,
                    'balance' => $balance,
                    'remarks' => $remarks ?? NULL,
                    'created_at' => Carbon::now(),
                ]);

                // Create System Logs used for Auditing of Logs
                SystemLogs::create([
                    'system' => 'ATM Monitoring',
                    'action' => 'Create',
                    'title' => 'Create Transaction',
                    'description' => $reason .' | '.$TransactionNumber,
                    'employee_id' => Auth::user()->employee_id,
                    'ip_address' => $request->ip(),
                    'created_at' => Carbon::now(),
                    'company_id' => Auth::user()->company_id,
                ]);

        }

        return response()->json([
            'status' => 'success',
            'message' => 'Transaction Created successfully!'  // Changed message to reflect update action
        ]);
    }

    public function TransactionReceivingPage()
    {
        $branch_id = Auth::user()->branch_id;
        $Branches = Branch::where('status', 'Active')->get();
        $DataTransactionAction = DataTransactionAction::where('status', 'Active')->get();

        return view('pages.pages_backend.atm.atm_receiving_of_transaction', compact('branch_id','Branches','DataTransactionAction'));
    }

    public function TransactionReleasingPage()
    {
        $branch_id = Auth::user()->branch_id;
        $Branches = Branch::where('status', 'Active')->get();
        $DataTransactionAction = DataTransactionAction::where('status', 'Active')->get();

        return view('pages.pages_backend.atm.atm_releasing_of_transaction', compact('branch_id','Branches','DataTransactionAction'));
    }

    public function TransactionReceivingData(Request $request)
    {
        $userBranchId = Auth::user()->branch_id;
        $userGroup = Auth::user()->UserGroup->group_name;

        $query = AtmBanksTransactionApproval::with('DataUserGroup', 'Employee', 'DataTransactionAction',
                'AtmBanksTransaction',
                'AtmBanksTransaction.Branch',
                'AtmBanksTransaction.AtmClientBanks',
                'AtmBanksTransaction.AtmClientBanks.ClientInformation')
            ->whereIn('status', ['Pending','Completed','Cancelled']) // Use whereIn for multiple values
            ->where('type', 'Received')
            ->orderBy('id', 'asc'); // Corrected syntax for descending order

            // Apply user group filter unless the user group is Developer
            if (!in_array($userGroup, ['Developer', 'Admin', 'Everfirst Admin'])) {
                $query->where('user_groups_id', Auth::user()->user_group_id);
            }

        // Apply branch filter based on user branch_id or request input
        $query->whereHas('AtmBanksTransaction', function ($query) use ($userBranchId, $request) {
            if ($userBranchId) {
                $query->where('branch_id', $userBranchId);
            } elseif ($request->filled('branch_id')) {
                $query->where('branch_id', $request->branch_id);
            }
        });

        $pendingReceivingTransaction = $query->get();

        return DataTables::of($pendingReceivingTransaction)
            ->setRowId('id')
            ->addColumn('checkbox', function($row){
                $checkbox = '';
                if($row->status == 'Completed'){
                    $checkbox = '';
                } else if ($row->status == 'Pending'){
                    $checkbox = '<input type="checkbox" class="check check-item" data-id="' . $row->id . '"/>';
                } else if ($row->status == 'Cancelled') {
                    $checkbox = '';
                } else {
                    $checkbox = '';
                }
                return $checkbox;
            })
            ->addColumn('action', function($row) use ($userGroup){
                $action = '';
                if($row->status == 'Pending'){
                    $action .= '<a href="#" class="btn btn-success btn-sm receivedTransaction me-2 mb-2"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="Receive Transaction"
                                    data-id="' . $row->id . '"
                                    data-transaction_id="' . $row->banks_transactions_id . '">
                                    Receive
                                </a>';

                    if($userGroup == 'Rider')
                    {
                        $action .= '';
                    } else {
                        // Cancelled Loan Has no Cancelled
                        if($row->transaction_actions_id == 13){
                            $action .= '';
                        } else {
                            $action .= '<a href="#" class="btn btn-danger btn-sm cancelledTransaction me-2 mb-2"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Cancel Transaction"
                                            data-id="' . $row->id . '"
                                            data-transaction_id="' . $row->banks_transactions_id . '"
                                            data-transaction_number="' . $row->AtmBanksTransaction->transaction_number . '">
                                            <i class="fas fa-times-circle"></i>
                                        </a>';
                        }
                    }
                }
                else if ($row->status == 'Completed'){
                        $dateApproved = $row->date_approved ? Carbon::parse($row->date_approved)->format('M j, Y - h:i A') : 'N/A';
                        $action = '<span class="fw-bold text-danger">Already Received</span><br>' .$dateApproved;
                } else if ($row->status == 'Cancelled') {
                    $action = 'Cancelled';
                } else {
                    $action = '';
                }
                return $action;
            })
            ->addColumn('full_name', function ($row) {
                // Check if the relationships and fields exist
                $clientInfo = $row->AtmBanksTransaction->AtmClientBanks->ClientInformation ?? null;
                $branchLocation = $row->AtmBanksTransaction->Branch->branch_location ?? 'N/A'; // Default to 'N/A' if branch_location is missing

                if ($clientInfo) {
                    $lastName = $clientInfo->last_name ?? '';
                    $firstName = $clientInfo->first_name ?? '';
                    $middleName = $clientInfo->middle_name ? ' ' . $clientInfo->middle_name : ''; // Add space if middle_name exists
                    $suffix = $clientInfo->suffix ? ', ' . $clientInfo->suffix : ''; // Add comma if suffix exists

                    // Combine the parts into the full name
                    $fullName = "{$lastName}, {$firstName}{$middleName}{$suffix}";
                } else {
                    // Fallback if client information is missing
                    $fullName = 'N/A';
                }

                return "<span>{$fullName}</span> <br> <span class='text-primary'>{$branchLocation}</span>";
            })
            ->addColumn('pension_details', function ($row) {
                // Check if the relationships and fields exist
                $pensionDetails = $row->AtmBanksTransaction->AtmClientBanks->ClientInformation ?? null;

                if ($pensionDetails) {
                    $PensionNumber = $pensionDetails->pension_number ?? '';
                    $PensionType = $pensionDetails->pension_account_type ?? '';
                    $AccountType = $pensionDetails->pension_type ?? '';

                    // Combine the parts into the full name
                    $pension_details = "<span class='fw-bold text-primary h6 pension_number_mask_display'>{$PensionNumber}</span><br>
                                       <span class='fw-bold'>{$PensionType}</span><br>
                                       <span class='text-success'>{$AccountType}</span>";
                } else {
                    // Fallback if client information is missing
                    $pension_details = 'N/A';
                }

                return $pension_details;
            })
            ->rawColumns(['checkbox','action','full_name','pension_details']) // Render HTML in both the action and pending_to columns
            ->make(true);
    }

    public function TransactionReleasingData(Request $request)
    {
        $userBranchId = Auth::user()->branch_id;
        $userGroup = Auth::user()->UserGroup->group_name;

        $query = AtmBanksTransactionApproval::with('DataUserGroup', 'Employee', 'DataTransactionAction',
                'AtmBanksTransaction',
                'AtmBanksTransaction.Branch',
                'AtmBanksTransaction.AtmClientBanks',
                'AtmBanksTransaction.AtmClientBanks.ClientInformation')
            ->whereIn('status', ['Pending','Completed','Cancelled']) // Use whereIn for multiple values
            ->where('type', 'Released')
            ->orderBy('id', 'desc'); // Corrected syntax for descending order

            // Apply user group filter unless the user group is Developer
            if (!in_array($userGroup, ['Developer', 'Admin', 'Everfirst Admin'])) {
                $query->where('user_groups_id', Auth::user()->user_group_id);
            }

        // Apply branch filter based on user branch_id or request input
        $query->whereHas('AtmBanksTransaction', function ($query) use ($userBranchId, $request) {
            if ($userBranchId) {
                $query->where('branch_id', $userBranchId);
            } elseif ($request->filled('branch_id')) {
                $query->where('branch_id', $request->branch_id);
            }
        });

        $pendingReceivingTransaction = $query->get();

        return DataTables::of($pendingReceivingTransaction)
            ->setRowId('id')
            ->addColumn('checkbox', function($row){
                $checkbox = '';
                if($row->status == 'Completed'){
                    $checkbox = '';
                } else if ($row->status == 'Pending'){
                    $checkbox = '<input type="checkbox" class="check check-item" data-id="' . $row->id . '"/>';
                } else if ($row->status == 'Cancelled') {
                    $checkbox = '';
                } else {
                    $checkbox = '';
                }
                return $checkbox;
            })
            ->addColumn('action', function($row) use ($userGroup){
                $action = '';
                if($row->status == 'Pending'){
                    $action .= '<a href="#" class="btn btn-success btn-sm receivedTransaction me-2 mb-2"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="Receive Transaction"
                                    data-id="' . $row->id . '"
                                    data-transaction_id="' . $row->banks_transactions_id . '">
                                    Receive
                                </a>';

                    if($userGroup == 'Rider')
                    {
                        $action .= '';
                    } else {
                        // Cancelled Loan Has no Cancelled
                        if($row->transaction_actions_id == 13){
                            $action .= '';
                        } else {
                            $action .= '<a href="#" class="btn btn-danger btn-sm cancelledTransaction me-2 mb-2"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Cancel Transaction"
                                            data-id="' . $row->id . '"
                                            data-transaction_id="' . $row->banks_transactions_id . '"
                                            data-transaction_number="' . $row->AtmBanksTransaction->transaction_number . '">
                                            <i class="fas fa-times-circle"></i>
                                        </a>';
                        }
                    }
                }
                else if ($row->status == 'Completed'){
                        $dateApproved = $row->date_approved ? Carbon::parse($row->date_approved)->format('M j, Y - h:i A') : 'N/A';
                        $action = '<span class="fw-bold text-danger">Already Received</span><br>' .$dateApproved;
                } else if ($row->status == 'Cancelled') {
                    $action = 'Cancelled';
                } else {
                    $action = '';
                }
                return $action;
            })
            ->addColumn('full_name', function ($row) {
                // Check if the relationships and fields exist
                $clientInfo = $row->AtmBanksTransaction->AtmClientBanks->ClientInformation ?? null;
                $branchLocation = $row->AtmBanksTransaction->Branch->branch_location ?? 'N/A'; // Default to 'N/A' if branch_location is missing

                if ($clientInfo) {
                    $lastName = $clientInfo->last_name ?? '';
                    $firstName = $clientInfo->first_name ?? '';
                    $middleName = $clientInfo->middle_name ? ' ' . $clientInfo->middle_name : ''; // Add space if middle_name exists
                    $suffix = $clientInfo->suffix ? ', ' . $clientInfo->suffix : ''; // Add comma if suffix exists

                    // Combine the parts into the full name
                    $fullName = "{$lastName}, {$firstName}{$middleName}{$suffix}";
                } else {
                    // Fallback if client information is missing
                    $fullName = 'N/A';
                }

                return "<span>{$fullName}</span> <br> <span class='text-primary'>{$branchLocation}</span>";
            })
            ->addColumn('pension_details', function ($row) {
                // Check if the relationships and fields exist
                $pensionDetails = $row->AtmBanksTransaction->AtmClientBanks->ClientInformation ?? null;

                if ($pensionDetails) {
                    $PensionNumber = $pensionDetails->pension_number ?? '';
                    $PensionType = $pensionDetails->pension_account_type ?? '';
                    $AccountType = $pensionDetails->pension_type ?? '';

                    // Combine the parts into the full name
                    $pension_details = "<span class='fw-bold text-primary h6 pension_number_mask_display'>{$PensionNumber}</span><br>
                                       <span class='fw-bold'>{$PensionType}</span><br>
                                       <span class='text-success'>{$AccountType}</span>";
                } else {
                    // Fallback if client information is missing
                    $pension_details = 'N/A';
                }

                return $pension_details;
            })
            ->rawColumns(['checkbox','action','full_name','pension_details']) // Render HTML in both the action and pending_to columns
            ->make(true);
    }

    public function TransactionTransferBranch(Request $request)
    {
        $atm_id = $request->atm_id;
        $remarks = $request->remarks ?? NULL;
        $branch_id = $request->branch_id ?? NULL;

        $AtmClientBanks = AtmClientBanks::with('ClientInformation','Branch')->findOrFail($atm_id);
        $AtmClientBanks->update([
            'updated_at' => Carbon::now(),
            'branch_id' => $branch_id,
        ]);

        $TransactionNumber = $AtmClientBanks->transaction_number;
        $ClientInformationId = $AtmClientBanks->client_information_id;
        $OldBranchLocation = $AtmClientBanks->Branch->branch_location;

        $Branch = Branch::findOrFail($branch_id);
        $NewBranchLocation = $Branch->branch_location;

        $ClientInformation = ClientInformation::findOrFail($ClientInformationId);
        $ClientInformation->update([
            'updated_at' => Carbon::now(),
            'branch_id' => $branch_id,
        ]);

        SystemLogs::create([
            'system' => 'ATM Monitoring',
            'action' => 'Update',
            'title' => 'Transfer Client to Other Branch',
            'description' => 'From : ' . $OldBranchLocation .' To : '. $NewBranchLocation . ' | ' . $TransactionNumber .' | '.$remarks,
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Transaction Created successfully!'  // Changed message to reflect update action
        ]);
    }

    public function TransactionEditClient(Request $request)
    {
        $atm_id = $request->atm_id ?? NULL;
        $AtmClientBanks = AtmClientBanks::with('ClientInformation','Branch')->findOrFail($atm_id);
        $OldBankAccountNo = $AtmClientBanks->bank_account_no;
        $TransactionNumber = $AtmClientBanks->transaction_number;

        $BankAccountNo = str_replace('-', '', $request->atm_number);

        // Validate First Existing Bank Account No Start
            if($BankAccountNo != $OldBankAccountNo){
                $existingAccount = AtmClientBanks::where('bank_account_no', $BankAccountNo)
                    ->whereNotNull('bank_account_no')
                    ->first(); // Fetch the first match

                // If a duplicate is found, return an error response
                if ($existingAccount) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "Duplicate ATM / Passbook / Sim Number: {$BankAccountNo},"
                    ]);
                }
            }
            else
            {
                $expirationDate = $request->expiration_date;

                if ($expirationDate) {
                    $expirationDate .= '-01';
                } else {
                    $expirationDate = null;
                }


                // Update other fields
                $AtmClientBanks->update([
                    'branch_id' => $request->branch_id ?? NULL,
                    'atm_type' => $request->atm_type ?? NULL,
                    'atm_status' => $request->atm_status ?? NULL,
                    'location' => 'Branch',
                    'bank_account_no' => $BankAccountNo ?? NULL,
                    'bank_name' => $request->bank_name ?? NULL,
                    'pin_no' => $request->pin_code ?? NULL,
                    'cash_box_no' => $request->cash_box_no ?? NULL,
                    'expiration_date' => $expirationDate,
                    'collection_date' => $request->collection_date ?? NULL,
                    'updated_at' => Carbon::now(),
                ]);

                $replace_status = $request->replace_status ?? NULL;
                if ($replace_status === 'yes') {
                    $AtmClientBanks->update([
                        'replacement_count' => $AtmClientBanks->replacement_count + 1, // Increment only
                    ]);
                }

                // Replacement count remains unchanged if replace_status is not 'yes'

                $ClientInformationId = $AtmClientBanks->client_information_id;

                $ClientInformation = ClientInformation::findOrFail($ClientInformationId);
                $ClientInformation->update([
                    'branch_id' => $request->branch_id ?? NULL,
                    'pension_type' => $request->pension_type ?? NULL,
                    'pension_account_type' => $request->pension_account_type ?? NULL,
                    'first_name' => $request->first_name ?? NULL,
                    'middle_name' => $request->middle_name ?? NULL,
                    'last_name' => $request->last_name ?? NULL,
                    'suffix' => $request->suffix ?? NULL,
                    'birth_date' => $request->birth_date ?? NULL,
                    'updated_at' => Carbon::now(),

                ]);

                SystemLogs::create([
                    'system' => 'ATM Monitoring',
                    'action' => 'Update',
                    'title' => 'Edit Client Information',
                    'description' => $TransactionNumber,
                    'employee_id' => Auth::user()->employee_id,
                    'ip_address' => $request->ip(),
                    'created_at' => Carbon::now(),
                    'company_id' => Auth::user()->company_id,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Transaction Created successfully!'  // Changed message to reflect update action
                ]);
            }



    }

    public function TransactionUpdate(Request $request)
    {
        // Retrieve ATM ID and Transaction ID from the request, defaulting to NULL if not provided
        $atm_id = $request->atm_id ?? NULL;
        $transanction_id = $request->transanction_id ?? NULL;
        // Clean up the bank account number by removing hyphens
        $BankAccountNo = str_replace('-', '', $request->update_atm_bank_no);

        // Update the AtmClientBanks model
        $AtmClientBanks = AtmClientBanks::findOrFail($atm_id);
        $AtmClientBanks->update([
            'location' => $request->location,
            'status' => $request->bank_status,
            'bank_account_no' => $BankAccountNo,
            'updated_at' => Carbon::now(),
        ]);

        // Clean up the transaction bank account number
        $TransactionBankAccountNo = str_replace('-', '', $request->update_transaction_bank_no);

        // Update the AtmBanksTransaction model
        $AtmBanksTransaction = AtmBanksTransaction::findOrFail($transanction_id);

        $TransactionNumber = $AtmBanksTransaction->transaction_number;
        $TransactionAction = $AtmBanksTransaction->transaction_actions_id;
        $AtmBanksTransaction->update([
            'status' => $request->transaction_status,
            'reason' => $request->reason ?? NULL,
            'reason_remarks' => $request->reason_remarks ?? NULL,
            'bank_account_no' => $TransactionBankAccountNo,
            'updated_at' => Carbon::now(),
        ]);

        // Update approvals for each approval record
        foreach ($request->approval_id as $key => $approval_id) {
            $AtmBanksTransactionApproval = AtmBanksTransactionApproval::findOrFail($approval_id);
            $AtmBanksTransactionApproval->update([
                'employee_id' => $request->employee_id[$key],
                'date_approved' => $request->date_approved[$key],
                'status' => $request->status[$key],
            ]);
        }

        // Retrieve the transaction action data for logging purposes
        $DataTransactionAction = DataTransactionAction::findOrFail($TransactionAction);

        // Log the transaction update in the system logs
        SystemLogs::create([
            'system' => 'ATM Monitoring',
            'action' => 'Update',
            'title' => 'Update Transaction',
            'description' => $TransactionNumber . ' | ' . $DataTransactionAction->name,
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        // Return a successful response
        return response()->json([
            'status' => 'success',
            'message' => 'Transaction updated successfully!'
        ]);
    }



}
