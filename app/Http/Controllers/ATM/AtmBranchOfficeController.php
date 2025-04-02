<?php

namespace App\Http\Controllers\ATM;

use Illuminate\Http\Request;
use App\Models\DataBankLists;
use App\Models\ATM\AtmClientBanks;
use App\Models\DataCollectionDate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\DataTransactionAction;
use App\Models\System\MaintenancePage;

use App\Models\ATM\AtmBanksTransaction;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ATM\AtmBanksTransactionApproval;

class AtmBranchOfficeController extends Controller
{
    public function BranchOfficePage()
    {
        $userGroup = Auth::user()->UserGroup->group_name;

        $DataCollectionDate = DataCollectionDate::where('status','Active')->get();
        $DataBankLists = DataBankLists::where('status','Active')->get();
        $MaintenancePage = MaintenancePage::where('pages_name', 'Branch Office Page')->first();

        if ($MaintenancePage->status == 'yes') {
            if (in_array($userGroup, ['Developer', 'Admin'])) {
                return view('pages.pages_backend.atm.atm_branch_office_atm_lists', compact('DataCollectionDate','DataBankLists'));
            } else {
                return view('pages.pages_validate.pages-maintenance');
            }
        } else {
            return view('pages.pages_backend.atm.atm_branch_office_atm_lists', compact('DataCollectionDate','DataBankLists'));
        }
    }

    public function BranchOfficeData()
    {
        $userBranchId = Auth::user()->branch_id;
        $userGroup = Auth::user()->UserGroup->group_name;
        $userDepartment = Auth::user()->department;

        // Start the query with the necessary relationships
        $query = AtmClientBanks::with('ClientInformation', 'Branch', 'AtmBanksTransaction')
            ->where('location', 'Branch')
            ->orderBy('id','desc');

        // Check if the user has a valid branch_id
        if ($userBranchId !== null && $userBranchId !== 0) {
            // Filter by branch_id if it's set and valid
            $query->where('branch_id', $userBranchId);
        }

        // Get the filtered data
        $HeadOfficeData = $query->get();

        // Return the data as DataTables response
        return DataTables::of($HeadOfficeData)
            ->setRowId('id')
            ->addColumn('action', function($row) use ($userGroup) {
                $action = ''; // Initialize a variable to hold the buttons\
                $add_atm = '';

                if ($row->AtmBanksTransaction) {
                    $hasOngoingTransaction = AtmBanksTransaction::where('transaction_number', $row->transaction_number)
                        ->where('status','ON GOING')
                        ->where('oc_transaction','NO')
                        ->orderBy('id', 'desc') // Corrected sorting method
                        ->first();

                    $CompletedTransaction = AtmBanksTransaction::where('transaction_number', $row->transaction_number)
                        ->where('status','COMPLETED')
                        ->where('oc_transaction','NO')
                        ->orderBy('id', 'desc') // Corrected sorting method
                        ->first();

                        $aprb_no_details = $CompletedTransaction->aprb_no ?? '';
                } else {
                    // $action = '<button type="button" class="btn btn-warning borrow_transaction"
                    //                 data-id="'.$row->id.'"
                    //                 data-bs-toggle="tooltip"
                    //                 data-bs-placement="right"
                    //                 title="Returning of Borrow Transaction">
                    //             <i class="fas fa-undo"></i>
                    //             </button>';

                    $action = 'No Transaction History';
                    $aprb_no_details = '';
                }

                // Only show the button for users in specific groups
                if (in_array($userGroup, ['Developer', 'Admin', 'Branch Head', 'Everfirst Admin'])) {
                    if ($hasOngoingTransaction) {
                        // Display the spinning icon if there is any ongoing transaction
                        $action = '<i class="fas fa-spinner fa-spin fs-3 text-success me-2 mb-2"></i>';
                    } else if ($CompletedTransaction && $CompletedTransaction->transaction_actions_id) {
                        // Generate buttons based on `transaction_actions_id`
                        if ($CompletedTransaction->transaction_actions_id == 3 || $CompletedTransaction->transaction_actions_id == 9) { // Safekeep and Release
                            $action = '<button type="button" class="btn btn-success release_transaction me-2"
                                            data-id="'.$row->id.'"
                                            data-aprb_no="'.$aprb_no_details.'"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="right"
                                            title="Releasing of Transaction">
                                            <i class="fas fa-sign-in-alt"></i>
                                        </button>';
                        } else if ($CompletedTransaction->transaction_actions_id == 1) { // Borrow Transaction
                            $action = '<button type="button" class="btn btn-warning borrow_transaction me-2"
                                            data-id="'.$row->id.'"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="right"
                                            title="Returning of Borrow Transaction">
                                           <i class="fas fa-undo"></i>
                                        </button>';
                        } else if ($CompletedTransaction->transaction_actions_id == 11) { // Replacement Transaction
                            $action = '<button type="button" class="btn btn-primary replacement_atm_transaction me-2"
                                            data-id="'.$row->id.'"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="right"
                                            title="Replacement of ATM / Passbook Transaction">
                                          <i class="fas fa-exchange-alt"></i>
                                        </button>';
                        } else if ($CompletedTransaction->transaction_actions_id == 13) { // Cancelled Loan Transaction
                            $action = '<button type="button" class="btn btn-danger cancelled_loan_transaction me-2"
                                            data-id="'.$row->id.'"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="right"
                                            title="Cancelled Loan Transaction">
                                          <i class="fas fa-times-circle"></i>
                                        </button>';
                        } else if ($CompletedTransaction->transaction_actions_id == 16) { // Release w/ Balance Transaction
                            $action = '<button type="button" class="btn btn-danger release_balance_transaction me-2"
                                            data-id="'.$row->id.'"
                                            data-aprb_no="'.$aprb_no_details.'"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="right"
                                            title="Release with Outstanding Balance Transaction">
                                          <i class="fas fa-sign-in-alt"></i>
                                        </button>';
                        } else {
                            $action = '<button type="button" class="btn btn-warning borrow_transaction me-2"
                                            data-id="'.$row->id.'"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="right"
                                            title="Returning of Borrow Transaction">
                                           <i class="fas fa-undo"></i>
                                        </button>';
                        }
                    } else {
                        $action = '<button type="button" class="btn btn-warning borrow_transaction me-2 me-2"
                                        data-id="'.$row->id.'"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="right"
                                        title="Returning of Borrow Transaction">
                                    <i class="fas fa-undo"></i>
                                    </button>';
                    }

                    // For ADD ATM Transaction
                    $OnGoingTransaction = AtmBanksTransaction::where('transaction_number', $row->transaction_number)
                        ->where('status','ON GOING')
                        ->where('oc_transaction','NO')
                        ->first();

                    if ($OnGoingTransaction && ((in_array($userGroup, ['Developer', 'Admin', 'Branch Head', 'Everfirst Admin'])))){
                        if($OnGoingTransaction->transaction_actions_id == '5' || $OnGoingTransaction->transaction_actions_id == '23'){
                            $add_atm = '<a href="#" class="btn btn-success addAtmTransaction me-2"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="Add ATM"
                                                data-id="' . $row->id . '">
                                            <i class="fas fa-credit-card"></i>
                                        </a>';
                        } else {
                            $add_atm = '';
                        }
                    } else {
                        $add_atm = '';
                    }
                }

                return $action .' '. $add_atm; // Return the action content
            })
            ->addColumn('pending_to', function($row) {
                $AtmBanksTransaction = AtmBanksTransaction::where('transaction_number', $row->transaction_number)
                    ->where('status', 'ON GOING')
                    ->where('oc_transaction', 'NO')
                    ->orderBy('id', 'desc')
                    ->first();

                    $transaction_id = $AtmBanksTransaction->id ?? '';

                    $Approval = AtmBanksTransactionApproval::with('DataTransactionAction', 'DataUserGroup')
                        ->where('banks_transactions_id', $transaction_id)
                        ->where('status', 'Pending')
                        ->orderBy('id', 'asc')
                        ->first();

                    if($AtmBanksTransaction){
                        $TransactionActionName = optional($Approval->DataTransactionAction)->name ?? '';
                        $UserGroupName = optional($Approval->DataUserGroup)->group_name ?? '';
                    } else {
                        $TransactionActionName = '';
                        $UserGroupName = '';
                    }

                return $TransactionActionName . ' <div class="text-dark"> ' . $UserGroupName .'</div>';
            })
            ->addColumn('qr_code', function($row) use ($userGroup) {
                if (in_array($userGroup, ['Developer', 'Admin','Everfirst Admin'])) {
                    $qr_code = '<button type="button" class="btn btn-primary generate_qr_code"
                                    data-atm_id="'.$row->id.'"
                                    data-transaction_number="'.$row->transaction_number.'"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="right"
                                    title="Generate QR Code">
                                <i class="fas fa-qrcode fs-5"></i>
                                </button>';
                }
                else {
                    $qr_code = '';
                }
                return $qr_code; // Return the action content
            })
            ->addColumn('full_name', function ($row) {
                // Check if the relationships and fields exist
                $clientInfo = $row->ClientInformation ?? null;

                if ($clientInfo) {
                    $lastName = $clientInfo->last_name ?? '';
                    $firstName = $clientInfo->first_name ?? '';
                    $middleName = $clientInfo->middle_name ? ' ' . $clientInfo->middle_name . '.' : ' '; // Add period if middle_name exists
                    $suffix = $clientInfo->suffix ? ' ' . $clientInfo->suffix . '.' : ' '; // Add period if suffix exists

                    // Combine the parts into the full name
                    $fullName = "{$lastName}, {$firstName}{$middleName}{$suffix}";
                } else {
                    // Fallback if client information is missing
                    $fullName = 'N/A';
                }

                return $fullName;
            })
            ->addColumn('pension_details', function ($row) {
                $PensionNumber = $row->pension_number ?? '';
                $PensionType = $row->pension_type ?? '';

                $pension_details = "<span class='fw-bold text-primary h6 pension_number_mask'>{$PensionNumber}</span><br>
                                    <span class='fw-bold text-success'>{$PensionType}</span>";

                return $pension_details;
            })
            ->addColumn('pin_code_details', function ($row) use ($userGroup, $userDepartment){
                // Define the user groups that need access
                $authorizedUserGroups = ['Developer', 'Admin', 'Everfirst Admin',
                    'Collection Receiving Clerk', 'Collection Head',
                    'Collection Staff', 'Collection Staff / Releasing',
                    'Collection Custodian', 'Collection Supervisor', 'Checker'];

                if (in_array($userGroup, $authorizedUserGroups) || $userDepartment == 'Collection') {
                    if ($row->atm_type == 'ATM') {
                        if ($row->pin_no != NULL) {
                            $pin_code_details =
                                '<a href="#" class="text-info fs-4 view_pin_code"
                                    data-pin="' . $row->pin_no . '"
                                    data-transaction_number="' . $row->transaction_number . '"
                                    data-bank_account_no="' . $row->bank_account_no . '">
                                    <i class="fas fa-eye"></i>
                                </a>';
                        } else {
                            $pin_code_details = 'No Pin Code';
                        }
                    } else {
                        $pin_code_details = 'No Pin Code';
                    }
                } else {
                    $pin_code_details = '********';
                }

                return $pin_code_details;
            })
            ->addColumn('bank_details', function ($row) {
                $replacementCountDisplay = $row->replacement_count > 0
                    ? '<span class="text-danger fw-bold h6"> / ' . ($row->replacement_count ?? '') . '</span>'
                    : '';

                return '<span class="fw-bold h6" style="color: #5AAD5D;">' . ($row->bank_account_no ?? '') . '</span>'
                    . $replacementCountDisplay . '<br>'
                    . '<span>' . ($row->bank_name ?? '') . '</span>';
            })
            ->addColumn('bank_status', function ($row) {
                $bankStatus = $row->atm_status; // Correct PHP variable declaration
                $atmTypeClass = ''; // Variable to hold the class based on atm_type

                // Determine the text color based on atm_type
                switch ($row->atm_type) {
                    case 'ATM':
                        $atmTypeClass = 'text-primary';
                        break;
                    case 'Passbook':
                        $atmTypeClass = 'text-danger';
                        break;
                    case 'Sim Card':
                        $atmTypeClass = 'text-info';
                        break;
                    default:
                        $atmTypeClass = 'text-secondary'; // Default color if none match
                }

                return '<span class="' . $atmTypeClass . '">' . $row->atm_type . '</span><br>
                        <span class="fw-bold h6">' . $bankStatus . '</span>';
            })
            ->rawColumns(['action',
                          'pending_to',
                          'qr_code',
                          'full_name',
                          'pension_details',
                          'pin_code_details',
                          'bank_details',
                          'bank_status',
                          'aprb_no_details'])
            ->make(true);
    }

    public function CancelledLoanPage()
    {
        $userGroup = Auth::user()->UserGroup->group_name;
        $MaintenancePage = MaintenancePage::where('pages_name', 'Cancelled Loan Page')->first();

        if ($MaintenancePage->status == 'yes') {
            if (in_array($userGroup, ['Developer', 'Admin'])) {
                 return view('pages.pages_backend.atm.atm_cancelled_loan_page');
            } else {
                return view('pages.pages_validate.pages-maintenance');
            }
        } else {
             return view('pages.pages_backend.atm.atm_cancelled_loan_page');
        }
    }

    public function CancelledLoanData()
    {
        $userBranchId = Auth::user()->branch_id;
        $userGroup = Auth::user()->UserGroup->group_name;
        $userDepartment = Auth::user()->department;

        // Start the query with the necessary relationships
        $query = AtmClientBanks::with('ClientInformation', 'Branch', 'AtmBanksTransaction')
            ->where('location', 'Branch')
            ->latest('updated_at');

        // Check if the user has a valid branch_id
        if ($userBranchId !== null && $userBranchId !== 0) {
            // Filter by branch_id if it's set and valid
            $query->where('branch_id', $userBranchId);
        }

        // Get the filtered data
        $HeadOfficeData = $query->get();

        // Return the data as DataTables response
        return DataTables::of($HeadOfficeData)
            ->setRowId('id')
            ->addColumn('action', function($row) use ($userGroup) {
                $hasOngoingTransaction = false;
                $latestTransaction = null;
                $latestTransactionId = null;
                $action = ''; // Initialize a variable to hold the buttons

                if ($row->AtmBanksTransaction) {
                    // Filter for ongoing transactions
                    $ongoingTransactions = $row->AtmBanksTransaction->filter(function ($transaction) {
                        return $transaction->status === 'ON GOING';
                    });

                    // Check if there is at least one ongoing transaction
                    if ($ongoingTransactions->isNotEmpty()) {
                        $hasOngoingTransaction = true;
                    }

                    // Check for completed transactions and get the latest one
                    $completedTransactions = $row->AtmBanksTransaction->filter(function ($transaction) {
                        return $transaction->status === 'COMPLETED';
                    })->sortByDesc('id'); // Sort by id in descending order

                    if ($completedTransactions->isNotEmpty()) {
                        $latestTransaction = $completedTransactions->first();
                        $latestTransactionId = $latestTransaction->id;
                    }
                }

                // Only show the button for users in specific groups
                if (in_array($userGroup, ['Developer', 'Admin', 'Branch Head', 'Everfirst Admin'])) {
                    if ($hasOngoingTransaction)
                    {
                        // Display the spinning icon if there is any ongoing transaction
                        $action = '<i class="fas fa-spinner fa-spin fs-3 text-success"></i>';
                    }
                    else if ($latestTransaction && $latestTransaction->transaction_actions_id)
                    {
                        // Generate buttons based on `transaction_actions_id`
                        if ($latestTransaction->transaction_actions_id == 3 || $latestTransaction->transaction_actions_id == 9) {
                            $action = '<button type="button" class="btn btn-success release_transaction"
                                            data-id="'.$row->id.'"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="right"
                                            title="Releasing of Transaction">
                                            <i class="fas fa-sign-in-alt"></i>
                                        </button>';
                        }
                        else if ($latestTransaction->transaction_actions_id == 1) {
                            $action = '<button type="button" class="btn btn-warning borrow_transaction"
                                            data-id="'.$row->id.'"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="right"
                                            title="Returning of Borrow Transaction">
                                           <i class="fas fa-undo"></i>
                                        </button>';
                        }
                        else if ($latestTransaction->transaction_actions_id == 11) {
                            $action = '<button type="button" class="btn btn-primary replacement_atm_transaction"
                                            data-id="'.$row->id.'"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="right"
                                            title="Replacement of ATM / Passbook Transaction">
                                          <i class="fas fa-exchange-alt"></i>
                                        </button>';
                        }
                        else if ($latestTransaction->transaction_actions_id == 13) {
                            $action = '<button type="button" class="btn btn-danger cancelled_loan_transaction"
                                            data-id="'.$row->id.'"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="right"
                                            title="Cancelled Loan Transaction">
                                          <i class="fas fa-times-circle"></i>
                                        </button>';
                        }
                        else if ($latestTransaction->transaction_actions_id == 16) {
                            $action = '<button type="button" class="btn btn-danger release_balance_transaction"
                                            data-id="'.$row->id.'"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="right"
                                            title="Release with Outstanding Balance Transaction">
                                          <i class="fas fa-sign-in-alt"></i>
                                        </button>';
                        }
                        else {
                            $action = '';
                        }
                    }
                }

                return $action; // Return the action content
            })
            ->addColumn('pending_to', function($row) {
                $groupName = ''; // Variable to hold the group name
                $atmTransactionActionName = ''; // Variable to hold the ATM transaction action name

                if ($row->AtmBanksTransaction) {
                    // Filter for ongoing transactions and sort by id in descending order
                    $ongoingTransactions = $row->AtmBanksTransaction->filter(function ($transaction) {
                        return $transaction->status === 'ON GOING';
                    })->sortByDesc('id'); // Sort by id in descending order

                    // Get the first ongoing transaction details if it exists
                    if ($ongoingTransactions->isNotEmpty()) {
                        $firstOngoingTransaction = $ongoingTransactions->first();

                        // Get the approvals related to this transaction with status 'Pending'
                        $pendingApprovals = $firstOngoingTransaction->AtmBanksTransactionApproval->filter(function ($approval) {
                            return $approval->status === 'Pending';
                        });

                        // If there are pending approvals, get the group name from the first one
                        if ($pendingApprovals->isNotEmpty()) {
                            // Use the relationship to get the group name
                            $groupName = optional($pendingApprovals->first()->DataUserGroup)->group_name;
                        }

                        // Get the ATM transaction action name if it exists
                        if (isset($firstOngoingTransaction->transaction_actions_id)) {
                            $atmTransactionAction = DataTransactionAction::find($firstOngoingTransaction->transaction_actions_id);
                            if ($atmTransactionAction) {
                                $atmTransactionActionName = htmlspecialchars($atmTransactionAction->name);
                            }
                        }
                    }
                }

                // Prepare the output
                return $atmTransactionActionName . ' <div class="text-dark"> ' . $groupName .'</div>'; // Combine the group name and action name
            })
            ->addColumn('pin_code_details', function ($row) use ($userGroup, $userDepartment){
                // Define the user groups that need access
                $authorizedUserGroups = ['Developer', 'Admin', 'Everfirst Admin',
                    'Collection Receiving Clerk', 'Collection Head',
                    'Collection Staff', 'Collection Staff / Releasing',
                    'Collection Custodian', 'Collection Supervisor', 'Checker'];

                if (in_array($userGroup, $authorizedUserGroups) || $userDepartment == 'Collection') {
                    if ($row->atm_type == 'ATM') {
                        if ($row->pin_no != NULL) {
                            $pin_code_details =
                                '<a href="#" class="text-info fs-4 view_pin_code"
                                    data-pin="' . $row->pin_no . '"
                                    data-transaction_number="' . $row->transaction_number . '"
                                    data-bank_account_no="' . $row->bank_account_no . '">
                                    <i class="fas fa-eye"></i>
                                </a>';
                        } else {
                            $pin_code_details = 'No Pin Code';
                        }
                    } else {
                        $pin_code_details = 'No Pin Code';
                    }
                } else {
                    $pin_code_details = '********';
                }

                return $pin_code_details;
            })
            ->rawColumns(['action','pending_to','pin_code_details']) // Render the HTML in the 'action' column
            ->make(true);
    }


}
