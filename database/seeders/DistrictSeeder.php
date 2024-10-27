<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DistrictSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'district_number' => 'D1',
                'district_name' => 'Helen Actub',
                'email' => 'hcactub@everfirstloans.com',
                'company_id' => 2,
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'district_number' => 'D2',
                'district_name' => 'Vanessa Belangoy',
                'email' => 'vabelangoy@everfirstloans.com',
                'company_id' => 2,
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'district_number' => 'D3',
                'district_name' => 'Josefina Evangelista',
                'email' => 'jdevangelista@everfirstloans.com',
                'company_id' => 2,
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'district_number' => 'D4',
                'district_name' => 'Clatchel Cosme',
                'email' => 'cvcosme@everfirstloans.com',
                'company_id' => 2,
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'district_number' => 'D5',
                'district_name' => 'Edgardo Rivera',
                'email' => 'ecrivera@everfirstloans.com',
                'company_id' => 2,
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'district_number' => 'D6',
                'district_name' => 'Anweda Enoy',
                'email' => 'alenoy@everfirstloans.com',
                'company_id' => 2,
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        DB::table('data_districts')->insert($data);
    }
}
