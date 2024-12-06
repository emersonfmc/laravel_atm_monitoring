<?php

namespace App\Http\Controllers\ATM;

use App\Models\User;
use Mpdf\Mpdf;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;
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
        $slot_number = $print_area_number;
        $reference_number = $transaction_number;

        // Initialize mPDF
        $mpdf = new Mpdf(['mode' => 'utf-8', 'orientation' => 'L']);

        // Generate QR Code
        $qrCode = new QrCode($transaction_number);
        $output = new Output\Png();
        $qrImage = $output->output($qrCode, 100); // 100 is the size of the QR code
        $qrImageBase64 = 'data:image/png;base64,' . base64_encode($qrImage);

        // Initialize HTML for slots
        $html = '';

        for ($i = 1; $i <= 77; $i++) {
            if ($i == $slot_number) {
                $html .= '<div id="slot_number" style="width:80px; height:100px; border: 1px solid black; margin-left:10px; margin-bottom:5px; float:left; padding:0px;">
                            <div style="margin:0; padding:0; height:80px; display: flex; align-items: center; justify-content: center;">
                                <img src="' . $qrImageBase64 . '" alt="QR Code" style="width:76px; height:76px; padding:2px; box-sizing:border-box;">
                            </div>
                            <div style="margin:0; padding:0; border-top: 1px solid black; height:20px;">
                                <p style="font-size: 9vh; margin:0; padding:5px;">'.$reference_number.'</p>
                            </div>
                        </div>';
            } else {
                // Empty Slot
                $html .= '<div id="slot_number" style="width:80px; height:100px; border: 1px solid white; margin-left:10px; margin-bottom:5px; float:left; padding:0px;">
                            <div style="margin:0; padding:0; height:80px;"></div>
                            <div style="margin:0; padding:0; border-top: 1px solid white; height:20px;"></div>
                        </div>';
            }
        }


        // Configure PDF Page Margins
        $mpdf->AddPageByArray([
            'margin-left' => 10,
            'margin-right' => 3,
            'margin-top' => 0,
            'margin-bottom' => 0,
        ]);

        // Write HTML to PDF
        $mpdf->WriteHTML($html);

        // Output PDF
        $mpdf->Output('Transaction_QRCode.pdf', \Mpdf\Output\Destination::INLINE);
    }


}
