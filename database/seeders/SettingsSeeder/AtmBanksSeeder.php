<?php

namespace Database\Seeders\SettingsSeeder;

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
            ['id' => 1, 'bank_name' => 'UCPB', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'bank_name' => 'PNB', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'bank_name' => 'EastWest Bank', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 4, 'bank_name' => 'China Bank', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 5, 'bank_name' => 'BDO', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 6, 'bank_name' => 'SBTC', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 7, 'bank_name' => 'LBP', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 8, 'bank_name' => 'BPI', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 9, 'bank_name' => 'Union Bank', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 10, 'bank_name' => 'Robinsons Bank', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 11, 'bank_name' => 'Asia United Bank', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 12, 'bank_name' => 'Malayan Bank', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 13, 'bank_name' => 'RCBC', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 14, 'bank_name' => 'DBP', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 15, 'bank_name' => 'Bank of Makati', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 16, 'bank_name' => 'City State Bank', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 17, 'bank_name' => 'Equicom Saving Bank', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 18, 'bank_name' => 'Veterans Bank', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 19, 'bank_name' => 'Philtrust Bank', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 20, 'bank_name' => 'Rural Bank', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 21, 'bank_name' => 'May Bank', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 22, 'bank_name' => 'HSBC Bank', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 23, 'bank_name' => 'Philippine Business Bank', 'status' => 'Inactive', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 24, 'bank_name' => 'Sterling Bank of Asia', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 25, 'bank_name' => 'SeaBank', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 26, 'bank_name' => 'MBTC', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 27, 'bank_name' => 'PSB', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 28, 'bank_name' => 'Bank of Commerce', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 29, 'bank_name' => 'VTB Bank', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 30, 'bank_name' => 'CTBC Bank', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 32, 'bank_name' => 'Philippine Bank of Communications', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 33, 'bank_name' => 'Producers Bank', 'status' => 'Inactive', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 34, 'bank_name' => 'TOPBANK', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 35, 'bank_name' => 'Innovative Bank', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 36, 'bank_name' => 'MVSM Bank', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 37, 'bank_name' => 'GRBANK', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 39, 'bank_name' => 'Bangko Kabayan', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 40, 'bank_name' => 'China Trust', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 41, 'bank_name' => 'Prestige Bank', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 42, 'bank_name' => 'Porac Bank', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 43, 'bank_name' => 'Bangko Nuestra Senora Del Pilar INC.', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 44, 'bank_name' => 'BPI ADA', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 45, 'bank_name' => 'BDO ADA', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 46, 'bank_name' => 'PNB ADA', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 47, 'bank_name' => 'Union Bank ADA', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 48, 'bank_name' => 'MBTC ATDA', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 49, 'bank_name' => 'LandBank', 'status' => 'Inactive', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 50, 'bank_name' => 'Malarayat Bank', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 51, 'bank_name' => 'BANGKO MABUHAY', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 52, 'bank_name' => 'Security Bank', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 53, 'bank_name' => 'PSBANK', 'status' => 'Inactive', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 54, 'bank_name' => 'All Bank', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 55, 'bank_name' => 'Rural Bank of Montalban Inc.', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 56, 'bank_name' => 'Bank of Florida', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 57, 'bank_name' => 'Wealth Bank', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 58, 'bank_name' => 'Country Builders Bank', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 59, 'bank_name' => 'GCASH BANK', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 60, 'bank_name' => 'RCBC ADA', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 61, 'bank_name' => 'Paymaya', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 62, 'bank_name' => 'PLANBANK', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 63, 'bank_name' => 'Cebuana Lhuillier', 'status' => 'Active', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];
        DB::table('data_bank_lists')->insert($data);
    }
}
