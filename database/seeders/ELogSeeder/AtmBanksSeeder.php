<?php

namespace Database\Seeders\ElogSeeder;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AtmBanksSeeder extends Seeder
{
    public function run()
    {
        $datas = [
            [1, 'UCPB', 'Active'],
            [2, 'PNB', 'Active'],
            [3, 'EastWest Bank', 'Active'],
            [4, 'China Bank', 'Active'],
            [5, 'BDO', 'Active'],
            [6, 'SBTC', 'Active'],
            [7, 'LBP', 'Active'],
            [8, 'BPI', 'Active'],
            [9, 'Union Bank', 'Active'],
            [10, 'Robinsons Bank', 'Active'],
            [11, 'Asia United Bank', 'Active'],
            [12, 'Malayan Bank', 'Active'],
            [13, 'RCBC', 'Active'],
            [14, 'DBP', 'Active'],
            [15, 'Bank of Makati', 'Active'],
            [16, 'City State Bank', 'Active'],
            [17, 'Equicom Saving Bank', 'Active'],
            [18, 'Veterans Bank', 'Active'],
            [19, 'Philtrust Bank', 'Active'],
            [20, 'Rural Bank', 'Active'],
            [21, 'May Bank', 'Active'],
            [22, 'HSBC Bank', 'Active'],
            [23, 'Philippine Business Bank', 'Inactive'],
            [24, 'Sterling Bank of Asia', 'Active'],
            [25, 'SeaBank', 'Active'],
            [26, 'MBTC', 'Active'],
            [27, 'PSB', 'Active'],
            [28, 'Bank of Commerce', 'Active'],
            [29, 'VTB Bank', 'Active'],
            [30, 'CTBC Bank', 'Active'],
            [32, 'Philippine Bank of Communications', 'Active'],
            [33, 'Producers Bank', 'Inactive'],
            [34, 'TOPBANK', 'Active'],
            [35, 'Innovative Bank', 'Active'],
            [36, 'MVSM Bank', 'Active'],
            [37, 'GRBANK', 'Active'],
            [39, 'Bangko Kabayan', 'Active'],
            [40, 'China Trust', 'Active'],
            [41, 'Prestige Bank', 'Active'],
            [42, 'Porac Bank', 'Active'],
            [43, 'Bangko Nuestra Senora Del Pilar INC.', 'Active'],
            [44, 'BPI ADA', 'Active'],
            [45, 'BDO ADA', 'Active'],
            [46, 'PNB ADA', 'Active'],
            [47, 'Union Bank ADA', 'Active'],
            [48, 'MBTC ATDA', 'Active'],
            [49, 'LandBank', 'Inactive'],
            [50, 'Malarayat Bank', 'Active'],
            [51, 'BANGKO MABUHAY', 'Active'],
            [52, 'Security Bank', 'Active'],
            [53, 'PSBANK', 'Inactive'],
            [54, 'All Bank', 'Active'],
            [55, 'Rural Bank of Montalban Inc.', 'Active'],
            [56, 'Bank of Florida', 'Active'],
            [57, 'Wealth Bank', 'Active'],
            [58, 'Country Builders Bank', 'Active'],
            [59, 'GCASH BANK', 'Active'],
            [60, 'RCBC ADA', 'Active'],
            [61, 'Paymaya', 'Active'],
            [62, 'PLANBANK', 'Active'],
            [63, 'Cebuana Lhuillier', 'Active'],
        ];

        foreach ($datas as $data) {
            DB::table('elm_bank_lists')->insert([
                'id' => $data[0],
                'bank_name' => $data[1],
                'status' => $data[2],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }

}
