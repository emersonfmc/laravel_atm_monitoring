<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CompanySeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
            $data = [
                        [
                            'company_name' => 'Filipinas Multi-Line Corp',
                            'status' => 'Active',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'company_name' => 'Everfirst',
                            'status' => 'Active',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                    ];
            DB::table('companies')->insert($data);
    }
}
