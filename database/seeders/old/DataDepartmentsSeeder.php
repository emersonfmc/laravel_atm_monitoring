<?php

namespace Database\Seeders\SettingsSeeder;

use Faker\Factory as Faker;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DataDepartmentsSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
            $data =
                    [
                        [
                            'name' => 'Information Technology',
                            'status' => 'Active',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'name' => 'Operation',
                            'status' => 'Active',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'name' => 'Accounting',
                            'status' => 'Active',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'name' => 'Collection',
                            'status' => 'Active',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'name' => 'Treasury',
                            'status' => 'Active',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'name' => 'Audit',
                            'status' => 'Active',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'name' => 'Purchasing',
                            'status' => 'Active',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'name' => 'Human Resources',
                            'status' => 'Active',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'name' => 'Finances',
                            'status' => 'Active',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                    ];
            DB::table('data_departments')->insert($data);
    }
}
