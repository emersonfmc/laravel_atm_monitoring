<?php

namespace App\Http\Controllers\ATM;

use Illuminate\Http\Request;
use App\Models\AtmClientBanks;
use App\Http\Controllers\Controller;
use App\Models\AtmTransactionAction;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AtmBranchOfficeController extends Controller
{
    public function BranchOfficePage()
    {
        return view('pages.pages_backend.atm.atm_branch_office_atm_lists');
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
                $latestTransactionId = null;
                $action = ''; // Initialize a variable to hold the buttons

                if ($row->AtmBanksTransaction) {
                    // Filter for completed transactions and sort them by 'id' in descending order
                    $completedTransactions = $row->AtmBanksTransaction->filter(function ($transaction) {
                        return $transaction->status === 'COMPLETED';
                    })->sortByDesc('id'); // Sort by id in descending order

                    // Check if there is at least one completed transaction
                    if ($completedTransactions->isNotEmpty()) {
                        $hasOngoingTransaction = true;
                        // Get the latest completed transaction's ID
                        $latestTransaction = $completedTransactions->first();
                        $latestTransactionId = $latestTransaction->id;
                    }
                }

                // Only show the button for users in specific groups
                if (in_array($userGroup, ['Developer', 'Admin', 'Branch Head', 'Everfirst Admin'])) {
                    if ($hasOngoingTransaction) {
                        if ($latestTransaction->transaction_actions_id == 3 || $latestTransaction->transaction_actions_id == 9) {
                            $action = '<button type="button" class="btn btn-success release_transaction"
                                            data-atm_id="'.$row->id.'"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="right"
                                            title="Releasing of Transaction">
                                            <i class="fas fa-sign-in-alt"></i>
                                        </button>';
                        }
                        else if ($latestTransaction->transaction_actions_id == 1) {
                            $action = '<button type="button" class="btn btn-warning borrow_transaction"
                                            data-atm_id="'.$row->id.'"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="right"
                                            title="Returning of Borrow Transaction">
                                           <i class="fas fa-undo"></i>
                                        </button>';
                        }
                        else if ($latestTransaction->transaction_actions_id == 11) {
                            $action = '<button type="button" class="btn btn-primary replacement_atm_transaction"
                                            data-atm_id="'.$row->id.'"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="right"
                                            title="Replacement of ATM / Passbook Transaction">
                                          <i class="fas fa-exchange-alt"></i>
                                        </button>';
                        }
                        else if ($latestTransaction->transaction_actions_id == 13) {
                            $action = '<button type="button" class="btn btn-danger cancelled_loan_transaction"
                                            data-atm_id="'.$row->id.'"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="right"
                                            title="Cancelled Loan Transaction">
                                          <i class="fas fa-times-circle"></i>
                                        </button>';
                        }
                        else if ($latestTransaction->transaction_actions_id == 16) {
                            $action = '<button type="button" class="btn btn-danger release_balance_transaction"
                                            data-atm_id="'.$row->id.'"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="right"
                                            title="Release with Oustanding Balance Transaction">
                                          <i class="fas fa-sign-in-alt"></i>
                                        </button>';
                        }
                        else if ($latestTransaction->transaction_actions_id == NULL || !$latestTransaction->transaction_actions_id) {
                            $action = '';
                        }
                        else {
                            // Show spinning gear icon if there are ongoing transactions
                            $action = '<i class="fas fa-spinner fa-spin fs-3 text-success"></i>';
                        }
                    }
                }
                return $action; // Return all the accumulated buttons
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
                            $atmTransactionAction = AtmTransactionAction::find($firstOngoingTransaction->transaction_actions_id);
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
