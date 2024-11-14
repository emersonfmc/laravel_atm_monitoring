<?php

namespace App\Http\Controllers\ATM;

use Illuminate\Http\Request;
use App\Models\DataBankLists;
use App\Models\AtmClientBanks;
use App\Models\DataCollectionDate;
use App\Http\Controllers\Controller;
use App\Models\AtmTransactionAction;
use Illuminate\Support\Facades\Auth;
use App\Models\DataTransactionAction;
use Yajra\DataTables\Facades\DataTables;

class AtmBranchOfficeController extends Controller
{
    public function BranchOfficePage()
    {
        $DataCollectionDate = DataCollectionDate::where('status','Active')->get();
        $DataBankLists = DataBankLists::where('status','Active')->get();

        return view('pages.pages_backend.atm.atm_branch_office_atm_lists', compact('DataCollectionDate','DataBankLists'));
    }

    public function BranchOfficeData()
    {
        $userBranchId = Auth::user()->branch_id;
        $userGroup = Auth::user()->UserGroup->group_name;

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
            ->rawColumns(['action','pending_to']) // Render the HTML in the 'action' column
            ->make(true);
    }

}
