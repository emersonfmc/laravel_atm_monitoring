<?php

namespace App\Http\Controllers\ATM;
use Exception;
use App\Models\Branch;
use App\Models\SystemLogs;
use Illuminate\Http\Request;
use App\Models\DataBankLists;

use App\Models\AtmClientBanks;
use Illuminate\Support\Carbon;
use App\Models\ClientInformation;
use App\Models\DataReleaseOption;
use App\Models\DataCollectionDate;
use App\Http\Controllers\Controller;
use App\Models\AtmTransactionAction;
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

                // Add buttons for users in Collection Staff and others
                if (in_array($userGroup, ['Collection Staff', 'Developer', 'Admin', 'Everfirst Admin'])) {
                    // Show the button to transfer branch transaction and edit information
                    if($row->atm_type === 'Passbook' && $row->passbook_for_collection === 'no')
                    {
                        $action .= '<a href="#" class="btn btn-info passbookForCollection me-2 mb-2"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="Passbook For Collection"
                                    data-id="' . $row->id . '">
                                    <i class="fas fa-book"></i>
                                </a>';
                    }
                }

                // // Only show the button for users in specific groups
                // if (in_array($userGroup, ['Developer', 'Admin', 'Branch Head', 'Everfirst Admin'])) {
                //     if ($hasOngoingTransaction) {
                //         // Show spinning gear icon if there are ongoing transactions
                //         return '<i class="fas fa-spinner fa-spin fs-3 text-success"></i>';
                //     } else {
                //         // Add buttons for creating a transaction and adding ATM transaction
                //         $action .= '<a href="#" class="text-primary createTransaction me-2 mb-2"
                //                             data-bs-toggle="tooltip"
                //                             data-bs-placement="top"
                //                             title="Create Transaction"
                //                             data-id="' . $row->id . '">
                //                         <i class="fas fa-plus-circle fs-4"></i>
                //                     </a>
                //                         <a href="#" class="text-success addAtmTransaction me-2 mb-2"
                //                             data-bs-toggle="tooltip"
                //                             data-bs-placement="top"
                //                             title="Add ATM"
                //                         data-id="' . $row->id . '">
                //                         <i class="fas fa-credit-card fs-4"></i>
                //                         </a>';
                //     }
                // }

                // // Add buttons for users in Collection Staff and others
                // if (in_array($userGroup, ['Collection Staff', 'Developer', 'Admin', 'Everfirst Admin'])) {
                //     // Show the button to transfer branch transaction and edit information
                //     $action .= '<a href="#" class="text-danger transferBranchTransaction me-2 mb-2"
                //                         data-bs-toggle="tooltip"
                //                         data-bs-placement="top"
                //                         title="Transfer to Other Branch"
                //                         data-id="' . $row->id . '">
                //                     <i class="fas fa-redo-alt fs-4"></i>
                //                 </a>
                //                 <a href="#" class="text-warning EditInformationTransaction me-2 mb-2"
                //                         data-bs-toggle="tooltip"
                //                         data-bs-placement="top"
                //                         title="Edit ATM / Client Information"
                //                         data-id="' . $row->id . '">
                //                     <i class="fas fa-edit fs-4"></i>
                //                 </a>';
                // }

                // // Add buttons for users in Collection Staff and others
                // if (in_array($userGroup, ['Collection Staff', 'Developer', 'Admin', 'Everfirst Admin'])) {
                //     // Show the button to transfer branch transaction and edit information
                //     if($row->atm_type === 'Passbook' && $row->passbook_for_collection === 'no')
                //     {
                //         $action .= '<a href="#" class="text-danger passbookForCollection me-2 mb-2"
                //                     data-bs-toggle="tooltip"
                //                     data-bs-placement="top"
                //                     title="Passbook For Collection"
                //                     data-id="' . $row->id . '">
                //                     <i class="fas fa-book fs-4"></i>
                //                 </a>';
                //     }
                // }

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

    public function PassbookForCollectionSetup(Request $request)
    {
        try {
            $atm_id = $request->atm_id;

            $AtmClientBanks = AtmClientBanks::with('ClientInformation', 'Branch')->findOrFail($atm_id);
            $AtmClientBanks->update([
                'passbook_for_collection' => 'yes',
                'updated_at' => Carbon::now(),
            ]);

            // Create System Logs used for Auditing of Logs
            SystemLogs::create([
                'system' => 'ATM Monitoring',
                'action' => 'Create',
                'title' => 'Passbook For Collection',
                'description' => 'Add to Setup for Passbook For Collection' .  $AtmClientBanks->transaction_number,
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

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while setting up the Passbook for Collection.',
            ]);
        }
    }

    public function SafekeepPage()
    {
        return view('pages.pages_backend.atm.atm_safekeep_lists');
    }

    public function SafekeepData()
    {
        $userBranchId = Auth::user()->branch_id;
        $userGroup = Auth::user()->UserGroup->group_name;

        // Start the query with the necessary relationships
        $query = AtmClientBanks::with('ClientInformation', 'Branch', 'AtmBanksTransaction')
            ->where('location', 'Safekeep')
            ->where('status', '6')
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
                        $action .= '<a href="#" class="btn btn-primary pullOutTransaction me-2 mb-2"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Pullout ATM / PB / Simcard"
                                            data-id="' . $row->id . '">
                                       <i class="fas fa-retweet"></i>
                                     </a>';
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
            ->rawColumns(['action', 'pending_to']) // Render HTML in both the action and pending_to columns
            ->make(true);
    }

    public function ReleasedPage()
    {
        return view('pages.pages_backend.atm.atm_released_lists');
    }

    public function ReleasedData()
    {
        $userBranchId = Auth::user()->branch_id;
        $userGroup = Auth::user()->UserGroup->group_name;


        // Start the query with the necessary relationships
        $query = AtmClientBanks::with('ClientInformation', 'Branch', 'AtmBanksTransaction')
            ->where('location', 'Released')
            ->where('status', ['0','2','3','4','5'])
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
                        $action .= '<a href="#" class="btn btn-success returnClientTransaction me-2 mb-2"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Return Client / Balik Loob"
                                            data-id="' . $row->id . '">
                                      <i class="fas fa-sign-in-alt fa-rotate-180"></i>
                                     </a>';
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
            ->rawColumns(['action', 'pending_to']) // Render HTML in both the action and pending_to columns
            ->make(true);
    }


}
