<?php

namespace Database\Seeders\ElogSeeder;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DataBorrowOptionSeeder extends Seeder
{
    public function run()
    {
        $datas = [
            ['For SSS / GSIS Report'],
            ['For SSS / GSIS Loan'],
            ['Bank Report'],
            ['For Requirements'],
            ['For Collection'],
            ['Others'],
        ];

        foreach ($datas as $data) {
            DB::table('elm_borrow_options')->insert([
                'reason' => $data[0],
                'status' => 'Active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }

}
