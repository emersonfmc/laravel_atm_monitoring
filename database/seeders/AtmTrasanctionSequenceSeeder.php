<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AtmTrasanctionSequenceSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['atm_transaction_actions_id' => 1, 'sequence_no' => 1, 'user_group_id' => 31,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 1, 'sequence_no' => 2, 'user_group_id' => 54,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 1, 'sequence_no' => 3, 'user_group_id' => 31,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 1, 'sequence_no' => 4, 'user_group_id' => 21,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 1, 'sequence_no' => 5, 'user_group_id' => 32,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 1, 'sequence_no' => 6, 'user_group_id' => 32,'type' => 'Released','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 1, 'sequence_no' => 7, 'user_group_id' => 9,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 1, 'sequence_no' => 8, 'user_group_id' => 5,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],

            ['atm_transaction_actions_id' => 3, 'sequence_no' => 1, 'user_group_id' => 9,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 3, 'sequence_no' => 2, 'user_group_id' => 25,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 3, 'sequence_no' => 3, 'user_group_id' => 30,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 3, 'sequence_no' => 4, 'user_group_id' => 15,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 3, 'sequence_no' => 5, 'user_group_id' => 31,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 3, 'sequence_no' => 6, 'user_group_id' => 54,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 3, 'sequence_no' => 7, 'user_group_id' => 31,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 3, 'sequence_no' => 8, 'user_group_id' => 21,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 3, 'sequence_no' => 9, 'user_group_id' => 32,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 3, 'sequence_no' => 10, 'user_group_id' => 32,'type' => 'Released','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 3, 'sequence_no' => 11, 'user_group_id' => 9,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 3, 'sequence_no' => 12, 'user_group_id' => 5,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],

            ['atm_transaction_actions_id' => 4, 'sequence_no' => 1, 'user_group_id' => 9,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 4, 'sequence_no' => 2, 'user_group_id' => 15,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 4, 'sequence_no' => 3, 'user_group_id' => 31,'type' => 'Released','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 4, 'sequence_no' => 4, 'user_group_id' => 54,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],

            ['atm_transaction_actions_id' => 5, 'sequence_no' => 1, 'user_group_id' => 9,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 5, 'sequence_no' => 2, 'user_group_id' => 15,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 5, 'sequence_no' => 3, 'user_group_id' => 31,'type' => 'Released','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 5, 'sequence_no' => 4, 'user_group_id' => 54,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],

            ['atm_transaction_actions_id' => 6, 'sequence_no' => 1, 'user_group_id' => 9,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 6, 'sequence_no' => 2, 'user_group_id' => 15,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 6, 'sequence_no' => 3, 'user_group_id' => 52,'type' => 'Released','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],

            ['atm_transaction_actions_id' => 7, 'sequence_no' => 1, 'user_group_id' => 9,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 7, 'sequence_no' => 2, 'user_group_id' => 15,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 7, 'sequence_no' => 3, 'user_group_id' => 31,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 7, 'sequence_no' => 4, 'user_group_id' => 54,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],

            ['atm_transaction_actions_id' => 8, 'sequence_no' => 1, 'user_group_id' => 9,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 8, 'sequence_no' => 2, 'user_group_id' => 15,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],
            ['atm_transaction_actions_id' => 8, 'sequence_no' => 3, 'user_group_id' => 31,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now(),],

            ['atm_transaction_actions_id' => 9, 'sequence_no' => 1, 'user_group_id' => 52,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 9, 'sequence_no' => 2, 'user_group_id' => 53,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 9, 'sequence_no' => 3, 'user_group_id' => 32,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 9, 'sequence_no' => 4, 'user_group_id' => 32,'type' => 'Released','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 9, 'sequence_no' => 5, 'user_group_id' => 15,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 9, 'sequence_no' => 6, 'user_group_id' => 31,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],

            ['atm_transaction_actions_id' => 11, 'sequence_no' => 1, 'user_group_id' => 31,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 11, 'sequence_no' => 2, 'user_group_id' => 54,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 11, 'sequence_no' => 3, 'user_group_id' => 31,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 11, 'sequence_no' => 4, 'user_group_id' => 21,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 11, 'sequence_no' => 5, 'user_group_id' => 32,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 11, 'sequence_no' => 6, 'user_group_id' => 32,'type' => 'Released','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 11, 'sequence_no' => 7, 'user_group_id' => 9,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 11, 'sequence_no' => 8, 'user_group_id' => 5,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],

            ['atm_transaction_actions_id' => 12, 'sequence_no' => 1, 'user_group_id' => 9,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 12, 'sequence_no' => 2, 'user_group_id' => 15,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 12, 'sequence_no' => 3, 'user_group_id' => 31,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 12, 'sequence_no' => 4, 'user_group_id' => 52,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],

            ['atm_transaction_actions_id' => 13, 'sequence_no' => 1, 'user_group_id' => 12,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 13, 'sequence_no' => 2, 'user_group_id' => 10,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 13, 'sequence_no' => 3, 'user_group_id' => 15,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 13, 'sequence_no' => 4, 'user_group_id' => 31,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 13, 'sequence_no' => 5, 'user_group_id' => 21,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 13, 'sequence_no' => 6, 'user_group_id' => 32,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 13, 'sequence_no' => 7, 'user_group_id' => 32,'type' => 'Released','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 13, 'sequence_no' => 8, 'user_group_id' => 9,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 13, 'sequence_no' => 9, 'user_group_id' => 5,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],

            ['atm_transaction_actions_id' => 14, 'sequence_no' => 1, 'user_group_id' => 9,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 14, 'sequence_no' => 2, 'user_group_id' => 25,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 14, 'sequence_no' => 3, 'user_group_id' => 30,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 14, 'sequence_no' => 4, 'user_group_id' => 15,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 14, 'sequence_no' => 5, 'user_group_id' => 31,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],

            ['atm_transaction_actions_id' => 15, 'sequence_no' => 1, 'user_group_id' => 9,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 15, 'sequence_no' => 2, 'user_group_id' => 15,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],

            ['atm_transaction_actions_id' => 16, 'sequence_no' => 1, 'user_group_id' => 9,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 16, 'sequence_no' => 2, 'user_group_id' => 15,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 16, 'sequence_no' => 3, 'user_group_id' => 31,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 16, 'sequence_no' => 4, 'user_group_id' => 54,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 16, 'sequence_no' => 5, 'user_group_id' => 31,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 16, 'sequence_no' => 6, 'user_group_id' => 21,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 16, 'sequence_no' => 7, 'user_group_id' => 32,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 16, 'sequence_no' => 8, 'user_group_id' => 32,'type' => 'Released','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 16, 'sequence_no' => 9, 'user_group_id' => 9,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 16, 'sequence_no' => 10, 'user_group_id' => 5,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],

            ['atm_transaction_actions_id' => 17, 'sequence_no' => 1, 'user_group_id' => 9,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 17, 'sequence_no' => 2, 'user_group_id' => 15,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 17, 'sequence_no' => 3, 'user_group_id' => 31,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 17, 'sequence_no' => 4, 'user_group_id' => 54,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],

            ['atm_transaction_actions_id' => 18, 'sequence_no' => 1, 'user_group_id' => 9,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 18, 'sequence_no' => 2, 'user_group_id' => 15,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 18, 'sequence_no' => 3, 'user_group_id' => 31,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 18, 'sequence_no' => 4, 'user_group_id' => 54,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],

            ['atm_transaction_actions_id' => 19, 'sequence_no' => 1, 'user_group_id' => 55,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 19, 'sequence_no' => 2, 'user_group_id' => 54,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 19, 'sequence_no' => 3, 'user_group_id' => 32,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 19, 'sequence_no' => 4, 'user_group_id' => 32,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 19, 'sequence_no' => 5, 'user_group_id' => 9,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 19, 'sequence_no' => 6, 'user_group_id' => 5,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],

            ['atm_transaction_actions_id' => 20, 'sequence_no' => 1, 'user_group_id' => 9,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 20, 'sequence_no' => 2, 'user_group_id' => 15,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 20, 'sequence_no' => 3, 'user_group_id' => 55,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],

            ['atm_transaction_actions_id' => 21, 'sequence_no' => 1, 'user_group_id' => 9,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 21, 'sequence_no' => 2, 'user_group_id' => 15,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 21, 'sequence_no' => 3, 'user_group_id' => 31,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 21, 'sequence_no' => 4, 'user_group_id' => 54,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],

            ['atm_transaction_actions_id' => 22, 'sequence_no' => 1, 'user_group_id' => 9,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 22, 'sequence_no' => 2, 'user_group_id' => 15,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 22, 'sequence_no' => 3, 'user_group_id' => 31,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 22, 'sequence_no' => 4, 'user_group_id' => 54,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],

            ['atm_transaction_actions_id' => 23, 'sequence_no' => 1, 'user_group_id' => 9,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 23, 'sequence_no' => 2, 'user_group_id' => 15,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 23, 'sequence_no' => 3, 'user_group_id' => 31,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['atm_transaction_actions_id' => 23, 'sequence_no' => 4, 'user_group_id' => 54,'type' => 'Received','created_at' => Carbon::now(),'updated_at' => Carbon::now()],


        ];
        DB::table('atm_transaction_sequences')->insert($data);
    }
}
