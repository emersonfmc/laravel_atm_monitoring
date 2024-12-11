<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Models\DataArea;
use App\Models\DataDistrict;
use Illuminate\Http\Request;
use App\Models\DataBankLists;
use App\Models\DataUserGroup;
use App\Models\AtmClientBanks;
use App\Models\ClientInformation;
use App\Models\AtmBanksTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\DataTransactionAction;
use Yajra\DataTables\Facades\DataTables;
use App\Models\AtmBanksTransactionApproval;

class DashboardController extends Controller
{
    //
    // public function dashboard()
    // {
    //     return view('index');
    // }

    public function elog_monitoring_dashboard()
    {
        $branch_id = Auth::user()->branch_id;
        $Branches = Branch::where('status', 'Active')->get();
        $DataTransactionAction = DataTransactionAction::where('status', 'Active')->get();

        return view('pages.pages_backend.dashboard',compact('branch_id','Branches','DataTransactionAction'));
    }

    public function elog_monitoring_dashboard_data(Request $request)
    {
        $branchId = Auth::user()->branch_id;

        $UserCount = User::where('status', 'Active')->count();
        $AreaCount = DataArea::where('status', 'Active')->count();
        $DistrictCount = DataDistrict::where('status', '1')->count();
        $BranchCount = Branch::where('status', 'Active')->count();
        $UserGroupCount = DataUserGroup::where('status', 'Active')->count();
        $BanksCount = DataBankLists::where('status', 'Active')->count();

        $TopBranchesCount = ClientInformation::selectRaw('branch_id, COUNT(*) as client_count')
            ->with('branch') // Include branch relationship
            ->groupBy('branch_id')
            ->orderByDesc('client_count') // Sort by count in descending order
            ->limit(3) // Get top 3
            ->get();


        // Count By Client Monthly
            $yearClient = $request->yearClient ?? ''; // Requested year or empty for all
            // $yearClient = $request->yearClient ?? now()->year; // Use this if want to display all current year data

            // Count By Client Monthly
            $ClientCounts = ClientInformation::selectRaw('
                YEAR(created_at) as year,
                MONTH(created_at) as month,
                COUNT(*) as client_monthly_counts
            ')
            ->whereNotNull('created_at') // Exclude NULL created_at values
            ->when($branchId, function ($query) use ($branchId) {
                // Apply branch_id filter only if the user has a branch_id
                return $query->where('branch_id', $branchId);
            })
            ->when($yearClient, function ($query) use ($yearClient) {
                // Apply year filter only if $yearClient is provided
                return $query->whereYear('created_at', $yearClient);
            })
            ->groupByRaw('YEAR(created_at), MONTH(created_at)') // Group by year and month
            ->get();
        // Count By Client Monthly

        // Count By ATM Passbook Simcard Monthly
            $yearAtm = $request->yearAtm ?? '';
            // $yearAtm = $request->yearAtm ?? now()->year; // Use this if want to display all current year data

            $AtmClientBanksCounts = AtmClientBanks::selectRaw('
                YEAR(created_at) as year,
                MONTH(created_at) as month,
                COUNT(CASE WHEN atm_type = "ATM" THEN 1 END) as atm_count,
                COUNT(CASE WHEN atm_type = "Passbook" THEN 1 END) as passbook_count,
                COUNT(CASE WHEN atm_type = "Sim Card" THEN 1 END) as sim_card_count
            ')
            ->whereNotNull('created_at') // Exclude NULL created_at values
            ->when($branchId, function ($query) use ($branchId) {
                // Apply branch_id filter only if the user has a branch_id
                return $query->where('branch_id', $branchId);
            })
            ->when($yearAtm, function ($query) use ($yearAtm) {
                return $query->whereYear('created_at', $yearAtm);
            })
            ->groupByRaw('YEAR(created_at), MONTH(created_at)') // Group by year and month
            ->get();
        // Count By ATM Passbook Simcard Monthly

        // Pending Transaction with subquery for branch filtering

        $userGroup = Auth::user()->UserGroup->group_name;

        $PendingReceivingTransaction = AtmBanksTransactionApproval::with('AtmBanksTransaction', 'AtmBanksTransaction.DataTransactionAction', 'AtmBanksTransaction.Branch')
            ->where('status', 'Pending')
            ->when(!in_array($userGroup, ['Developer', 'Admin', 'Everfirst Admin']), function ($query) use ($branchId) {
                // Apply the filtering logic only if the user is not a Developer
                $query->where('user_groups_id', Auth::user()->user_group_id)
                      ->whereHas('AtmBanksTransaction', function ($subQuery) use ($branchId) {
                          if ($branchId !== null && $branchId !== 0) {
                              $subQuery->where('branch_id', $branchId);
                          }
                      });
            })
            ->latest('created_at') // Correctly reference the created_at column of the AtmBanksTransaction model
            ->limit(5)
            ->get();
        // Pending Transaction with subquery for branch filtering

        // Return the counts as a JSON response
        return response()->json([
            'UserCount' => $UserCount,
            'AreaCount' => $AreaCount,
            'DistrictCount' => $DistrictCount,
            'BranchCount' => $BranchCount,
            'UserGroupCount' => $UserGroupCount,
            'BanksCount' => $BanksCount,
            'TopBranchesCount' => $TopBranchesCount,
            'ClientCounts' => $ClientCounts,
            'AtmClientBanksCounts' => $AtmClientBanksCounts,
            'PendingReceivingTransaction' => $PendingReceivingTransaction
        ]);
    }

    public function elog_monitoring_transaction_data(Request $request)
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
        ])
        ->where('status','ON GOING')
        ->latest('updated_at');

        // Apply branch filter based on user branch_id or request input
        if ($branch_id) {
            $query->where('branch_id', $branch_id);
        } elseif ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('transaction_actions_id')) {
            $query->where('transaction_actions_id', $request->transaction_actions_id);
        }

        return DataTables::of($query)
            ->setRowId('id')
            ->addColumn('action', function($row) use ($userGroup) {
                $action = ''; // Initialize a variable to hold the buttons

                $action .= '<a href="#" class="text-info viewTransaction me-2 mb-2"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="View Transaction"
                                data-id="' . $row->id . '">
                                <i class="fas fa-eye fs-5"></i>
                                </a>';

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

    public function SidebarCount()
    {
        $userBranchId = Auth::user()->branch_id;

        // Initialize query builders
        $ClientInformationCount = ClientInformation::get();

        $HeadOfficeCounts = AtmClientBanks::where('location', 'Head Office')->where('status', '1');
        $BranchOfficeCounts = AtmClientBanks::where('location', 'Branch')->where('status', '1');
        $ReleasedCounts = AtmClientBanks::where('location', 'Released')->whereIn('status', ['0', '2', '3', '4', '5', '7']);
        $SafekeepCounts = AtmClientBanks::where('location', 'Safekeep')->where('status', '6');
        $OnGoingTransaction = AtmBanksTransaction::where('status', 'ON GOING');

        $userBranchId = Auth::user()->branch_id;
        $userGroup = Auth::user()->UserGroup->group_name;

        $PendingReceivingTransaction = AtmBanksTransactionApproval::with('AtmBanksTransaction')
            ->where('status', 'Pending')
            ->where('type', 'Received')
            ->when(!in_array($userGroup, ['Developer', 'Admin', 'Everfirst Admin']), function($query) {
                return $query->where('user_groups_id', Auth::user()->user_group_id);
            })
            ->whereHas('AtmBanksTransaction', function ($query) use ($userBranchId) {
                if ($userBranchId !== null && $userBranchId !== 0) {
                    $query->where('branch_id', $userBranchId);
                }
            });

        $PendingReleasingTransaction = AtmBanksTransactionApproval::where('status', 'Pending')
            ->where('type', 'Released')
            ->when(!in_array($userGroup, ['Developer', 'Admin', 'Everfirst Admin']), function($query) {
                return $query->where('user_groups_id', Auth::user()->user_group_id);
            });

        // Apply branch_id filter to all queries if branch_id is set
        if ($userBranchId !== null && $userBranchId !== 0) {
            $ClientInformationCount->where('branch_id', $userBranchId);
            $HeadOfficeCounts->where('branch_id', $userBranchId);
            $BranchOfficeCounts->where('branch_id', $userBranchId);
            $ReleasedCounts->where('branch_id', $userBranchId);
            $SafekeepCounts->where('branch_id', $userBranchId);
            $OnGoingTransaction->where('branch_id', $userBranchId);
        }

        // Get the counts
        $ClientInformationCount = $ClientInformationCount->count();
        $HeadOfficeCount = $HeadOfficeCounts->count();
        $BranchOfficeCount = $BranchOfficeCounts->count();
        $ReleasedCount = $ReleasedCounts->count();
        $SafekeepCount = $SafekeepCounts->count();
        $OnGoingTransactionCount = $OnGoingTransaction->count();
        $PendingReceivingTransactionCount = $PendingReceivingTransaction->count();
        $PendingReleasingTransactionCount = $PendingReleasingTransaction->count();

        // Return the counts as a JSON response
        return response()->json([
            'ClientInformationCount' => $ClientInformationCount,
            'HeadOfficeCounts' => $HeadOfficeCount,
            'BranchOfficeCounts' => $BranchOfficeCount,
            'ReleasedCounts' => $ReleasedCount,
            'SafekeepCounts' => $SafekeepCount,
            'OnGoingTransaction' => $OnGoingTransactionCount,
            'PendingReceivingTransaction' => $PendingReceivingTransactionCount,
            'PendingReleasingTransaction' => $PendingReleasingTransactionCount
        ]);
    }

    public function main_dashboard()
    {
        return view('pages.pages_backend.main_dashboard');
    }






}
