<?php

namespace App\Http\Controllers\ATM;
use App\Models\Branch;

use App\Models\System\SystemLogs;
use App\Models\System\MaintenancePage;
use App\Models\DataTransactionSequence;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\ClientInformation;
use App\Models\DataCollectionDate;
use App\Models\AtmTransactionSequence;
use App\Models\DataBankLists;

use App\Models\ATM\AtmClientBanks;
use App\Models\ATM\AtmBanksTransaction;
use App\Models\ATM\AtmTransactionBalanceLogs;
use App\Models\ATM\AtmBanksTransactionApproval;

use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
class ClientContoller extends Controller
{
    public function client_page(){
        $userGroup = Auth::user()->UserGroup->group_name;
        $branch_id = Auth::user()->branch_id;
        $branches = Branch::where('status', 'Active')->get();

        $DataCollectionDates = DataCollectionDate::where('status', 'Active')->get();
        $DataBankLists = DataBankLists::where('status', 'Active')->get();

        $MaintenancePage = MaintenancePage::where('pages_name', 'Client Lists Page')->first();

        if ($MaintenancePage->status == 'yes') {
            if (in_array($userGroup, ['Developer', 'Admin'])) {
                return view('pages.pages_backend.atm.atm_clients_page', compact('branches','userGroup','DataCollectionDates','DataBankLists'));
            } else {
                return view('pages.pages_validate.pages-maintenance');
            }
        } else {
            return view('pages.pages_backend.atm.atm_clients_page', compact('branches','userGroup','DataCollectionDates','DataBankLists','branch_id'));
        }
    }

    public function client_data(Request $request){
        $userBranchId = Auth::user()->branch_id;
        $userGroup = Auth::user()->UserGroup->group_name;
        $userDepartment = Auth::user()->department;

        // Start the query with the necessary relationships
        $query = AtmClientBanks::with('Branch', 'ClientInformation')
            ->latest('updated_at');

        if ($request->filled('pension_number')) {
            // Sanitize pension number (remove non-numeric characters)
            $pension_number_get = preg_replace('/[^0-9]/', '', $request->pension_number);

            // Find existing pension number (even if in another branch)
            $existingPension = AtmClientBanks::where('pension_number', $pension_number_get)->first();

            if ($existingPension) {
                if (!$userBranchId) {
                    return response()->json(['error' => 'Pension number already exists.']);

                    $query->where('pension_number', $pension_number_get);
                }

                // Check if pension number belongs to the same branch
                if ($existingPension->branch_id == $userBranchId) {
                    return response()->json(['error' => 'Pension number already exists in the same branch.']);
                } else {
                    // Instead of returning early, apply the filter and send the response with `201`
                    $query->where('pension_number', $pension_number_get);
                    return response()->json([
                        'success' => 'Pension number already exists in another branch.',
                        'pension_number' => $pension_number_get // Send back for DataTable filtering
                    ], 201);
                }
            }
            return response()->json(['success' => 'Pension number is valid.'], 200);
        }

        // Get filtered data based on pension_number (if provided)
        $branchData = $query->get();

        // Return the data as DataTables response
        return DataTables::of($branchData)
            ->setRowId('id')
            ->addColumn('action', function($row) use ($userGroup) {
                $action = '';
                // Add buttons for users in Collection Staff and others
                if (in_array($userGroup, ['Collection Staff', 'Developer', 'Admin', 'Everfirst Admin'])) {
                    // Show the button to transfer branch transaction and edit information
                    $action .= '<a href="#" class="btn btn-success fw-bold add_more_atm"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="Add More ATM / PB"
                                    data-id="' . $row->id  . '">
                                    <i class="fas fa-credit-card"></i>
                                 </a>';
                } else {
                     $action .= '';
                }
                return $action; // Return all the accumulated buttons
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
            ->addColumn('pension_details', function ($row) {
                $PensionNumber = $row->pension_number ?? '';
                $PensionType = $row->pension_type ?? '';

                $pension_details = "<span class='fw-bold text-primary'>{$PensionNumber}</span><br>
                                    <span class='fw-bold text-success'>{$PensionType}</span>";

                return $pension_details;
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
            ->addColumn('branch_location', function($row) use ($userGroup) {
                $branch_location = $row->Branch->branch_location;
                return $branch_location; // Return all the accumulated buttons
            })
            ->rawColumns(['action',
                          'full_name',
                          'pin_code_details',
                          'branch_location',
                          'pension_details',
                          'bank_details',
                          'bank_status'])
            ->make(true);
    }

    public function clientCreate(Request $request){

            $existingPensionNumber = AtmClientBanks::where('pension_number', $request->pension_number)
                        ->where('pension_type', $request->pension_type)
                        ->exists();

            // If it exists, return a response with a message
            if ($existingPensionNumber) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Duplicate Pension Number Found'
                ]);
            } else {
                // Validate First Existing Bank Account No Start
                    if (is_array($request->atm_number)) {
                        // Clear hyphens from each element in the array
                        $atmNumbers = array_map(function($number) {
                            return str_replace('-', '', $number); // Remove hyphens
                        }, $request->atm_number);

                        // Use Eloquent to check for existing bank_account_no
                        $existingAccounts = AtmClientBanks::whereIn('bank_account_no', $atmNumbers)
                            ->whereNotNull('bank_account_no')
                            ->get(['bank_account_no']); // Get only the bank_account_no field
                    } else {
                        // Clear hyphen from the single atm_number
                        $atmNumber = str_replace('-', '', $request->atm_number);

                        $existingAccounts = AtmClientBanks::where('bank_account_no', $atmNumber)
                            ->whereNotNull('bank_account_no')
                            ->get(['bank_account_no']); // Get only the bank_account_no field
                    }

                    // Check if any existing accounts were found
                    if ($existingAccounts->isNotEmpty())
                    {
                        // Extract existing bank_account_no values for display
                        $existingNumbers = $existingAccounts->pluck('bank_account_no')->toArray();
                        $existingNumbersString = implode(', ', $existingNumbers); // Convert to string

                        // Prepare the message to include existing numbers
                        $message = "Duplicate ATM / Passbook / Sim Number $existingNumbersString,";
                        return response()->json([
                            'status' => 'error',
                            'message' => $message
                        ]);
                    }
                // Validate First Existing Bank Account No End

                if ($request->branch_id !== null) {
                    $branch_id = $request->branch_id; // Use the branch_id from the request if it's not null
                } else {
                    $branch_id = Auth::user()->branch_id; // Fall back to the authenticated user's branch_id
                }

                // Get the branch abbreviation
                $BranchGet = Branch::where('id', $branch_id)->first();
                $branch_abbreviation = $BranchGet->branch_abbreviation;
                $branch_location = $BranchGet->branch_location;

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

                $pension_number = str_replace('-', '', $request->pension_number);

                $ClientInformation = ClientInformation::create([
                    'first_name' => $request->first_name ?? NULL,
                    'middle_name' => $request->middle_name ?? NULL,
                    'last_name' => $request->last_name ?? NULL,
                    'suffix' => $request->suffix ?? NULL,
                    'birth_date' => $request->birth_date ?? NULL,
                    'created_at' => Carbon::now(),
                ]);

                if (is_array($request->atm_type) && !empty($request->atm_type)) {
                    foreach ($request->atm_type as $key => $value){
                        $transactionCounter = $lastadded + $key + 1;
                        $TransactionNumber = $branch_abbreviation . '-' . date('Y') . '-' . str_pad($transactionCounter, 5, '0', STR_PAD_LEFT);

                        $BankAccountNo = str_replace('-', '', $request->atm_number[$key]);

                        $expirationDate = $request->expiration_date[$key];

                        if ($expirationDate) {
                            // Append '-01' only if the expiration date has a valid month and year format (e.g., '2024-10')
                            $expirationDate .= '-01';
                        } else {
                            $expirationDate = null; // or handle the case when expiration_date is not provided
                        }

                        // dd($TransactionNumber);

                        $AtmClientBanks = AtmClientBanks::create([
                            'pension_number' => $pension_number ?? NULL,
                            'pension_type' => $request->pension_type ?? NULL,
                            'account_type' => $request->account_type ?? NULL,
                            'client_information_id' => $ClientInformation->id,
                            'transaction_number' => $TransactionNumber,
                            'branch_id' => $branch_id ?? NULL,
                            'atm_type' => $value,
                            'atm_status' => $request->atm_status[$key] ?? NULL,
                            'location' => 'Branch',
                            'bank_account_no' => $BankAccountNo ?? NULL,
                            'bank_name' => $request->bank_id[$key] ?? NULL,
                            'pin_no' => $request->pin_code[$key] ?? NULL,
                            'expiration_date' => $expirationDate,
                            'collection_date' => $request->collection_date ?? NULL,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);

                        $AtmBanksTransaction = AtmBanksTransaction::create([
                            'client_banks_id' => $AtmClientBanks->id,
                            'transaction_actions_id' => 5,
                            'request_by_employee_id' => Auth::user()->employee_id,
                            'transaction_number' => $TransactionNumber,
                            'atm_type' => $value,
                            'bank_account_no' => $BankAccountNo ?? NULL,
                            'branch_id' => $branch_id,
                            'aprb_no' => NULL,
                            'status' => 'ON GOING',
                            'reason' => 'New Client',
                            'reason_remarks' => NULL,
                            'yellow_copy' => NULL,
                            'created_at' => Carbon::now(),
                        ]);

                        // Sequence
                            $DataTransactionSequence = DataTransactionSequence::where('transaction_actions_id', 5)
                                ->orderBy('sequence_no')
                                ->get();


                            foreach ($DataTransactionSequence as $transactionSequence) {
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
                        // Sequence

                        $balance = floatval(preg_replace('/[^\d]/', '', $request->atm_balance[$key]));

                        AtmTransactionBalanceLogs::create([
                            'banks_transactions_id' => $AtmBanksTransaction->id,
                            'check_by_employee_id' => Auth::user()->employee_id,
                            'balance' => $balance,
                            'remarks' => $request->remarks[$key] ?? NULL,
                            'created_at' => Carbon::now(),
                        ]);

                        // System Logs
                            $FirstName = $request->first_name ?? '';
                            $MiddleName = $request->middle_name ?? '';
                            $LastName = $request->last_name ?? '';
                            $Suffix = $request->suffix ?? '';

                            $ClientDetails = $LastName .', '. $FirstName . $MiddleName . $Suffix;

                            SystemLogs::create([
                                'module' => 'ATM / PB Monitoring',
                                'action' => 'Create',
                                'title' => 'Create New Client',
                                'description_logs' => [
                                    'new_details' => [
                                        'Transaction' => 'Add New Client',
                                        'Client Details' => $ClientDetails,
                                        'Pension Number' => $pension_number ?? NULL,
                                        'Pension Type' => $request->pension_type ?? NULL,
                                        'Account Type' => $request->account_type ?? NULL,
                                        'Birthdate' => $request->birth_date ?? NULL,
                                        'Transaction Number' => $TransactionNumber,
                                        'Branch' => $branch_location ?? '',
                                        'ATM Type' => $value,
                                        'ATM Status' => 'New',
                                        'Location' => 'Branch',
                                        'Card No' => $BankAccountNo ?? NULL,
                                        'Bank Name' => $request->bank_id[$key] ?? NULL,
                                        'Pin No.' => $request->pin_code[$key] ?? NULL,
                                        'Expiration Date' => $expirationDate,
                                        'Collection Date' => $request->collection_date ?? NULL,
                                        'Balance' => $request->atm_balance[$key],
                                        'Remarks' => $request->remarks[$key] ?? NULL,
                                    ],
                                ],
                                'employee_id' => Auth::user()->employee_id,
                                'ip_address' => $request->ip(),
                                'created_at' => Carbon::now(),
                                'company_id' => Auth::user()->company_id,
                            ]);
                        // System Logs

                    }
                }

            }


        return response()->json([
            'status' => 'success',
            'message' => 'New Client Created successfully!'  // Changed message to reflect update action
        ]);
    }

    public function clientGetBanks($id){
        $AtmClientBanks = AtmClientBanks::with('Branch','ClientInformation')->findOrFail($id);
        return response()->json($AtmClientBanks);
    }

    public function PensionNumberValidate(Request $request){
        // Remove any non-numeric characters (like hyphens) from the pension number
        $pension_number_get = preg_replace('/[^0-9]/', '', $request->pension_number);

        // Get the authenticated user's branch_id
        $user_branch_id =  Auth::user()->branch_id;

        // Query to find if the pension number exists in the client information
        $clientInfo = AtmClientBanks::with('Branch')
                        ->where('pension_number', $pension_number_get)
                        ->first();

        if ($clientInfo) {
            // If the user has no branch_id (user is not assigned to any specific branch)
            if (is_null($user_branch_id)) {
                return response()->json(['error' => 'Pension number already exists.']);
            }

            // Check if the found pension number belongs to the same branch as the user
            if ($clientInfo->branch_id == $user_branch_id) {
                return response()->json(['error' => 'Pension number already exists in the same branch.']);
            } else {
                // Pension number exists but in a different branch
                return response()->json(['error' => 'Pension number already exists in another branch.']);
            }
        }

        // If the pension number does not exist, allow further processing
        return response()->json(['success' => 'Pension number is valid.'], 200);
    }

    public function addMoreAtm(Request $request){
        $atm_id  = $request->atm_id;

        // Fetch Data From AtmClientBanks
            $AtmClientBanks = AtmClientBanks::with('ClientInformation','Branch')->findOrFail($atm_id);

            $client_information_id = $AtmClientBanks->client_information_id;
            $branch_id = $AtmClientBanks->branch_id;
            $OldPensionNumber = $AtmClientBanks->pension_number;
            $BranchDetails = $AtmClientBanks->Branch->branch_location ?? '';
        // Fetch Data From AtmClientBanks

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
                    $TransactionNumber = $branch_abbreviation . '-' . date('Y') . '-' . str_pad($transactionCounter, 5, '0', STR_PAD_LEFT);
                // Create Transaction Number

                // Validate Pension Number and Pension Type Already Exists
                    $existingPensionNoAndType = null;
                    $NewPensionNumber = str_replace('-', '', $request->pension_number);
                    $existingPensionNoAndType = AtmClientBanks::where('pension_number', $NewPensionNumber)
                        ->where('pension_type', $request->pension_type)
                        ->first();

                    if ($request->pension_no_select === 'no' && $existingPensionNoAndType) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Pension Number and Pension Type Already Exist'
                        ]);
                    }

                    $InsertPensionNumber = ($request->pension_no_select === 'no') ? $NewPensionNumber : $OldPensionNumber;
                // Validate Pension Number Already Exists

                $expirationDate = $request->expiration_date;

                if ($expirationDate) {
                    $expirationDate .= '-01';
                } else {
                    $expirationDate = null;
                }

                AtmClientBanks::create([
                    'client_information_id' => $client_information_id,
                    'pension_type' => $request->pension_type ?? '',
                    'pension_number' => $InsertPensionNumber,
                    'account_type' => $request->account_type ?? '',
                    'transaction_number' => $TransactionNumber,
                    'branch_id' => $branch_id ?? NULL,
                    'atm_type' => $request->atm_type ?? NULL,
                    'atm_status' => $request->atm_status ?? NULL,
                    'cash_box_no' => $request->cash_box_no ?? NULL,
                    'location' => 'Head Office',
                    'bank_account_no' => $BankAccountNo ?? NULL,
                    'bank_name' => $request->bank_name ?? NULL,
                    'pin_no' => $request->pin_code ?? NULL,
                    'expiration_date' => $expirationDate,
                    'collection_date' => $request->collection_date ?? NULL,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                // Create System Logs used for Auditing of Logs
                $ClientDetails = trim(
                    ($request->last_name ?? '') . ' ' .
                    ($request->first_name ?? '') . ' ' .
                    ($request->middle_name ?? '') . ' ' .
                    ($request->suffix ?? '')
                );

                SystemLogs::create([
                    'module' => 'ATM / PB Monitoring',
                    'action' => 'Create',
                    'title' => 'Create Add ATM Transaction',
                    'description_logs' => [ // Convert array to JSON
                        'new_details' => [
                            'Transaction' =>  'Add New ' . $request->atm_type ?? '',
                            'Remarks' => $remarks ?? '',
                            'Client Details' => $ClientDetails,
                            'Pension No.' => $InsertPensionNumber,
                            'Pension Type' => $request->pension_type,
                            'Account Type' => $request->account_type ?? '',
                            'Branch' => $BranchDetails,
                            'Transaction Number' => $TransactionNumber ?? '',
                            'Card Type' => $request->atm_type ?? '',
                            'ATM Status' => $request->atm_status ?? '',
                            'Location' => 'Branch',
                            'Bank Account No.' => $BankAccountNo ?? '',
                            'Bank Name' => $request->bank_name ?? '',
                            'PIN No.' => $request->pin_code ?? '',
                            'Expiration Date' => $expirationDate,
                            'Collection Date' => $request->collection_date ?? '',
                        ],
                    ],
                    'employee_id' => Auth::user()->employee_id,
                    'ip_address' => $request->ip(),
                    'created_at' => Carbon::now(),
                    'company_id' => Auth::user()->company_id,
                ]);
            // Create System Logs used for Auditing of Logs



        return response()->json([
            'status' => 'success',
            'message' => 'Transaction Created successfully!'  // Changed message to reflect update action
        ]);
    }




}
