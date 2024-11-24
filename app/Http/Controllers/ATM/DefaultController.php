<?php

namespace App\Http\Controllers\ATM;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\AtmClientBanks;
use App\Models\ClientInformation;
use App\Http\Controllers\Controller;
use App\Models\DataPensionTypesLists;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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

    public function AtmClientBanksFetch(Request $request)
    {
        $client_id = $request->client_id;

        // Fetch the ClientInformation along with its AtmClientBanks that have no ongoing transactions
        $ClientInfo = ClientInformation::with(['AtmClientBanks' => function ($query) {
                $query->whereDoesntHave('AtmBanksTransaction', function ($query) {
                    $query->where('status', 'ON GOING');
                });
            }, 'Branch'])
            ->where('id', $client_id)
            ->firstOrFail();

        return response()->json($ClientInfo);
    }

    public function UserSelect()
    {
        $User = User::get();
        return response()->json($User);
    }

    public function GenerateQRCode($transaction_number)
    {
        // Generate QR code
        $qrCode = QrCode::format('png') // Use PNG format
            ->size(200)                // Set the size of the QR code
            ->generate($transaction_number);

        // Return the QR code as a response
        return view('pages.pages_backend.atm.atm_generate_qr_code', [
            'transactionNumber' => $transaction_number, // Ensure the variable name matches
            'qrCode' => $qrCode,
        ]);
    }


}
