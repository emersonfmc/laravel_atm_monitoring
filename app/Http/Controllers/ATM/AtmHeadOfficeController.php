<?php

namespace App\Http\Controllers\ATM;

use Illuminate\Http\Request;
use App\Models\AtmClientBanks;
use App\Models\ClientInformation;
use App\Http\Controllers\Controller;

use App\Models\AtmTransactionAction;
use App\Models\DataReleaseOption;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AtmHeadOfficeController extends Controller
{
    public function HeadOfficePage()
    {

        $AtmTransactionAction = AtmTransactionAction::where('type','Going to Branch Office')
            ->where('status','Active')
            ->get();

        $DataReleaseOption = DataReleaseOption::where('status','Active')
            ->get();

        return view('pages.pages_backend.atm.atm_head_office_atm_lists', compact('AtmTransactionAction','DataReleaseOption'));
    }

    public function HeadOfficeData()
    {
        $userBranchId = Auth::user()->branch_id;
        $userGroup = Auth::user()->UserGroup->group_name;

        // Start the query with the necessary relationships
        $query = AtmClientBanks::with('ClientInformation', 'Branch', 'AtmBanksTransaction')
            ->where('location', 'Head Office')
            ->latest('updated_at');

        // Check if the user has a valid branch_id
        if ($userBranchId !== null && $userBranchId !== 0) {
            // Filter by branch_id if it's set and valid
            $query->where('branch_id', $userBranchId);
        }

        // Get the filtered data
        $HeadOfficeData = $query->get();

        // Initialize an array to store bank account numbers
        $bankAccountNumbers = [];

        foreach ($HeadOfficeData as $clientBank) {
            $bankAccountNumbers[] = $clientBank->bank_account_no;
        }

        // Return the data as DataTables response
        return DataTables::of($HeadOfficeData)
            ->setRowId('id')
            ->addColumn('action', function($row) use ($userGroup) {
                // Initialize the variable to check ongoing transactions
                $hasOngoingTransaction = false;

                // Check if the AtmBanksTransaction relationship is loaded
                if ($row->AtmBanksTransaction) {
                    // Check if there are any ongoing transactions
                    $hasOngoingTransaction = $row->AtmBanksTransaction
                        ->where('status', ['ON GOING'])->isNotEmpty();
                }

                // Only show the button for users in specific groups
                if (in_array($userGroup, ['Developer', 'Admin', 'Branch Head', 'Everfirst Admin'])) {
                    if ($hasOngoingTransaction) {
                        return '<i class="fa-solid fa-gear fa-spin-pulse"></i>'; // Show spinning gear icon if there are ongoing transactions
                    } else {
                        // Check if there are no transactions at all
                        $hasTransactions = $row->AtmBanksTransaction && $row->AtmBanksTransaction->isNotEmpty();
                        if (!$hasTransactions) {
                            return '<a href="#" class="btn btn-warning createTransaction" data-id="' . $row->id . '">
                                        <i class="fas fa-plus-circle fs-5"></i>
                                    </a>'; // Show message if there are no transactions
                        } else {
                            return '<a href="#" class="btn btn-warning createTransaction" data-id="' . $row->id . '">
                                        <i class="fas fa-plus-circle fs-5"></i>
                                    </a>';
                        }
                    }
                }
                return ''; // Return empty if user is not in specified groups
            })
            ->rawColumns(['action']) // Render the HTML in the 'action' column
            ->make(true);
    }




}
