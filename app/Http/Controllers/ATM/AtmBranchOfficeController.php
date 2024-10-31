<?php

namespace App\Http\Controllers\ATM;

use Illuminate\Http\Request;
use App\Models\AtmClientBanks;
use App\Http\Controllers\Controller;
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
        $query = AtmClientBanks::with('ClientInformation','Branch')
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
                // Only show the button for users in specific groups
                if (in_array($userGroup, ['Developer', 'Admin', 'Branch Head','Everfirst Admin'])) {
                    return '<a href="#" class="btn btn-warning createTransaction" data-id="' . $row->id . '">
                                <i class="fas fa-plus-circle fs-5"></i>
                            </a>';
                }
                return ''; // Return empty if user is not in specified groups
            })
            ->rawColumns(['action']) // Render the HTML in the 'action' column
            ->make(true);

    }
}
