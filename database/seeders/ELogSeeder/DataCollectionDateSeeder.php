<?php

namespace Database\Seeders\ElogSeeder;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DataCollectionDateSeeder extends Seeder
{
    public function run()
    {
        $datas = [
            ['1st'],
            ['8th'],
            ['16th'],
            ['1st and 8th'],
            ['1st and 16th'],
            ['8th / 16th'],
            ['8th / 1st'],
        ];

        foreach ($datas as $data) {
            DB::table('elm_collection_dates')->insert([
                'collection_date' => $data[0],
                'status' => 'Active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
