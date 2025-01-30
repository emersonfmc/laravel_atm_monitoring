<?php

namespace Database\Seeders;
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
            $data = [
                        [
                            'name' => 'Operation',
                            'status' => 'active',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'name' => 'Accounting',
                            'status' => 'active',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'name' => 'Collection',
                            'status' => 'active',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                    ];
            DB::table('data_departments')->insert($data);
    }
}
