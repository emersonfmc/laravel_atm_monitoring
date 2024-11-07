<?php

namespace App\Http\Controllers\ATM;

use Illuminate\Http\Request;
use App\Models\AtmClientBanks;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PassbookCollectionController extends Controller
{
    public function PassbookCollectionSetUpPage()
    {
        $AtmClientBanks = AtmClientBanks::with('ClientInformation', 'Branch', 'AtmBanksTransaction')
        ->where('passbook_for_collection', 'yes')
        ->latest('updated_at')
        ->get();

        dd($AtmClientBanks);
        return view('pages.pages_backend.passbook.passbook_setup');
    }

    public function PassbookCollectionData()
    {
        $userBranchId = Auth::user()->branch_id;
        $userGroup = Auth::user()->UserGroup->group_name;

        // Start the query with the necessary relationships
        $query = AtmClientBanks::with('ClientInformation', 'Branch', 'AtmBanksTransaction')
            ->where('passbook_for_collection', 'yes')
            ->latest('updated_at');

        // Check if the user has a valid branch_id
        if ($userBranchId !== null && $userBranchId !== 0) {
            // Filter by branch_id if it's set and valid
            $query->where('branch_id', $userBranchId);
        }

        // Get the filtered data
        $PassbookCollectionData = $query->get();

        return DataTables::of($PassbookCollectionData)
            ->setRowId('id');
    }
}
