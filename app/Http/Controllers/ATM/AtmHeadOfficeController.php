<?php

namespace App\Http\Controllers\ATM;
use Exception;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Models\DataBankLists;
use Illuminate\Support\Carbon;
use App\Models\ClientInformation;
use App\Models\DataReleaseOption;
use App\Models\System\SystemLogs;
use App\Models\ATM\AtmClientBanks;
use App\Models\DataCollectionDate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\DataPensionTypesLists;
use App\Models\DataTransactionAction;
use App\Models\System\MaintenancePage;
use App\Models\ATM\AtmBanksTransaction;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ATM\AtmBanksTransactionApproval;
use App\Models\DataBorrowOption;

class AtmHeadOfficeController extends Controller
{
    public function HeadOfficePage(){
        $userGroup = Auth::user()->UserGroup->group_name;
        $branch_id = Auth::user()->branch_id;

        $Branches = Branch::where('status', 'Active')->get();
        $DataBankLists = DataBankLists::where('status', 'Active')->get();
        $DataCollectionDate = DataCollectionDate::where('status', 'Active')->get();
        $DataTransactionAction = DataTransactionAction::where('transaction', '1')->where('status', 'Active')->get();
        $DataReleaseOption = DataReleaseOption::where('status', 'Active')->get();
        $DataPensionTypesLists = DataPensionTypesLists::where('status', 'Active')->get();
        $DataBorrowOption = DataBorrowOption::where('status', 'Active')->get();

        $MaintenancePage = MaintenancePage::where('pages_name', 'Head Office Page')->first();
        if ($MaintenancePage->status == 'yes') {
            // Allow "Developer" group to bypass maintenance mode
            if (in_array($userGroup, ['Developer', 'Admin'])) {
                return view('pages.pages_backend.atm.atm_head_office_atm_lists', compact(
                    'DataTransactionAction',
                    'DataReleaseOption',
                    'DataBankLists',
                    'DataCollectionDate',
                    'Branches',
                    'DataPensionTypesLists',
                    'userGroup',
                    'branch_id',
                    'DataBorrowOption',
                ));
            } else {
                return view('pages.pages_validate.pages-maintenance');
            }
        } else {
            return view('pages.pages_backend.atm.atm_head_office_atm_lists', compact(
                'DataTransactionAction',
                'DataReleaseOption',
                'DataBankLists',
                'DataCollectionDate',
                'Branches',
                'DataPensionTypesLists',
                'userGroup',
                'branch_id',
                'DataBorrowOption',
            ));
        }
    }

    public function HeadOfficeData(Request $request){
        $userBranchId = Auth::user()->branch_id;
        $userGroup = Auth::user()->UserGroup->group_name;
        $userDepartment = Auth::user()->department;

        // Start the query with the necessary relationships
        $query = AtmClientBanks::with('ClientInformation', 'Branch')
            ->where('location', 'Head Office')
            ->where('status', '1')
            ->whereNull('deleted_at');

            if ($userBranchId) {
                // Case 1: User has a branch_id (non-admin users)
                $query->where('branch_id', $userBranchId);
            } else if (in_array($userGroup, ['Developer', 'Admin'])) {
                // Case 2: Developer or Admin
                if ($request->filled('branch_id')) {
                    if ($request->branch_id !== 'all') {
                        $query->where('branch_id', $request->branch_id);
                    }
                    // else do nothing, display all
                } else {
                    // If no branch_id is sent at all, default to branch_id 4
                    $query->where('branch_id', 4);
                }
            } else {
                // Case 3: Other users without branch_id
                if ($request->filled('branch_id') && $request->branch_id != '0' && $request->branch_id !== 'all') {
                    $query->where('branch_id', $request->branch_id);
                } else {
                    $query->where('branch_id', 4);
                }
            }

        // Get the filtered data
        $HeadOfficeData = $query->get();

        return DataTables::of($HeadOfficeData)
            ->setRowId('id')
            ->addColumn('action', function($row) use ($userGroup) {
                $action = ''; // Initialize a variable to hold the buttons

                $hasOngoingTransaction = AtmBanksTransaction::where('transaction_number', $row->transaction_number)
                    ->where('status','ON GOING')
                    ->where('oc_transaction','NO')
                    ->orderByDesc('id') // Corrected sorting method
                    ->first();

                // Only show the button for users in specific groups
                if (in_array($userGroup, ['Developer', 'Admin', 'Branch Head', 'Everfirst Admin'])) {
                    if ($hasOngoingTransaction) {
                        // Show spinning gear icon if there are ongoing transactions
                        return '<button type="button" class="btn text-success me-1">
                                    <i class="fas fa-spinner fa-spin fs-3"></i>
                                </button>';
                    } else {
                        // Add buttons for creating a transaction and adding ATM transaction
                        $action .= '<a href="#" class="btn btn-primary createTransaction me-2 mb-2"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Create Transaction"
                                            data-id="' . $row->id . '
                                            data-client_id="' . $row->ClientInformation->id . '">
                                        <i class="fas fa-plus-circle"></i>
                                     </a>';
                    }

                    $action .= '<a href="#" class="btn btn-success addAtmTransaction me-2 mb-2"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        title="Add ATM / PB / Simcard"
                                        data-id="' . $row->id . '">
                                    <i class="fas fa-credit-card"></i>
                                </a>';
                }

                // Add buttons for users in Collection Staff and others
                if (in_array($userGroup, ['Collection Staff', 'Developer', 'Admin', 'Everfirst Admin'])) {
                    // Show the button to transfer branch transaction and edit information
                    $action .= '<a href="#" class="btn btn-danger transferBranchTransaction me-2 mb-2"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="Transfer to Other Branch"
                                    data-id="' . $row->id . '">
                                    <i class="fas fa-redo-alt"></i>
                                 </a>

                                 <a href="#" class="btn btn-warning EditInformationTransaction me-2 mb-2"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="Edit ATM / Client Information"
                                    data-id="' . $row->id . '">
                                    <i class="fas fa-edit"></i>
                                 </a>';
                } else {
                     $action .= '';
                }
                return $action; // Return all the accumulated buttons
            })
            ->addColumn('pending_to', function($row) {
                $AtmBanksTransaction = AtmBanksTransaction::where('transaction_number', $row->transaction_number)
                    ->where('status', 'ON GOING')
                    ->where('oc_transaction', 'NO')
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
            ->addColumn('passbook_for_collection', function($row) use ($userGroup) {
                $passbook_for_collection = ''; // Initialize a variable to hold the buttons

                // Add buttons for users in Collection Staff and others
                if (in_array($userGroup, ['Developer', 'Admin', 'Everfirst Admin','Branch Head', 'Collection Staff'])) {
                    if($row->atm_type === 'Passbook' && $row->ClientInformation->passbook_for_collection === 'no') {
                        $passbook_for_collection .= '<a href="#" class="btn btn-info passbookForCollection me-2 mb-2"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="Passbook For Collection"
                                                            data-id="' . $row->id . '">
                                                            <i class="fas fa-book"></i>
                                                    </a>';
                    } else {
                        $passbook_for_collection = '';
                    }
                } else {
                    $passbook_for_collection = '';
                }
                return $passbook_for_collection; // Return all the accumulated buttons
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

                $pension_details = "<span class='fw-bold text-primary'>{$PensionNumber}</span><br>
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
                                    data-atm_id="' . $row->id . '"
                                    data-transaction_number="' . $row->transaction_number . '"
                                    data-bank_account_no="' . $row->bank_account_no . '">
                                    <i class="fas fa-eye"></i>
                                </a>';
                        } else {
                            $pin_code_details = 'No Pin Code';
                        }
                    } else {
                        $pin_code_details = '';
                    }
                } else {
                    $pin_code_details = '********';
                }

                return $pin_code_details;
            })
            ->addColumn('bank_details', function ($row) {
                $replacementCountDisplay = $row->replacement_count > 0
                    ? '<span class="text-danger fw-bold"> / ' . ($row->replacement_count ?? '') . '</span>'
                    : '';

                return '<span class="fw-bold" style="color: #5AAD5D;">' . ($row->bank_account_no ?? '') . '</span>'
                    . $replacementCountDisplay . '<br>'
                    . '<span>' . ($row->bank_name ?? '') . '</span>';
            })
            ->addColumn('branch_location', function ($row){
                $branch_location = $row->Branch->branch_location ?? '';
                return $branch_location;
            })
            ->addColumn('atm_status', function ($row) {
                // Define the default ATM type class
                $atmTypeClass = 'text-secondary'; // Default if no match

                // Assign classes based on ATM type
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
                }

                // Get ATM Status
                $BankStatus = $row->atm_status ?? '';

                return '<span class="' . $atmTypeClass . '">' . ($row->atm_type ?? '') . '</span><br>
                        <span class="fw-bold">' . $BankStatus . '</span>';
            })
            ->rawColumns(['action',
                          'pending_to',
                          'passbook_for_collection',
                          'full_name',
                          'qr_code',
                          'pension_details',
                          'pin_code_details',
                          'branch_location',
                          'atm_status',
                          'bank_details']) // Render HTML in both the action and pending_to columns
            ->make(true);
    }

    public function PassbookForCollectionSetup(Request $request){
        $atm_id = $request->atm_id;

        $AtmClientBanks = AtmClientBanks::with('ClientInformation', 'Branch')->findOrFail($atm_id);
        $AtmClientBanks->update([
            'updated_at' => Carbon::now(),
        ]);
        $ClientInformationId = $AtmClientBanks->client_information_id;

        $ClientInformation = ClientInformation::findOrFail($ClientInformationId);
        $ClientInformation->update([
            'passbook_for_collection' => 'yes',
            'updated_at' => Carbon::now(),
        ]);

        $BranchDetails = $AtmClientBanks->Branch->branch_location ?? '';
        $ClientDetails = trim(
            ($ClientInformation->last_name ?? '') . ' ' .
            ($ClientInformation->first_name ?? '') . ' ' .
            ($ClientInformation->middle_name ?? '') . ' ' .
            ($ClientInformation->suffix ?? '')
        );

        // Create System Logs used for Auditing of Logs
        SystemLogs::create([
            'module' => 'ATM / PB Monitoring',
            'action' => 'Create',
            'title' => 'Add to Setup for Passbook For Collection',
            'description_logs' => [ // Convert array to JSON
                'new_details' => [
                    'Client Details' => $ClientDetails,
                    'Branch' => $BranchDetails,
                    'Transaction Number' => $AtmClientBanks->transaction_number ?? '',
                ],
            ],
            'employee_id' => Auth::user()->employee_id,
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
            'company_id' => Auth::user()->company_id,
        ]);

        // Return a successful response
        return response()->json([
            'status' => 'success',
            'message' => 'Already Setup for Passbook for Collection'
        ]);
    }

    public function SafekeepPage(){
        $userGroup = Auth::user()->UserGroup->group_name;

        $MaintenancePage = MaintenancePage::where('pages_name', 'Safekeep Page')->first();

        if ($MaintenancePage->status == 'yes') {
            if (in_array($userGroup, ['Developer', 'Admin'])) {
                return view('pages.pages_backend.atm.atm_safekeep_lists');
            } else {
                return view('pages.pages_validate.pages-maintenance');
            }
        } else {
            return view('pages.pages_backend.atm.atm_safekeep_lists');
        }
    }

    public function SafekeepData(){
        $userBranchId = Auth::user()->branch_id;
        $userGroup = Auth::user()->UserGroup->group_name;
        $userDepartment = Auth::user()->department;

        // Start the query with the necessary relationships
        $query = AtmClientBanks::with('ClientInformation', 'Branch', 'AtmBanksTransaction')
            ->where('location', 'Head Office')
            ->where('status', '6');

        // Check if the user has a valid branch_id
        if ($userBranchId !== null && $userBranchId !== 0) {
            // Filter by branch_id if it's set and valid
            $query->where('branch_id', $userBranchId);
        }

        // Get the filtered data
        $HeadOfficeData = $query->get();

        return DataTables::of($HeadOfficeData)
            ->setRowId('id')
            ->addColumn('action', function($row) use ($userGroup) {
                $action = '';

                $hasOngoingTransaction = AtmBanksTransaction::where('transaction_number', $row->transaction_number)
                    ->where('status','ON GOING')
                    ->where('oc_transaction','NO')
                    ->orderByDesc('id') // Corrected sorting method
                    ->first();


                $CompletedTransaction = AtmBanksTransaction::where('transaction_number', $row->transaction_number)
                    ->where('status','COMPLETED')
                    ->where('oc_transaction','NO')
                    ->orderBy('id', 'desc') // Corrected sorting method
                    ->first();

                    $aprb_no_details = $CompletedTransaction->aprb_no ?? '';

                // Only show the button for users in specific groups
                if (in_array($userGroup, ['Developer', 'Admin', 'Branch Head', 'Everfirst Admin'])) {
                    if ($hasOngoingTransaction) {
                        // Show spinning gear icon if there are ongoing transactions
                        return '<i class="fas fa-spinner fa-spin fs-3 text-success"></i>';
                    } else {
                        // Add buttons for creating a transaction and adding ATM transaction
                        $action .= '<a href="#" class="btn btn-primary pullOutTransaction me-2 mb-2"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Pullout ATM / PB / Simcard"
                                            data-aprb_no="' . $aprb_no_details . '"
                                            data-id="' . $row->id . '">
                                         <i class="fas fa-share-square"></i>
                                    </a>';
                    }
                }
                return $action; // Return all the accumulated buttons
            })
            ->addColumn('pending_to', function($row) {
                $AtmBanksTransaction = AtmBanksTransaction::where('transaction_number', $row->transaction_number)
                    ->where('status', 'ON GOING')
                    ->where('oc_transaction', 'NO')
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

                $pension_details = "<span class='fw-bold text-primary'>{$PensionNumber}</span><br>
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
                                    data-atm_id="' . $row->id . '"
                                    data-transaction_number="' . $row->transaction_number . '"
                                    data-bank_account_no="' . $row->bank_account_no . '">
                                    <i class="fas fa-eye"></i>
                                </a>';
                        } else {
                            $pin_code_details = 'No Pin Code';
                        }
                    } else {
                        $pin_code_details = '';
                    }
                } else {
                    $pin_code_details = '********';
                }

                return $pin_code_details;
            })
            ->addColumn('bank_details', function ($row) {
                $replacementCountDisplay = $row->replacement_count > 0
                    ? '<span class="text-danger fw-bold"> / ' . ($row->replacement_count ?? '') . '</span>'
                    : '';

                return '<span class="fw-bold" style="color: #5AAD5D;">' . ($row->bank_account_no ?? '') . '</span>'
                    . $replacementCountDisplay . '<br>'
                    . '<span>' . ($row->bank_name ?? '') . '</span>';
            })
            ->addColumn('branch_location', function ($row){
                $branch_location = $row->Branch->branch_location ?? '';
                return $branch_location;
            })
            ->addColumn('atm_status', function ($row) {
                // Define the default ATM type class
                $atmTypeClass = 'text-secondary'; // Default if no match

                // Assign classes based on ATM type
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
                }

                // Get ATM Status
                $BankStatus = $row->atm_status ?? '';

                return '<span class="' . $atmTypeClass . '">' . ($row->atm_type ?? '') . '</span><br>
                        <span class="fw-bold">' . $BankStatus . '</span>';
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
                        <span class="fw-bold">' . $bankStatus . '</span>';
            })
            ->rawColumns(['action',
                          'pending_to',
                          'full_name',
                          'qr_code',
                          'pension_details',
                          'pin_code_details',
                          'branch_location',
                          'atm_status',
                          'bank_details',
                          'bank_status']) // Render HTML in both the action and pending_to columns
            ->make(true);
    }

    public function ReleasedPage(){
        $userGroup = Auth::user()->UserGroup->group_name;
        $branch_id = Auth::user()->branch_id;
        $Branches = Branch::where('status', 'Active')->get();
        $DataBankLists = DataBankLists::where('status','Active')->get();
        $DataCollectionDate = DataCollectionDate::where('status', 'Active')->get();
        $MaintenancePage = MaintenancePage::where('pages_name', 'Released Page')->first();

        if ($MaintenancePage->status == 'yes') {
            if (in_array($userGroup, ['Developer', 'Admin'])) {
                return view('pages.pages_backend.atm.atm_released_lists', compact('DataBankLists','Branches','branch_id','userGroup','DataCollectionDate'));
            } else {
                return view('pages.pages_validate.pages-maintenance');
            }
        } else {
            return view('pages.pages_backend.atm.atm_released_lists', compact('DataBankLists','Branches','branch_id','userGroup','DataCollectionDate'));
        }
    }

    public function ReleasedData(Request $request){
        $userBranchId = Auth::user()->branch_id;
        $userGroup = Auth::user()->UserGroup->group_name;
        $userDepartment = Auth::user()->department;

        $query = AtmClientBanks::with('ClientInformation', 'Branch', 'AtmBanksTransaction')
            ->where('location','Released')
            ->whereIn('status', ['0', '2', '3', '4', '5','7']) // Use whereIn for multiple values
            ->latest('updated_at');

            if ($userBranchId) {
                // Case 1: User has a branch_id
                $query->where('branch_id', $userBranchId);
            } else if (in_array($userGroup, ['Developer', 'Admin'])) {
                // Case 2: Developer or Admin
                if ($request->filled('branch_id') && $request->branch_id != 0) {
                    $query->where('branch_id', $request->branch_id);
                }
            } else {
                // If a specific branch_id is selected, override default
                if ($request->filled('branch_id') && $request->branch_id != 0) {
                    $query->where('branch_id', $request->branch_id);
                } else {
                    $query->where('branch_id', 5);
                }
            }
        // Get the filtered data
        $HeadOfficeData = $query->get();

        return DataTables::of($HeadOfficeData)
            ->setRowId('id')
            ->addColumn('action', function($row) use ($userGroup ,$userDepartment) {
                $action = ''; // Initialize a variable to hold the buttons

                if ($row->AtmBanksTransaction) {
                    $hasOngoingTransaction = AtmBanksTransaction::where('transaction_number', $row->transaction_number)
                        ->where('status','ON GOING')
                        ->where('oc_transaction','NO')
                        ->orderBy('id', 'desc') // Corrected sorting method
                        ->first();
                } else {
                    $action .= 'No Transaction History';
                }

                // Only show the button for users in specific groups
                if (in_array($userGroup, ['Developer', 'Admin', 'Branch Head', 'Everfirst Admin'])) {
                    if ($hasOngoingTransaction) {
                        $action .= '<button type="button" class="btn fs-3 text-success me-1">
                                        <i class="fas fa-spinner fa-spin"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="On Going Transaction">
                                        </i>
                                    </button>';
                    } else {
                        $LastTransactionHistory = AtmBanksTransaction::where('transaction_number', $row->transaction_number)
                            ->where('status','COMPLETED')
                            ->where('oc_transaction','NO')
                            ->orderBy('id', 'desc') // Corrected sorting method
                            ->first();

                        if($row->status == 0){
                            if($LastTransactionHistory && // To Make Sure Last Transaction Are 3 Release or 8 Yellow Paper
                                   ( $LastTransactionHistory->transaction_actions_id == '3' ||
                                     $LastTransactionHistory->transaction_actions_id == '8' ) ){
                                $action .= '<a href="#" class="btn btn-success returnClientTransaction me-2 mb-2"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="Return Client / Balik Loob"
                                                data-id="' . $row->id . '">
                                                <i class="fas fa-sign-in-alt fa-rotate-180"></i>
                                            </a>';
                            } else {
                                $action .= '<span class="text-danger">Last Transaction Error !</span>';
                            }
                        } else if($row->status == 3){
                            $action .= '<span class="text-danger">Return Client Using Different Card</span>';
                        } else if($row->status == 4){
                            $action .= '<span class="text-danger">Return Client Using Same Card</span>';
                        } else if($row->status == 5){
                            $action .= '<span class="text-danger">Did Not Return By Bank</span>';
                        } else {
                            $action .= '<span class="text-danger">Something Wrong !</span>';
                        }
                    }
                }

                // Add buttons for users in Collection Staff and others
                if (in_array($userGroup, ['Collection Staff', 'Developer', 'Admin', 'Everfirst Admin']) &&
                   ($row->status == '0') || $userDepartment === 'Collection') {
                        $action .= '<a href="#" class="btn btn-danger transferBranchTransaction me-2 mb-2"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Transfer to Other Branch"
                                            data-id="' . $row->id . '">
                                        <i class="fas fa-redo-alt"></i>
                                    </a>';
                } else {
                        $action .= '';
                }
                return $action; // Return all the accumulated buttons
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

                // Ensure $Approval is not null before accessing relationships

                    if($AtmBanksTransaction){
                        $TransactionActionName = $Approval?->DataTransactionAction?->name ?? null;
                        $UserGroupName = $Approval?->DataUserGroup?->group_name ?? null;
                    } else {
                        $TransactionActionName = '';
                        $UserGroupName = '';
                    }

                return $TransactionActionName . ' <div class="text-dark"> ' . $UserGroupName .'</div>';
            })
            ->addColumn('full_name', function ($row) {
                // Check if the relationships and fields exist
                $clientInfo = $row->ClientInformation ?? null;

                if ($clientInfo) {
                    $lastName = $clientInfo->last_name ?? '';
                    $firstName = $clientInfo->first_name ?? '';
                    $middleName = $clientInfo->middle_name ? ' ' . $clientInfo->middle_name : ''; // Add space if middle_name exists
                    $suffix = $clientInfo->suffix ? ', ' . $clientInfo->suffix : ''; // Add comma if suffix exists

                    // Combine the parts into the full name
                    $fullName = "{$lastName}, {$firstName}{$middleName}{$suffix}";
                } else {
                    // Fallback if client information is missing
                    $fullName = '';
                }

                return $fullName;
            })
            ->addColumn('pension_details', function ($row) {
                    $PensionNumber = $row->pension_number ?? '';
                    $PensionType = $row->pension_type ?? '';

                    $pension_details = "<span class='fw-bold text-primary pension_number_mask_display'>{$PensionNumber}</span><br>
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
                                    data-atm_id="' . $row->id . '"
                                    data-transaction_number="' . $row->transaction_number . '"
                                    data-bank_account_no="' . $row->bank_account_no . '">
                                    <i class="fas fa-eye"></i>
                                </a>';
                        } else {
                            $pin_code_details = 'No Pin Code';
                        }
                    } else {
                        $pin_code_details = '';
                    }
                } else {
                    $pin_code_details = '********';
                }

                return $pin_code_details;
            })
            ->addColumn('bank_details', function ($row) {
                $replacementCountDisplay = $row->replacement_count > 0
                    ? '<span class="text-danger fw-bold"> / ' . ($row->replacement_count ?? '') . '</span>'
                    : '';

                return '<span class="fw-bold" style="color: #5AAD5D;">' . ($row->bank_account_no ?? '') . '</span>'
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
                        <span class="fw-bold">' . $bankStatus . '</span>';
            })
            ->rawColumns(['action',
                          'pending_to',
                          'full_name',
                          'pension_details',
                          'pin_code_details',
                          'bank_details',
                          'bank_status']) // Render HTML in both the action and pending_to columns
            ->make(true);
    }
}
