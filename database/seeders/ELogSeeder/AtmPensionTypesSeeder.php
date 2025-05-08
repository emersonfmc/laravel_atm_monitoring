<?php

namespace Database\Seeders\ElogSeeder;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AtmPensionTypesSeeder extends Seeder
{
    public function run()
    {
        $datas = [
            ['SSS-SD / EC / ITF', 'SSS', 'Active'],
            ['SSS-SD / ITF', 'SSS', 'Active'],
            ['SSS-SP', 'SSS', 'Active'],
            ['SSS-SP & SD', 'SSS', 'Active'],
            ['SSS-SP / EC', 'SSS', 'Active'],
            ['SSS-SP / EC / ITF', 'SSS', 'Active'],
            ['SSS-SP / ITF', 'SSS', 'Active'],
            ['SSS-ST', 'SSS', 'Active'],
            ['SSS-ST & SD', 'SSS', 'Active'],
            ['SSS-ST / EC', 'SSS', 'Active'],
            ['SSS-ST / EC / ITF', 'SSS', 'Active'],
            ['SSS-ST / ITF', 'SSS', 'Active'],
            ['SSS-RT & SD', 'SSS', 'Active'],
            ['SSS-RT / ITF', 'SSS', 'Active'],
            ['SSS-RT / SD', 'SSS', 'Active'],
            ['SSS-SD', 'SSS', 'Active'],
            ['SSS-SD / EC', 'SSS', 'Active'],
            ['SSS-RT', 'SSS', 'Active'],
            ['SSS-RT/ SD / ITF', 'SSS', 'Active'],
            ['SSS-SD / EC / ED', 'SSS', 'Active'],
            ['SSS-ED', 'SSS', 'Active'],
            ['SSS-SD / SD - EC', 'SSS', 'Active'],
            ['GSIS-SD / EC / ITF', 'GSIS', 'Active'],
            ['GSIS-SD / ITF', 'GSIS', 'Active'],
            ['GSIS-SP', 'GSIS', 'Active'],
            ['GSIS-SP & SD', 'GSIS', 'Active'],
            ['GSIS-SP / EC', 'GSIS', 'Active'],
            ['GSIS-SP / EC / ITF', 'GSIS', 'Active'],
            ['GSIS-SP / ITF', 'GSIS', 'Active'],
            ['GSIS-ST', 'GSIS', 'Active'],
            ['GSIS-ST & SD', 'GSIS', 'Active'],
            ['GSIS-ST / EC', 'GSIS', 'Active'],
            ['GSIS-ST / EC / ITF', 'GSIS', 'Active'],
            ['GSIS-ST / ITF', 'GSIS', 'Active'],
            ['GSIS-RT & SD', 'GSIS', 'Active'],
            ['GSIS-RT / ITF', 'GSIS', 'Active'],
            ['GSIS-RT / SD', 'GSIS', 'Active'],
            ['GSIS-SD', 'GSIS', 'Active'],
            ['GSIS-SD / EC', 'GSIS', 'Active'],
            ['GSIS-RT', 'GSIS', 'Active'],
            ['GSIS-RT / SSS-RT', 'GSIS', 'Active'],
            ['GSIS-SD / SSS-RT', 'GSIS', 'Active'],
            ['GSIS-SD / SSS-SD', 'GSIS', 'Active'],
            ['GSIS-RT / SSS-SD', 'GSIS', 'Active'],
        ];

        foreach ($datas as $data) {
            DB::table('elm_pension_types_lists')->insert([
                'pension_name' => $data[0],
                'types' => $data[1],
                'status' => 'Active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
