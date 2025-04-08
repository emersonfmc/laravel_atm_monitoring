<?php

namespace Database\Seeders\SettingsSeeder;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AtmMaintenancePageSeeder extends Seeder
{
    public function run()
    {
            $data = [
                        ['pages_name' => 'ELOG Dashboard Page','status' => 'no','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
                        ['pages_name' => 'Client Lists Page','status' => 'no','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
                        ['pages_name' => 'Head Office Page','status' => 'no','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
                        ['pages_name' => 'Branch Office Page' ,'status' => 'no','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
                        ['pages_name' => 'Receiving of Transaction Page','status' => 'no','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
                        ['pages_name' => 'Releasing of Transaction Page','status' => 'no','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
                        ['pages_name' => 'Branch Transaction Page','status' => 'no','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
                        ['pages_name' => 'Released Page','status' => 'no','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
                        ['pages_name' => 'Cancelled Loan Page','status' => 'no','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
                        ['pages_name' => 'Safekeep Page','status' => 'no','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
                        ['pages_name' => 'PB Collection Setup Page','status' => 'no','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
                        ['pages_name' => 'PB Collection For Receiving','status' => 'no','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
                        ['pages_name' => 'PB Collection For Releasing','status' => 'no','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
                        ['pages_name' => 'PB Collection For Returning','status' => 'no','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
                        ['pages_name' => 'PB Collection Transaction','status' => 'no','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
                        ['pages_name' => 'PB Collection All Transaction','status' => 'no','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],

                    ];
            DB::table('maintenance_pages')->insert($data);
    }
}
