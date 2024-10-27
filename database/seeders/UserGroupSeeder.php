<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserGroupSeeder extends Seeder
{
    public function run()
    {
            $data = [
                    ['id' => 1, 'group_name' => 'Admin', 'company_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 2, 'group_name' => 'Everfirst Admin', 'company_id' => 2, 'created_at' => Carbon::now(),  'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 3, 'group_name' => 'Receiving Clerk', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 4, 'group_name' => 'Calderon HR', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 5, 'group_name' => 'Branch Head', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 6, 'group_name' => 'Area Supervisor', 'company_id' => 2, 'created_at' => Carbon::now(),  'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 7, 'group_name' => 'District Manager', 'company_id' => 2, 'created_at' => Carbon::now(),  'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 8, 'group_name' => 'Verifier', 'company_id' => 2, 'created_at' => Carbon::now(),  'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 9, 'group_name' => 'Rider', 'company_id' => 2, 'created_at' => Carbon::now(),  'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 10, 'group_name' => 'Accounting Supervisor', 'company_id' => 2, 'created_at' => Carbon::now(),  'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 11, 'group_name' => 'Operations Manager', 'company_id' => 2, 'created_at' => Carbon::now(),  'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 12, 'group_name' => 'Treasury Head', 'company_id' => 2, 'created_at' => Carbon::now(),  'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 13, 'group_name' => 'Treasury Receiving Clerk', 'company_id' => 2, 'created_at' => Carbon::now(),  'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 14, 'group_name' => 'Filling Clerk', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 15, 'group_name' => 'Collection Receiving Clerk', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 16, 'group_name' => 'Auditor Assistant', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 17, 'group_name' => 'Treasury Assistant/Staff', 'company_id' => 2, 'created_at' => Carbon::now(),  'updated_at' => Carbon::now(),'status' => 'Active'],
                    ['id' => 18, 'group_name' => 'Approving Officer', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 19, 'group_name' => 'Accounting Staff', 'company_id' => 2, 'created_at' => Carbon::now(),  'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 20, 'group_name' => 'Edit For new', 'company_id' => 2, 'created_at' => Carbon::now(),  'updated_at' => Carbon::now(), 'status' => 'Inactive'],
                    ['id' => 21, 'group_name' => 'Collection Head', 'company_id' => 2, 'created_at' => Carbon::now(),  'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 22, 'group_name' => 'Test', 'company_id' => 2, 'created_at' => Carbon::now(),  'updated_at' => Carbon::now(), 'status' => 'Inactive'],
                    ['id' => 23, 'group_name' => 'Test2', 'company_id' => 2, 'created_at' => Carbon::now(),  'updated_at' => Carbon::now(), 'status' => 'Inactive'],
                    ['id' => 24, 'group_name' => 'Test3', 'company_id' => 2, 'created_at' => Carbon::now(),  'updated_at' => Carbon::now(),'status' => 'Active'],
                    ['id' => 25, 'group_name' => 'AP/AR Staff', 'company_id' => 2, 'created_at' => Carbon::now(),  'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 26, 'group_name' => 'Payroll Clerk', 'company_id' => 2, 'created_at' => Carbon::now(),  'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 27, 'group_name' => 'Collection Department', 'company_id' => 2, 'created_at' => Carbon::now(),  'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 28, 'group_name' => 'Chief Accounting', 'company_id' => 2, 'created_at' => Carbon::now(),  'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 29, 'group_name' => 'Warehouse', 'company_id' => 2, 'created_at' => Carbon::now(),  'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 30, 'group_name' => 'Accounting / Report Staff', 'company_id' => 2, 'created_at' => Carbon::now(),  'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 31, 'group_name' => 'Collection Staff', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 32, 'group_name' => 'Collection Staff / Releasing', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 33, 'group_name' => 'Finance Manager', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 34, 'group_name' => 'Treasury Supervisor', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 35, 'group_name' => 'Treasury Assistant', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 36, 'group_name' => 'Treasury Staff', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 37, 'group_name' => 'Auditor Head', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 38, 'group_name' => 'Loan Processor', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 39, 'group_name' => 'General Accountant', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 40, 'group_name' => 'Collection / Reports Staff', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 41, 'group_name' => 'IT Staff', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 42, 'group_name' => 'Approving Head', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 43, 'group_name' => 'HR Manager', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 44, 'group_name' => 'Purchasing', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 45, 'group_name' => 'Treasury Rider', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 46, 'group_name' => 'Treasury (Multi-Line)', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 47, 'group_name' => 'Admin test 1 Group', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 48, 'group_name' => 'Admin Test 2 Group', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 49, 'group_name' => 'Training Manager', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 50, 'group_name' => 'Loan Consultant', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 51, 'group_name' => 'Assistant Trainer', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 52, 'group_name' => 'Collection Custodian', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 53, 'group_name' => 'Collection Supervisor', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 54, 'group_name' => 'Checker', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 55, 'group_name' => 'Passbook Custodian', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                    ['id' => 56, 'group_name' => 'Developer', 'company_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'status' => 'Active'],
                ];

            DB::table('data_user_groups')->insert($data);
    }
}
