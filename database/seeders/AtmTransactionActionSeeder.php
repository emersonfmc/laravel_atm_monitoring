<?php

namespace Database\Seeders;

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
            [1,  'Borrowed',                         1, 'Going to Branch Office', 'Active'],
            [3,  'Release',                          1, 'Going to Branch Office','Active'],
            [4,  'Returning of Borrowed ATM',        2, 'Going to Head Office','Active'],
            [5,  'New Client',                       2, 'Going to Head Office','Active'],
            [6,  'Safekeeping ',                     2, 'Going to Head Office','Active'],
            [7,  'Renewal',                          4, 'Going to Head Office','Active'],
            [8,  'Yellow Paper',                     2, 'Going to Head Office','Active'],
            [9,  'Going Back to Branch (Safekeep)',  3, 'Going to Branch Office','Active'],
            [10, 'Borrowed - Returning',             2, 'Going to Head Office','Active'],
            [11, 'Replacement of ATM',               1, 'Going to Branch Office','Active'],
            [12, 'Returning of Old ATM',             2, 'Going to Head Office','Active'],
            [13, 'Cancelled Loan',                   1, 'Going to Branch Office','Active'],
            [14, 'Cancelled Request Released',       NULL ,NUll, 'Active'],
            [15, 'Returning of Cancelled Form',      NULL ,NULL,'Active'],
            [16, 'Release With Balance',             1, 'Going to Branch Office','Active'],
            [17, 'New ATM (Replacement)',            2, 'Going to Head Office','Active'],
            [18, 'New ATM (Add ATM)',                2, 'Going to Head Office','Active'],
            [19, 'Passbook For Collection',          3, 'Going to Branch Office','Active'],
            [20, 'Returning of Borrowed Passbook',   3, 'Going to Head Office','Active'],
            [21, 'ATM Did not Replaced',             2, 'Going to Head Office','Active'],
            [22, 'Returning / Balik Loob Client',    4, 'Going to Head Office', 'Active'],
            [23, 'Add ATM',                          2, 'Going to Head Office', 'Active'],
        ];

        foreach ($datas as $data) {
            DB::table('data_transaction_actions')->insert([
                'id' => $data[0],
                'name' => $data[1],
                'transaction' => $data[2],
                'type' => $data[3],
                'status' => $data[4],
                'created_at' => Carbon::now(), // Use the current date for updated_at
                'updated_at' => Carbon::now(), // Use the current date for updated_at
            ]);
        }
    }
}
