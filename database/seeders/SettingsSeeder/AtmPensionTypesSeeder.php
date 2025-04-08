<?php

namespace Database\Seeders\SettingsSeeder;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AtmPensionTypesSeeder extends Seeder
{
    public function run()
    {
        $data =
        [
            ['pension_name' => 'SSS-SD / EC / ITF', 'types' => 'SSS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'SSS-SD / ITF', 'types' => 'SSS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'SSS-SP', 'types' => 'SSS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'SSS-SP & SD', 'types' => 'SSS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'SSS-SP / EC', 'types' => 'SSS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'SSS-SP / EC / ITF', 'types' => 'SSS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'SSS-SP / ITF', 'types' => 'SSS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'SSS-ST', 'types' => 'SSS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'SSS-ST & SD', 'types' => 'SSS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'SSS-ST / EC', 'types' => 'SSS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'SSS-ST / EC / ITF', 'types' => 'SSS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'SSS-ST / ITF', 'types' => 'SSS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'SSS-RT & SD', 'types' => 'SSS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'SSS-RT / ITF', 'types' => 'SSS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'SSS-RT / SD', 'types' => 'SSS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'SSS-SD', 'types' => 'SSS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'SSS-SD / EC', 'types' => 'SSS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'SSS-RT', 'types' => 'SSS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'SSS-RT/ SD / ITF', 'types' => 'SSS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'SSS-SD / EC / ED', 'types' => 'SSS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'SSS-ED', 'types' => 'SSS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'SSS-SD / SD - EC', 'types' => 'SSS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'GSIS-SD / EC / ITF', 'types' => 'GSIS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'GSIS-SD / ITF', 'types' => 'GSIS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'GSIS-SP', 'types' => 'GSIS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'GSIS-SP & SD', 'types' => 'GSIS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated _at' => Carbon::now()],
            ['pension_name' => 'GSIS-SP / EC', 'types' => 'GSIS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'GSIS-SP / EC / ITF', 'types' => 'GSIS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'GSIS-SP / ITF', 'types' => 'GSIS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'GSIS-ST', 'types' => 'GSIS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'GSIS-ST & SD', 'types' => 'GSIS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'GSIS-ST / EC', 'types' => 'GSIS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'GSIS-ST / EC / ITF', 'types' => 'GSIS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'GSIS-ST / ITF', 'types' => 'GSIS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'GSIS-RT & SD', 'types' => 'GSIS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'GSIS-RT / ITF', 'types' => 'GSIS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'GSIS-RT / SD', 'types' => 'GSIS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'GSIS-SD', 'types' => 'GSIS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'GSIS-SD / EC', 'types' => 'GSIS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'GSIS-RT', 'types' => 'GSIS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'GSIS-RT / SSS-RT', 'types' => 'GSIS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'GSIS-SD / SSS-RT', 'types' => 'GSIS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'GSIS-SD / SSS-SD', 'types' => 'GSIS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['pension_name' => 'GSIS-RT / SSS-SD', 'types' => 'GSIS', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];
        DB::table('data_pension_types_lists')->insert($data);
    }
}
