<?php

namespace App\Http\Controllers\ATM;

use App\Models\AtmBanks;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AtmBankLists;
use App\Models\AtmPensionTypes;
use App\Models\AtmPensionTypesLists;
use Yajra\DataTables\Facades\DataTables;

class AtmSettingsController extends Controller
{

    public function BankListsPage()
    {
        return view('pages.pages_backend.settings.bank_lists_page');
    }

    public function BankListsData()
    {
       $AtmBankLists = AtmBankLists::latest('updated_at')
            ->get();

        return DataTables::of($AtmBankLists)
            ->setRowId('id')
            ->make(true);
    }

    public function PensionTypesPage()
    {
        return view('pages.pages_backend.settings.pension_types_page');
    }

    public function PensionTypesData()
    {
       $AtmPensionTypesLists = AtmPensionTypesLists::latest('updated_at')
            ->get();

        return DataTables::of($AtmPensionTypesLists)
        ->setRowId('id')
        ->make(true);
    }
}
