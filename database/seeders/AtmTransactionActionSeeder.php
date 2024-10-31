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
        $data =
        [
            ['id' => 1, 'name' => 'Borrowed', 'type' => 'Going to Branch Office', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'name' => 'Release', 'type' => 'Going to Branch Office','status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 4, 'name' => 'Returning of Borrowed ATM', 'type' => 'Going to Head Office','status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 5, 'name' => 'New Client', 'type' => 'Going to Head Office','status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 6, 'name' => 'Safekeeping ', 'type' => 'Going to Head Office','status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 7, 'name' => 'Renewal', 'type' => 'Going to Head Office','status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 8, 'name' => 'Yellow Paper', 'type' => 'Going to Head Office','status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 9, 'name' => 'Going Back to Branch (Safekeep)', 'type' => 'Going to Branch Office','status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 10, 'name' => 'Borrowed - Returning', 'type' => 'Going to Branch Office','status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 11, 'name' => 'Replacement of ATM', 'type' => 'Going to Branch Office','status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 12, 'name' => 'Returning of Old ATM', 'type' => 'Going to Head Office','status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 13, 'name' => 'Cancelled Loan', 'type' => NULL,'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 14, 'name' => 'Cancelled Request Released','type' => NUll, 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 15, 'name' => 'Returning of Cancelled Form', 'type' => NULL,'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 16, 'name' => 'Release With Balance', 'type' => 'Going to Branch Office','status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 17, 'name' => 'New ATM (Replacement)', 'type' => 'Going to Head Office','status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 18, 'name' => 'New ATM (Add ATM)', 'type' => 'Going to Head Office','status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 19, 'name' => 'Passbook For Collection', 'type' => 'Going to Branch Office','status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 20, 'name' => 'Returning of Borrowed Passbook', 'type' => 'Going to Head Office','status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 21, 'name' => 'ATM Did not Replaced', 'type' => 'Going to Head Office','status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 22, 'name' => 'Returning / Balik Loob Client','type' => 'Going to Head Office', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 23, 'name' => 'Add ATM','type' => 'Going to Head Office', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];
        DB::table('atm_transaction_actions')->insert($data);
    }
}
