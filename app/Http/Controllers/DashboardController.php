<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AtmClientBanks;
use App\Models\AtmBanksTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AtmBanksTransactionApproval;
use App\Models\ClientInformation;

class DashboardController extends Controller
{
    //
    public function dashboard()
    {
        return view('index');
    }

    public function display_atm_monitoring_dashboard()
    {
        return view('index');
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


        // Pending Transaction with subquery for branch filtering
        $PendingReceivingTransaction = AtmBanksTransactionApproval::with('AtmBanksTransaction')
            ->where('status', 'Pending')
            ->where('type', 'Received')
            ->where('user_groups_id', Auth::user()->user_group_id)
            ->whereHas('AtmBanksTransaction', function ($query) use ($userBranchId) {
                if ($userBranchId !== null && $userBranchId !== 0) {
                    $query->where('branch_id', $userBranchId);
                }
            });

        $PendingReleasingTransaction = AtmBanksTransactionApproval::where('status', 'Pending')
            ->where('type', 'Released')
            ->where('user_groups_id', Auth::user()->user_group_id);

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






}
