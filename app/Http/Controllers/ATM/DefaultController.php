<?php

namespace App\Http\Controllers\ATM;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\AtmClientBanks;
use App\Http\Controllers\Controller;
use App\Models\DataPensionTypesLists;

class DefaultController extends Controller
{
    public function PensionTypesFetch(Request $request)
    {
        $selected_pension_types = $request->selected_pension_types;
        $DataPensionTypesLists = DataPensionTypesLists::where('types',$selected_pension_types)
            ->where('status','Active')
            ->get();

        return response()->json($DataPensionTypesLists);
    }

    public function AtmClientFetch(Request $request)
    {
        $new_atm_id = $request->new_atm_id;
        $AtmClientBanks = AtmClientBanks::with('ClientInformation','ClientInformation.AtmClientBanks','Branch')->findOrFail($new_atm_id);
        return response()->json($AtmClientBanks);
    }

    public function UserSelect()
    {
        $User = User::get();
        return response()->json($User);
    }
}
