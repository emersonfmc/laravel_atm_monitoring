<?php

namespace Database\Seeders\SettingsSeeder;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DataReleaseReasonSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['reason' => 'Money Not Needed', 'description' => 'Money Not Needed', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['reason' => 'Health Condition', 'description' => 'Health Condition', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['reason' => 'Over Age', 'description' => 'Over Age', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['reason' => 'End of Pension', 'description' => 'End of Pension', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['reason' => 'Cut Pension', 'description' => 'Cut Pension', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['reason' => 'Deceased W/ Balance', 'description' => 'Deceased W/ Balance', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['reason' => 'Release of ATM / Passbook with Outstanding Balance', 'description' => 'Release of ATM / Passbook with Outstanding Balance', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['reason' => 'Buy Out', 'description' => 'Buy Out', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['reason' => 'Moved out', 'description' => 'Moved out', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['reason' => 'Moved To Other Lending', 'description' => 'Moved To Other Lending', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['reason' => 'Swindler', 'description' => 'Swindler', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['reason' => 'Living Abroad', 'description' => 'Living Abroad', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['reason' => 'Bad Accounts', 'description' => 'Bad Accounts', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['reason' => 'With SSS / GSIS / Bank Loan', 'description' => 'With SSS / GSIS / Bank Loan', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['reason' => 'Clients Attitude', 'description' => 'Clients Attitude', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['reason' => 'Deceased Without Balance', 'description' => 'Deceased Without Balance', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['reason' => 'Deceased', 'description' => 'Deceased', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('data_release_options')->insert($data);
    }
}
