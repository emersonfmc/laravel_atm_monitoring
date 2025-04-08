<?php

namespace Database\Seeders\SettingsSeeder;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AtmTransactionActionSeeder extends Seeder
{
    public function run()
    {
        $datas =
        [
            // 1 Creation of Transaction Going All to Branch ( Pullout )
            // 2 Creation of Transaction Going All to Head Office ( Return )
            // 3 Creation of Transaction Going All to Branch Office ( Pullout )
            // 3 Creation of Transaction Going All to Head Office ( Returning )
            // 5 Outside for Collection
            [1,  'Borrowed',                         1, 'Going to Branch Office', NULL , 'Active'],
            [3,  'Release',                          1, 'Going to Branch Office', NULL , 'Active'],
            [4,  'Returning of Borrowed ATM',        2, 'Going to Head Office', NULL , 'Active'],
            [5,  'New Client',                       2, 'Going to Head Office', NULL , 'Active'],
            [6,  'Safekeeping ',                     2, 'Going to Head Office', NULL , 'Active'],
            [7,  'Renewal',                          4, 'Going to Head Office', NULL , 'Active'],
            [8,  'Yellow Paper',                     2, 'Going to Head Office', NULL , 'Active'],
            [9,  'Going Back to Branch (Safekeep)',  3, 'Going to Branch Office', NULL , 'Active'],
            [10, 'Borrowed - Returning',             2, 'Going to Head Office', NULL , 'Active'],
            [11, 'Replacement of ATM',               1, 'Going to Branch Office', NULL , 'Active'],
            [12, 'Returning of Old ATM',             2, 'Going to Head Office', NULL , 'Active'],
            [13, 'Cancelled Loan',                   1, 'Going to Branch Office', NULL , 'Active'],
            [14, 'Cancelled Request Released',       NULL ,NUll, NULL , 'Active'],
            [15, 'Returning of Cancelled Form',      3, 'Going to Head Office', NULL , 'Active'],
            [16, 'Release With Balance',             1, 'Going to Branch Office', NULL , 'Active'],
            [17, 'New ATM (Replacement)',            2, 'Going to Head Office', NULL , 'Active'],
            [18, 'New ATM (Add ATM)',                2, 'Going to Head Office', NULL , 'Active'],
            [19, 'Passbook For Collection',          3, 'Going to Branch Office', NULL , 'Active'],
            [20, 'Returning of Borrowed Passbook',   3, 'Going to Head Office', NULL , 'Active'],
            [21, 'ATM Did not Replaced',             2, 'Going to Head Office', NULL , 'Active'],
            [22, 'Returning / Balik Loob Client',    4, 'Going to Head Office', NULL , 'Active'],
            [23, 'Add ATM',                          2, 'Going to Head Office', NULL , 'Active'],
            [24, 'Collection Thru ATM',              5, 'Outside For Collection', 'ATM' , 'Active'],
            [25, 'Print Out Statement Thru ATM',     5, 'Outside For Collection', 'ATM' , 'Active'],
            [26, 'Activate ATM Card',                5, 'Outside For Collection', 'ATM' , 'Active'],
            [27, 'Passbook Collection',              5, 'Outside For Collection', 'Passbook' , 'Active'],
            [28, 'Update Passbook',                  5, 'Outside For Collection', 'Passbook' , 'Active'],
            [29, 'Change New Passbook',              5, 'Outside For Collection', 'Passbook' , 'Active'],
            [30, 'Deceased For Collection',          5, 'Outside For Collection', 'ATM' , 'Active'],
            [31, 'Other Accounts',                   5, 'Outside For Collection', 'ATM' , 'Active'],
            [32, 'Change Pin',                       5, 'Outside For Collection', 'ATM' , 'Active'],
        ];

        foreach ($datas as $data) {
            DB::table('data_transaction_actions')->insert([
                'id' => $data[0],
                'name' => $data[1],
                'transaction' => $data[2],
                'transaction_type' => $data[3],
                'atm_type' => $data[4],
                'status' => $data[5],
                'created_at' => Carbon::now(), // Use the current date for updated_at
                'updated_at' => Carbon::now(), // Use the current date for updated_at
            ]);
        }
    }
}
