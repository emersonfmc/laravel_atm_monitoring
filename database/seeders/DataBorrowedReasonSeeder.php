<?php

namespace Database\Seeders;
use Faker\Factory as Faker;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DataBorrowedReasonSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
            $data = [
                        [
                            'name' => 'For SSS / GSIS Report',
                            'status' => 'active',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'name' => 'For SSS / GSIS Loan',
                            'status' => 'active',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'name' => 'Bank Report',
                            'status' => 'active',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'name' => 'For Requirements',
                            'status' => 'active',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                    ];
            DB::table('data_borrowed_reasons')->insert($data);
    }
}
