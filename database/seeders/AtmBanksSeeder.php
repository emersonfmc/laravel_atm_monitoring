<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AtmBanksSeeder extends Seeder
{
    public function run()
    {
        $data =
        [
            ['id' => 1, 'bank_name' => 'UCPB', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'bank_name' => 'PNB', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'bank_name' => 'EastWest Bank', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 4, 'bank_name' => 'China Bank', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 5, 'bank_name' => 'BDO', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 6, 'bank_name' => 'SBTC', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 7, 'bank_name' => 'LBP', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 8, 'bank_name' => 'BPI', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 9, 'bank_name' => 'Union Bank', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 10, 'bank_name' => 'Robinsons Bank', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 11, 'bank_name' => 'Asia United Bank', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 12, 'bank_name' => 'Malayan Bank', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 13, 'bank_name' => 'RCBC', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 14, 'bank_name' => 'DBP', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 15, 'bank_name' => 'Bank of Makati', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 16, 'bank_name' => 'City State Bank', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 17, 'bank_name' => 'Equicom Saving Bank', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 18, 'bank_name' => 'Veterans Bank', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 19, 'bank_name' => 'Philtrust Bank', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 20, 'bank_name' => 'Rural Bank', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 21, 'bank_name' => 'May Bank', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 22, 'bank_name' => 'HSBC Bank', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 23, 'bank_name' => 'Philippine Business Bank', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 24, 'bank_name' => 'Sterling Bank of Asia', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 25, 'bank_name' => 'SeaBank', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 26, 'bank_name' => 'MBTC', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 27, 'bank_name' => 'PSB', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 28, 'bank_name' => 'Bank of Commerce', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 29, 'bank_name' => 'VTB Bank', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 30, 'bank_name' => 'CTBC Bank', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 31, 'bank_name' => 'Philippine Business Bank', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 32, 'bank_name' => 'Philippine Bank of Communications', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 33, 'bank_name' => 'Producers Bank', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 34, 'bank_name' => 'TOPBANK', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 35, 'bank_name' => 'Innovative Bank', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 36, 'bank_name' => 'MVSM Bank', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 37, 'bank_name' => 'GRBANK', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 38, 'bank_name' => 'PRODUCERS BANK', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 39, 'bank_name' => 'Bangko Kabayan', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 40, 'bank_name' => 'China Trust', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 41, 'bank_name' => 'Prestige Bank', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 42, 'bank_name' => 'Porac Bank', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 43, 'bank_name' => 'Bangko Nuestra Senora Del Pilar INC.', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 44, 'bank_name' => 'BPI ADA', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 45, 'bank_name' => 'BDO ADA', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 46, 'bank_name' => 'PNB ADA', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 47, 'bank_name' => 'Union Bank ADA', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 48, 'bank_name' => 'MBTC ATDA', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 49, 'bank_name' => 'LandBank', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 50, 'bank_name' => 'Malarayat Bank', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 51, 'bank_name' => 'BANGKO MABUHAY', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 52, 'bank_name' => 'Security Bank', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 53, 'bank_name' => 'PSBANK', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 54, 'bank_name' => 'All Bank', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 55, 'bank_name' => 'Rural Bank of Montalban Inc.', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 56, 'bank_name' => 'Bank of Florida', 'status' => 'active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];
        DB::table('data_bank_lists')->insert($data);
    }
}
