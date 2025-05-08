<?php

namespace Database\Seeders\ElogSeeder;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AtmTrasanctionSequenceSeeder extends Seeder
{
    public function run()
    {
        $actions = [
            [1, 1, 31, 'Received','', '',],
            [1, 2, 54, 'Received' ,'', '',],
            [1, 3, 31, 'Received' ,'', '',],
            [1, 4, 21, 'Received' ,'', '',],
            [1, 5, 32, 'Received' ,'', '',],
            [1, 6, 32, 'Released' ,'', '',],
            [1, 7, 9,  'Received' ,'', '',],
            [1, 8, 5,  'Received' ,'', '',],

            [3, 1, 9,   'Received' ,'', '',],
            [3, 2, 25,  'Received' ,'', '',],
            [3, 3, 30,  'Received' ,'', '',],
            [3, 4, 15,  'Received' ,'', '',],
            [3, 5, 31,  'Received' ,'', '',],
            [3, 6, 54,  'Received' ,'', '',],
            [3, 7, 31,  'Received' ,'', '',],
            [3, 8, 21,  'Received' ,'', '',],
            [3, 9, 32,  'Received' ,'', '',],
            [3, 10, 32, 'Released' ,'', '',],
            [3, 11, 9,  'Received' ,'', '',],
            [3, 12, 5,  'Received' ,'', '',],

            [4, 1, 9,  'Received' ,'', '',],
            [4, 2, 15, 'Received' ,'', '',],
            [4, 3, 31, 'Released' ,'', '',],
            [4, 4, 54, 'Received' ,'', '',],

            [5, 1, 9,  'Received' ,'', '',],
            [5, 2, 15, 'Received' ,'', '',],
            [5, 3, 31, 'Received' ,'', '',],
            [5, 4, 54, 'Received' ,'', '',],

            [6, 1, 9,  'Received' ,'', '',],
            [6, 2, 15, 'Received' ,'', '',],
            [6, 3, 52, 'Received' ,'', '',],

            [7, 1, 9,  'Received' ,'', '',],
            [7, 2, 15, 'Received' ,'', '',],
            [7, 3, 31, 'Received' ,'', '',],
            [7, 4, 54, 'Received' ,'', '',],

            [8, 1, 9, 'Received' ,'', '',],
            [8, 2, 15, 'Received' ,'', '',],
            [8, 3, 31, 'Received' ,'', '',],

            [9, 1, 52, 'Received' ,'', '',],
            [9, 2, 53, 'Received' ,'', '',],
            [9, 3, 32, 'Received' ,'', '',],
            [9, 4, 32, 'Released' ,'', '',],
            [9, 5, 9,  'Received' ,'', '',],
            [9, 6, 31, 'Received' ,'', '',],

            [11, 1, 31, 'Received' ,'', '',],
            [11, 2, 54, 'Received' ,'', '',],
            [11, 3, 31, 'Received' ,'', '',],
            [11, 4, 21, 'Received' ,'', '',],
            [11, 5, 32, 'Received' ,'', '',],
            [11, 6, 32, 'Released' ,'', '',],
            [11, 7, 9,  'Received' ,'', '',],
            [11, 8, 5,  'Received' ,'', '',],

            [12, 1, 9,  'Received' ,'', '',],
            [12, 2, 15, 'Received' ,'', '',],
            [12, 3, 31, 'Received' ,'', '',],
            [12, 4, 52, 'Received' ,'', '',],

            [13, 1, 12, 'Received' ,'', '',],
            [13, 2, 10, 'Received' ,'', '',],
            [13, 3, 15, 'Received' ,'', '',],
            [13, 4, 31, 'Received' ,'', '',],
            [13, 5, 21, 'Received' ,'', '',],
            [13, 6, 32, 'Received' ,'', '',],
            [13, 7, 32, 'Released' ,'', '',],
            [13, 8, 9,  'Received' ,'', '',],
            [13, 9, 5,  'Received' ,'', '',],

            [14, 1, 9,  'Received' ,'', '',],
            [14, 2, 25, 'Received' ,'', '',],
            [14, 3, 30, 'Received' ,'', '',],
            [14, 4, 15, 'Received' ,'', '',],
            [14, 5, 31, 'Received' ,'', '',],

            [15, 1, 9,  'Received' ,'', '',],
            [15, 2, 15, 'Received' ,'', '',],

            [16, 1, 9,  'Received' ,'', '',],
            [16, 2, 15, 'Received' ,'', '',],
            [16, 3, 31, 'Received' ,'', '',],
            [16, 4, 54, 'Received' ,'', '',],
            [16, 5, 31, 'Received' ,'', '',],
            [16, 6, 21, 'Received' ,'', '',],
            [16, 7, 32, 'Received' ,'', '',],
            [16, 8, 32, 'Released' ,'', '',],
            [16, 9, 9,  'Received' ,'', '',],
            [16, 10, 5, 'Received' ,'', '',],

            [17, 1, 9,  'Received' ,'', '',],
            [17, 2, 15, 'Received' ,'', '',],
            [17, 3, 31, 'Received' ,'', '',],
            [17, 4, 54, 'Received' ,'', '',],

            [18, 1, 9,  'Received' ,'', '',],
            [18, 2, 15, 'Received' ,'', '',],
            [18, 3, 31, 'Received' ,'', '',],
            [18, 4, 54, 'Received' ,'', '',],

            [19, 1, 55, 'Received' ,'', '',],
            [19, 2, 54, 'Received' ,'', '',],
            [19, 3, 32, 'Received' ,'', '',],
            [19, 4, 32, 'Released' ,'', '',],
            [19, 5, 9,  'Received' ,'', '',],
            [19, 6, 5,  'Received' ,'', '',],

            [20, 1, 9,  'Received' ,'', '',],
            [20, 2, 15, 'Received' ,'', '',],
            [20, 3, 55, 'Received' ,'', '',],

            [21, 1, 9,  'Received' ,'', '',],
            [21, 2, 15, 'Received' ,'', '',],
            [21, 3, 31, 'Received' ,'', '',],
            [21, 4, 54, 'Received' ,'', '',],

            [22, 1, 9,  'Received' ,'', '',],
            [22, 2, 15, 'Received' ,'', '',],
            [22, 3, 31, 'Received' ,'', '',],
            [22, 4, 54, 'Received' ,'', '',],

            [23, 1, 9,  'Received' ,'', '',],
            [23, 2, 15, 'Received' ,'', '',],
            [23, 3, 31, 'Received' ,'', '',],
            [23, 4, 54, 'Received' ,'', '',],

            [24, 1, 54, 'Received' ,'', 'Collection'],
            [24, 2, 52, 'Received' ,'', 'Collection'],
            [24, 3, 53, 'Received' ,'', 'Collection'],
            [24, 4, 58, 'Received' ,'', 'Collection'],
            [24, 5, 58, 'Received' ,'', 'Collection'],
            [24, 6, 53, 'Received' ,'', 'Collection'],
            [24, 7, 31, 'Received' ,'', 'Collection'],
            [24, 8, 54, 'Received' ,'Can be received by 52(Collection Custodian)', 'Collection'],

            [24, 1, 53, 'Received' ,'', 'Custodian'],
            [24, 2, 58, 'Received' ,'', 'Custodian'],
            [24, 3, 58, 'Received' ,'', 'Custodian'],
            [24, 4, 53, 'Received' ,'', 'Custodian'],
            [24, 5, 52, 'Received' ,'', 'Custodian'],

            [25, 1, 54, 'Received' ,'', 'Collection'],
            [25, 2, 52, 'Received' ,'', 'Collection'],
            [25, 3, 53, 'Received' ,'', 'Collection'],
            [25, 4, 58, 'Received' ,'', 'Collection'],
            [25, 5, 58, 'Received' ,'', 'Collection'],
            [25, 6, 53, 'Received' ,'', 'Collection'],
            [25, 7, 31, 'Received' ,'', 'Collection'],
            [25, 8, 54, 'Received' ,'Can be received by 52(Collection Custodian)', 'Collection'],

            [25, 1, 53, 'Received' ,'', 'Custodian'],
            [25, 2, 58, 'Received' ,'', 'Custodian'],
            [25, 3, 58, 'Received' ,'', 'Custodian'],
            [25, 4, 53, 'Received' ,'', 'Custodian'],
            [25, 5, 52, 'Received' ,'', 'Custodian'],

            [26, 1, 54, 'Received' ,'', 'Collection'],
            [26, 2, 52, 'Received' ,'', 'Collection'],
            [26, 3, 53, 'Received' ,'', 'Collection'],
            [26, 4, 58, 'Received' ,'', 'Collection'],
            [26, 5, 58, 'Received' ,'', 'Collection'],
            [26, 6, 53, 'Received' ,'Can be received by 52(Collection Custodian)	', 'Collection'],
            [26, 7, 31, 'Received' ,'', 'Collection'],
            [26, 8, 54, 'Received' ,'Can be received by 52(Collection Custodian)', 'Collection'],

            [26, 1, 53, 'Received' ,'', 'Custodian'],
            [26, 2, 58, 'Received' ,'', 'Custodian'],
            [26, 3, 58, 'Received' ,'', 'Custodian'],
            [26, 4, 53, 'Received' ,'', 'Custodian'],
            [26, 5, 52, 'Received' ,'', 'Custodian'],

            [27, 1, 54, 'Received' ,'', 'Collection'],
            [27, 2, 52, 'Received' ,'', 'Collection'],
            [27, 3, 53, 'Received' ,'', 'Collection'],
            [27, 4, 58, 'Received' ,'', 'Collection'],
            [27, 5, 58, 'Received' ,'', 'Collection'],
            [27, 6, 53, 'Received' ,'', 'Collection'],
            [27, 7, 31, 'Received' ,'', 'Collection'],
            [27, 8, 54, 'Received' ,'Can be received by 52(Collection Custodian)', 'Collection'],

            [27, 1, 53, 'Received' ,'', 'Custodian'],
            [27, 2, 58, 'Received' ,'', 'Custodian'],
            [27, 3, 58, 'Received' ,'', 'Custodian'],
            [27, 4, 53, 'Received' ,'', 'Custodian'],
            [27, 5, 55, 'Received' ,'', 'Custodian'],

            [28, 1, 54, 'Received' ,'', 'Collection'],
            [28, 2, 52, 'Received' ,'', 'Collection'],
            [28, 3, 53, 'Received' ,'', 'Collection'],
            [28, 4, 58, 'Received' ,'', 'Collection'],
            [28, 5, 58, 'Received' ,'', 'Collection'],
            [28, 6, 53, 'Received' ,'', 'Collection'],
            [28, 7, 31, 'Received' ,'', 'Collection'],
            [28, 8, 54, 'Received' ,'', 'Collection'],

            [28, 1, 53, 'Received' ,'', 'Custodian'],
            [28, 2, 58, 'Received' ,'', 'Custodian'],
            [28, 3, 58, 'Received' ,'', 'Custodian'],
            [28, 4, 53, 'Received' ,'', 'Custodian'],
            [28, 5, 55, 'Received' ,'', 'Custodian'],

            [29, 1, 54, 'Received' ,'', 'Collection'],
            [29, 2, 52, 'Received' ,'', 'Collection'],
            [29, 3, 53, 'Received' ,'', 'Collection'],
            [29, 4, 58, 'Received' ,'', 'Collection'],
            [29, 5, 58, 'Received' ,'', 'Collection'],
            [29, 6, 53, 'Received' ,'', 'Collection'],
            [29, 7, 31, 'Received' ,'', 'Collection'],
            [29, 8, 54, 'Received' ,'', 'Collection'],

            [29, 1, 53, 'Received' ,'', 'Custodian'],
            [29, 2, 58, 'Received' ,'', 'Custodian'],
            [29, 3, 58, 'Received' ,'', 'Custodian'],
            [29, 4, 53, 'Received' ,'', 'Custodian'],
            [29, 5, 55, 'Received' ,'', 'Custodian'],

            [30, 1, 54, 'Received' ,'', 'Collection'],
            [30, 2, 52, 'Received' ,'', 'Collection'],
            [30, 3, 53, 'Received' ,'', 'Collection'],
            [30, 4, 58, 'Received' ,'', 'Collection'],
            [30, 5, 58, 'Received' ,'', 'Collection'],
            [30, 6, 53, 'Received' ,'', 'Collection'],
            [30, 7, 31, 'Received' ,'', 'Collection'],
            [30, 8, 54, 'Received' ,'Can be received by 52(Collection Custodian)', 'Collection'],

            [30, 1, 53, 'Received' ,'', 'Custodian'],
            [30, 2, 58, 'Received' ,'', 'Custodian'],
            [30, 3, 58, 'Received' ,'', 'Custodian'],
            [30, 4, 53, 'Received' ,'', 'Custodian'],
            [30, 5, 52, 'Received' ,'', 'Custodian'],

            [31, 1, 54, 'Received' ,'', 'Collection'],
            [31, 2, 52, 'Received' ,'', 'Collection'],
            [31, 3, 53, 'Received' ,'', 'Collection'],
            [31, 4, 58, 'Received' ,'', 'Collection'],
            [31, 5, 58, 'Received' ,'', 'Collection'],
            [31, 6, 53, 'Received' ,'', 'Collection'],
            [31, 7, 31, 'Received' ,'', 'Collection'],
            [31, 8, 54, 'Received' ,'Can be received by 52(Collection Custodian)', 'Collection'],

            [31, 1, 53, 'Received' ,'', 'Custodian'],
            [31, 2, 58, 'Received' ,'', 'Custodian'],
            [31, 3, 58, 'Received' ,'', 'Custodian'],
            [31, 4, 53, 'Received' ,'', 'Custodian'],
            [31, 5, 52, 'Received' ,'', 'Custodian'],

            [32, 1, 54, 'Received' ,'', 'Collection'],
            [32, 2, 52, 'Received' ,'', 'Collection'],
            [32, 3, 53, 'Received' ,'', 'Collection'],
            [32, 4, 58, 'Received' ,'', 'Collection'],
            [32, 5, 58, 'Received' ,'', 'Collection'],
            [32, 6, 53, 'Received' ,'', 'Collection'],
            [32, 7, 31, 'Received' ,'', 'Collection'],
            [32, 8, 54, 'Received' ,'Can be received by 52(Collection Custodian)', 'Collection'],

            [32, 1, 53, 'Received' ,'', 'Custodian'],
            [32, 2, 58, 'Received' ,'', 'Custodian'],
            [32, 3, 58, 'Received' ,'', 'Custodian'],
            [32, 4, 53, 'Received' ,'', 'Custodian'],
            [32, 5, 52, 'Received' ,'', 'Custodian'],

            [33, 1, 54, 'Received' ,'', 'Collection'],
            [33, 2, 52, 'Received' ,'', 'Collection'],
            [33, 3, 53, 'Received' ,'', 'Collection'],
            [33, 4, 58, 'Received' ,'', 'Collection'],
            [33, 5, 58, 'Received' ,'', 'Collection'],
            [33, 6, 53, 'Received' ,'', 'Collection'],
            [33, 7, 31, 'Received' ,'', 'Collection'],
            [33, 8, 54, 'Received' ,'', 'Collection'],

            [33, 1, 53, 'Received' ,'', 'Custodian'],
            [33, 2, 58, 'Received' ,'', 'Custodian'],
            [33, 3, 58, 'Received' ,'', 'Custodian'],
            [33, 4, 53, 'Received' ,'', 'Custodian'],
            [33, 5, 52, 'Received' ,'', 'Custodian'],

            [34, 1, 54, 'Received' ,'', 'Collection'],
            [34, 2, 52, 'Received' ,'', 'Collection'],
            [34, 3, 53, 'Received' ,'', 'Collection'],
            [34, 4, 58, 'Received' ,'', 'Collection'],
            [34, 5, 58, 'Received' ,'', 'Collection'],
            [34, 6, 53, 'Received' ,'', 'Collection'],
            [34, 7, 31, 'Received' ,'', 'Collection'],
            [34, 8, 54, 'Received' ,'', 'Collection'],

            [34, 1, 53, 'Received' ,'', 'Custodian'],
            [34, 2, 58, 'Received' ,'', 'Custodian'],
            [34, 3, 58, 'Received' ,'', 'Custodian'],
            [34, 4, 53, 'Received' ,'', 'Custodian'],
            [34, 5, 52, 'Received' ,'', 'Custodian'],


        ];

        foreach ($actions as $action) {
            DB::table('elm_transaction_sequences')->insert([
                'action_id' => $action[0],
                'sequence_no' => $action[1],
                'user_group_id' => $action[2],
                'type' => $action[3],
                'remarks' => $action[4],
                'oc_receiver_type' => $action[5],
                'created_at' => Carbon::now(), // Use the current date for updated_at
                'updated_at' => Carbon::now(), // Use the current date for updated_at
            ]);
        }
    }
}
