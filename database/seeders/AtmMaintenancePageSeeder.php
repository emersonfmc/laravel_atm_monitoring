<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AtmMaintenancePageSeeder extends Seeder
{
    public function run()
    {
            $data = [
                        [
                            'pages_name' => 'Head Office',
                            'status' => 'no',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'pages_name' => 'Branch Office',
                            'status' => 'no',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'pages_name' => 'Receiving of Transaction',
                            'status' => 'no',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'pages_name' => 'Releasing of Transaction',
                            'status' => 'no',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                    ];
            DB::table('maintenance_pages')->insert($data);
    }
}
