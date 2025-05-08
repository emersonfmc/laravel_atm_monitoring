<?php

namespace Database\Seeders\ElogSeeder;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DataReleaseReasonSeeder extends Seeder
{
    public function run()
    {
        $datas = [
            ['Money Not Needed', 'Money Not Needed'],
            ['Health Condition', 'Health Condition'],
            ['Over Age', 'Over Age'],
            ['End of Pension', 'End of Pension'],
            ['Cut Pension', 'Cut Pension'],
            ['Deceased W/ Balance', 'Deceased W/ Balance'],
            ['Release of ATM / Passbook with Outstanding Balance', 'Release of ATM / Passbook with Outstanding Balance'],
            ['Buy Out', 'Buy Out'],
            ['Moved out', 'Moved out'],
            ['Moved To Other Lending', 'Moved To Other Lending'],
            ['Swindler', 'Swindler'],
            ['Living Abroad', 'Living Abroad'],
            ['Bad Accounts', 'Bad Accounts'],
            ['With SSS / GSIS / Bank Loan', 'With SSS / GSIS / Bank Loan'],
            ['Clients Attitude', 'Clients Attitude'],
            ['Deceased Without Balance', 'Deceased Without Balance'],
            ['Deceased', 'Deceased'],
        ];

        foreach ($datas as $data) {
            DB::table('elm_release_options')->insert([
                'reason' => $data[0],
                'description' => $data[1],
                'status' => 'Active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
