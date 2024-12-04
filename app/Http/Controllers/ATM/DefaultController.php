<?php

namespace App\Http\Controllers\ATM;

use App\Models\User;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output\Png;
use Mpdf\QrCode\Output\Mpdf;
use Illuminate\Http\Request;
use App\Models\AtmClientBanks;
use App\Models\ClientInformation;
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

    public function UserSelectServerSide(Request $request)
    {
        $search = $request->input('search');
        $page = $request->input('page', 1);
        $perPage = 10;

        $query = User::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('employee_id', 'LIKE', "%{$search}%");
        }

        $total = $query->count();
        $users = $query->offset(($page - 1) * $perPage)
                    ->limit($perPage)
                    ->get(['employee_id', 'name']);

        return response()->json([
            'users' => $users,
            'pagination' => [
                'more' => ($page * $perPage) < $total
            ]
        ]);
    }


    public function GenerateQRCode($print_area_number, $transaction_number)
    {
        $SlotNumber = $print_area_number;
        $TransactionNumber = $transaction_number;
        // Create a new instance of MPDF
        $mpdf = new Mpdf();

        // Generate QR Code
        $qrCode = new QrCode($transaction_number); // Create QR Code with transaction number
        $output = new Png(); // Set output format as PNG
        $qrImage = $output->output($qrCode, 200); // Generate QR code with size 200px

        dd($qrImage);

        // Embed the QR Code in MPDF
        $mpdf->WriteHTML('<h3>Transaction Number: ' . $transaction_number . '</h3>');
        $mpdf->WriteHTML('<h4>Print Number: ' . $print_area_number . '</h4>');
        $mpdf->WriteHTML('<img src="data:image/png;base64,' . base64_encode($qrImage) . '" alt="QR Code">');

        // Output the PDF in browser
        $mpdf->Output('QRCode.pdf', 'I'); // 'I' for inline display in browser
    }


}
