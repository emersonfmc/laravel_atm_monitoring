<?php

namespace App\Http\Controllers\ATM;

use Illuminate\Http\Request;
use App\Models\AtmClientBanks;
use App\Models\ClientInformation;
use App\Http\Controllers\Controller;

use App\Models\AtmTransactionAction;
use App\Models\Branch;
use App\Models\DataBankLists;
use App\Models\DataCollectionDate;
use App\Models\DataReleaseOption;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AtmHeadOfficeController extends Controller
{
    public function HeadOfficePage()
    {
        // // Start the query with the necessary relationships
        // $HeadOfficeData = AtmClientBanks::with('ClientInformation', 'Branch', 'AtmBanksTransaction')
        //     ->where('location', 'Head Office')
        //     ->latest('updated_at')
        //     ->get();

        // dd($HeadOfficeData);
        $DataBankLists = DataBankLists::where('status','Active')->get();
        $DataCollectionDate = DataCollectionDate::where('status','Active')->get();
        $Branches = Branch::where('status','Active')->get();

        $AtmTransactionAction = AtmTransactionAction::where('transaction','1')
            ->where('status','Active')
            ->get();

        $DataReleaseOption = DataReleaseOption::where('status','Active')
            ->get();

        return view('pages.pages_backend.atm.atm_head_office_atm_lists', compact('AtmTransactionAction','DataReleaseOption','DataBankLists','DataCollectionDate','Branches'));
    }

    // public function HeadOfficeData()
    // {
    //     $userBranchId = Auth::user()->branch_id;
    //     $userGroup = Auth::user()->UserGroup->group_name;

    //     // Start the query with the necessary relationships
    //     $query = AtmClientBanks::with('ClientInformation', 'Branch', 'AtmBanksTransaction')
    //         ->where('location', 'Head Office')
    //         ->latest('updated_at');

    //     // Check if the user has a valid branch_id
    //     if ($userBranchId !== null && $userBranchId !== 0) {
    //         // Filter by branch_id if it's set and valid
    //         $query->where('branch_id', $userBranchId);
    //     }

    //     // Get the filtered data
    //     $HeadOfficeData = $query->get();

    //     return DataTables::of($HeadOfficeData)
    //     ->setRowId('id')
    //     ->addColumn('action', function($row) use ($userGroup) {
    //         // Initialize the variable to check ongoing transactions
    //         $hasOngoingTransaction = false;

    //         // Check if the AtmBanksTransaction relationship is loaded
    //         if ($row->AtmBanksTransaction) {
    //             // Check if there is at least one ongoing transaction, ordered by id
    //             $hasOngoingTransaction = $row->AtmBanksTransaction
    //                 ->where('status', 'ON GOING')
    //                 ->where('bank_account_no', $HeadOfficeData->bank_account_no)
    //                 ->sortBy('id') // Sort by 'id'
    //                 ->first() !== null; // Check if there's at least one record
    //         }

    //         // Only show the button for users in specific groups
    //         if (in_array($userGroup, ['Developer', 'Admin', 'Branch Head', 'Everfirst Admin'])) {
    //             if ($hasOngoingTransaction) {
    //                 return '<i class="fa-solid fa-gear fa-spin-pulse"></i>'; // Show spinning gear icon if there are ongoing transactions
    //             } else {
    //                 // Check if there are no transactions at all
    //                 $hasTransactions = $row->AtmBanksTransaction && $row->AtmBanksTransaction->isNotEmpty();
    //                 if (!$hasTransactions) {
    //                     return '<a href="#" class="btn btn-warning createTransaction" data-id="' . $row->id . '">
    //                                 <i class="fas fa-plus-circle fs-5"></i>
    //                             </a>'; // Show message if there are no transactions
    //                 } else {
    //                     return '<a href="#" class="btn btn-warning createTransaction" data-id="' . $row->id . '">
    //                                 <i class="fas fa-plus-circle fs-5"></i>
    //                             </a>';
    //                 }
    //             }
    //         }
    //         return ''; // Return empty if user is not in specified groups
    //     })
    //     ->rawColumns(['action']) // Render the HTML in the 'action' column
    //     ->make(true);

    // }

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

        return DataTables::of($HeadOfficeData)
            ->setRowId('id')
            ->addColumn('action', function($row) use ($userGroup) {
                $hasOngoingTransaction = false;
                $latestTransactionId = null;
                $action = ''; // Initialize a variable to hold the buttons

                if ($row->AtmBanksTransaction) {
                    // Filter for ongoing transactions and sort them by 'id' in descending order
                    $ongoingTransactions = $row->AtmBanksTransaction->filter(function ($transaction) {
                        return $transaction->status === 'ON GOING';
                    })->sortByDesc('id'); // Sort by id in descending order

                    // Check if there is at least one ongoing transaction
                    if ($ongoingTransactions->isNotEmpty()) {
                        $hasOngoingTransaction = true;
                        // Get the latest ongoing transaction's ID
                        $latestTransactionId = $ongoingTransactions->first()->id;
                    }
                }

                // Only show the button for users in specific groups
                if (in_array($userGroup, ['Developer', 'Admin', 'Branch Head', 'Everfirst Admin'])) {
                    if ($hasOngoingTransaction) {
                        // Show spinning gear icon if there are ongoing transactions
                        return '<i class="fas fa-spinner fa-spin fs-3 text-success"></i>';
                    } else {
                        // Add buttons for creating a transaction and adding ATM transaction
                        $action .= '<a href="#" class="btn btn-primary createTransaction me-2 mb-2"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Create Transaction"
                                            data-id="' . $row->id . '">
                                        <i class="fas fa-plus-circle"></i>
                                     </a>
                                     <a href="#" class="btn btn-success addAtmTransaction me-2 mb-2"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Add ATM"
                                        data-id="' . $row->id . '">
                                        <i class="fas fa-credit-card"></i>
                                     </a>';
                    }
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
            ->rawColumns(['action', 'pending_to']) // Render HTML in both the action and pending_to columns
            ->make(true);
    }







}
