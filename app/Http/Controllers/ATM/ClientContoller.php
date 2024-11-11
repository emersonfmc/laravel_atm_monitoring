<?php

namespace App\Http\Controllers\ATM;
use App\Models\SystemLogs;

use Illuminate\Http\Request;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

use App\Models\ClientInformation;
use App\Models\DataCollectionDate;
use App\Models\AtmBanksTransaction;
use App\Models\AtmTransactionSequence;
use App\Models\AtmTransactionBalanceLogs;
use App\Models\AtmBanksTransactionApproval;
use App\Models\DataBankLists;
use App\Models\AtmClientBanks;
use App\Models\Branch;
use App\Models\DataTransactionSequence;

class ClientContoller extends Controller
{
    public function client_page()
    {
        $userGroup = Auth::user()->UserGroup->group_name;  // Assuming the group name is stored in the 'name' field

        if (in_array($userGroup, ['Developer','Admin','Everfirst Admin'])) {
            $branches = Branch::where('status', 'Active')->get();
        } else {
            $branches = collect();  // Return an empty collection if not authorized
        }

        $DataCollectionDates = DataCollectionDate::where('status', 'Active')->get();
        $DataBankLists = DataBankLists::where('status', 'Active')->get();

        return view('pages.pages_backend.atm.atm_clients_page', compact('branches','userGroup','DataCollectionDates','DataBankLists'));
    }

    public function client_data()
    {
        $userBranchId = Auth::user()->branch_id;

        // Start the query with the necessary relationships
        $query = ClientInformation::with('Branch', 'AtmClientBanks')->latest('updated_at');

        // Check if the user has a valid branch_id
        if ($userBranchId !== null && $userBranchId !== 0) {
            // Filter by branch_id if it's set and valid
            $query->where('branch_id', $userBranchId);
        }

        // Get the filtered data
        $branchData = $query->get();

        // Return the data as DataTables response
        return DataTables::of($branchData)
            ->setRowId('id')
            ->make(true);
    }

    public function clientCreate(Request $request)
    {

            $existingPensionNumber = ClientInformation::where('pension_number', $request->pension_number)
                        ->where('pension_type', $request->pension_type)
                        ->exists();

            // If it exists, return a response with a message
            if ($existingPensionNumber) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Duplicate Pension Number Found'
                ]);
            }
            else
            {
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
                    'pension_number' => $pension_number ?? NULL,
                    'branch_id' => $branch_id ?? NULL,
                    'pension_type' => $request->pension_type ?? NULL,
                    'pension_account_type' => $request->pension_account_type ?? NULL,
                    'first_name' => $request->first_name ?? NULL,
                    'middle_name' => $request->middle_name ?? NULL,
                    'last_name' => $request->last_name ?? NULL,
                    'suffix' => $request->suffix ?? NULL,
                    'birth_date' => $request->birth_date ?? NULL,
                    'created_at' => Carbon::now(),
                ]);

                if (is_array($request->atm_type) && !empty($request->atm_type)) {
                    foreach ($request->atm_type as $key => $value)
                    {
                        $transactionCounter = $lastadded + $key + 1;
                        $TransactionNumber = $branch_abbreviation . '-' . date('mdy') . '-' . str_pad($transactionCounter, 5, '0', STR_PAD_LEFT);

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

                        $DataTransactionSequence = DataTransactionSequence::where('transaction_actions_id', 5)
                            ->orderBy('sequence_no')
                            ->get();

                        foreach ($DataTransactionSequence as $transactionSequence)
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

                        $balance = floatval(preg_replace('/[^\d]/', '', $request->atm_balance[$key]));

                        AtmTransactionBalanceLogs::create([
                            'banks_transactions_id' => $AtmBanksTransaction->id,
                            'check_by_user_id' => Auth::user()->id,
                            'balance' => $balance,
                            'remarks' => $request->remarks[$key] ?? NULL,
                            'created_at' => Carbon::now(),
                        ]);

                    }
                }

            }


        return response()->json([
            'status' => 'success',
            'message' => 'New Client Created successfully!'  // Changed message to reflect update action
        ]);
    }

    public function clientGet($id)
    {
        $ClientInformation = ClientInformation::with('Branch','AtmClientBanks')->findOrFail($id);
        return response()->json($ClientInformation);
    }

    public function PensionNumberValidate(Request $request)
    {
        // Remove any non-numeric characters (like hyphens) from the pension number
        $pension_number_get = preg_replace('/[^0-9]/', '', $request->pension_number);

        // Get the authenticated user's branch_id
        $user_branch_id =  Auth::user()->branch_id;

        // Query to find if the pension number exists in the client information
        $clientInfo = ClientInformation::with('Branch')
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





}
