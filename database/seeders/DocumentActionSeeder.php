<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class DocumentActionSeeder extends Seeder
{
    public function run()
    {
        $datas =
        [
            [1,  'Reinforcement Fund (Request)',     1, 1, 2, 2, 4, 'Active'],
            [2,  'Printer OR (Official Receipt)',    1, 1, 2, 3, 1, 'Active'],
            [3,  'LC Original Copy of AFCR',         1, 1, 2, 3, 4, 'Active'],
            [4,  'ATM / Passbook Released paper',    1, 1, 4, 2, 1, 'Active'],
            [5,  'ATM / Passbook Request',           1, 1, 2, 2, 1, 'Active'],
            [6,  'Disposal Form',                    1, 1, 2, 2, 1, 'Active'],
            [7,  'RPO For TCA/PCA',                  1, 1, 2, 2, 1, 'Active'],
            [8,  'Documents for Filling Clerk',      1, 1, 2, 2, 1, 'Active'],
            [9,  'Billing / RPO Utilities / Rental', 1, 1, 2, 2, 4, 'Active'],
            [10, 'ATM / Passbook Borrow',            1, 1, 5, 3, 1, 'Active'],
            [11, 'Passbook Collection',              1, 1, 3, 3, 3, 'Active'],
            [12, 'ATM / Passbook New Client',        1, 1, 6, 2, 3, 'Active'],
            [13, 'ATM / Passbook Released Paper',    1, 1, 7, 2, 1, 'Active'],
            [14, 'ATM / Passbook Cancelled Loan',    1, 1, 2, 2, 1, 'Active'],
            [15, 'Folder For Write-Off',             1, 1, 2, 2, 1, 'Active'],
            [16, 'Petty Cash, Cash Referral Reward or Motorycle Fund ( Liquidation of TCA )', 1, 1, 2, 2, 4, 'Active'],
            [17, 'RPO of Petty Cash Fund ( Replenishment or Request of Fund on Additional Fund', 1, 1, 2, 2, 4, 'Active'],
            [18, 'Replenishment of Cash Fund',       1, 1, 2, 2, 1, 'Active'],
            [19, 'LC Incentive',                     1, 1, 2, 2, 4, 'Active'],
            [20, 'Additional LC Incentive',          1, 1, 2, 2, 4, 'Active'],
            [21, 'Revolving Fund (Paycard)',         1, 1, 2, 2, 4, 'Active'],
            [22, 'Revolving Fund (Cheque)',          1, 1, 2, 2, 4, 'Active'],
            [23, 'Reinforcement Fund (Paycard)',     1, 1, 2, 2, 1, 'Active'],
            [24, 'Reinforcement Fund (Cheque)',      1, 1, 2, 2, 4, 'Active'],
            [25, 'Other RPO for Disbursements',      1, 1, 2, 2, 1, 'Active'],
            [26, 'Salary Upgrade',                   1, 1, 2, 2, 1, 'Active'],
            [27, 'Bank Documents',                   1, 1, 2, 3, 1, 'Active'],
            [28, 'Checkbook Request',                1, 1, 2, 3, 1, 'Active'],
            [29, 'Check Booklet',                    1, 1, 2, 4, 1, 'Active'],
            [30, 'Fund Transfer CV and Deposit Slip',1, 1, 2, 3, 1, 'Active'],
            [31, 'Cancelled Process',                1, 1, 2, 4, 1, 'Active'],
            [32, 'Utilities (HO Printing)',          1, 1, 2, 3, 1, 'Active'],
            [33, 'Voucher Payable Filing',           1, 1, 2, 3, 1, 'Active'],
            [34, 'Document for HR',                  1, 1, 2, 4, 1, 'Active'],
            [35, 'Payable in Multi-Line',            1, 1, 2, 4, 1, 'Active'],
            [36, 'Documents for Purchasing',         1, 1, 2, 3, 1, 'Active'],
            [37, 'Acknowledgement Receipt',          1, 1, 2, 4, 1, 'Active'],
            [38, 'ADA Forms',                        1, 1, 8, 2, 3, 'Active'],
            [39, 'CASH /PASSBOOK COLLECTION',        1, 1, 9, 2, 1, 'Active'],
            [40, 'Quit Claim Waiver',                1, 1, 2, 2, 1, 'Active'],
            [41, 'Cancelled ATM',                    1, 1, 2, 2, 1, 'Active'],
            [42, 'Cancelled Temporary Receipt',      1, 1, 2, 2, 1, 'Active'],
            [43, 'REQUEST ATM/PB',                   1, 1, 2, 2, 1, 'Active'],
            [44, 'ATM Passbook',                     1, 1, 2, 2, 1, 'Active'],
            [45, 'Reinforcement Fund',               1, 1, 2, 2, 4, 'Active'],
            [46, 'LC AFCR For Signing',              1, 1, 2, 2, 1, 'Active'],
            [47, 'Evaluation Form For Employee',     1, 1, 2, 2, 1, 'Active'],
            [48, 'Document',                         1, 1, 2, 2, 1, 'Active'],
            [49, 'Replenishment of Revolving Fund',  1, 1, 2, 4, 1, 'Active'],
            [50, 'Replenishment of Petty Cash Fund', 1, 1, 2, 4, 1, 'Active'],
            [51, 'Replenishment of Cash Referral',   1, 1, 2, 4, 1, 'Active'],
            [52, 'Cancelled DSB',                    1, 1, 2, 4, 1, 'Active'],
            [53, 'CV & Receipts for Bills',          1, 1, 2, 4, 1, 'Active'],
            [54, 'CV for Loan Release',              1, 1, 2, 4, 1, 'Active'],
            [55, 'CV for Check Change',              1, 1, 2, 4, 1, 'Active'],
            [56, 'Yellow Copy of Release Paper',     1, 1, 2, 4, 1, 'Active'],
            [57, 'Employee Performance Evaluation',  1, 1, 2, 2, 1, 'Active'],
            [58, 'Employee Performance Evaluation',  1, 1, 2, 2, 1, 'Active'],
            [59, 'Employee Performance Evaluation',  1, 1, 2, 2, 1, 'Active'],
            [60, 'Cash Referral',                    1, 1, 2, 2, 2, 'Active'],
            [61, 'Health Declaration Form',          1, 1, 2, 2, 1, 'Active'],
            [62, 'OM',                               1, 1, 2, 2, 1, 'Active'],
            [63, 'Loan Release (HO Cheque Printing)',  1, 1, 2, 2, 1, 'Active'],
            [64, 'Loan Release ( Cheque Writing )',    1, 1, 2, 2, 1, 'Active'],
            [65, 'Loan Release (Branch Cheque Printing)', 1, 1, 2, 2, 1, 'Active'],
            [66, 'Check Change (HO Cheque Printing)', 1, 1, 2, 4, 1, 'Active'],
            [67, 'Check Change ( Cheque Writing) ',   1, 1, 2, 4, 1, 'Active'],
            [68, 'Check Change (Branch Cheque Printing)', 1, 1, 2, 4, 1, 'Active'],
            [69, 'Cash Change',  1, 1, 2, 4, 1, 'Active'],
            [70, 'Cancelled DSB',                    1, 1, 2, 2, 1, 'Active'],
            [71, 'Liquidation (TCA, PCA, Advances to Supplier and Other Advances)', 1, 1, 2, 2, 1, 'Active'],
            [72, 'Returned Ada Form',                1, 1, 2, 3, 1, 'Active'],
            [73, 'ATM /PB REPLACEMENT',              1, 1, 10, 2, 1, 'Active'],
            [74, 'Request TR Form Pad',              1, 1, 2, 3, 1, 'Active'],
            [75, 'Request ATM / PB Released Form Pad', 1, 1, 2, 3, 1, 'Active'],
            [76, 'Cancelled Checked',                     1, 1, 2, 2, 1, 'Active'],
            [77, 'Cancelled Checked (Head Office Process)', 1, 1, 2, 4, 1, 'Active'],
            [78, 'Sample Back-Out/Apply As Payment',      1, 1, 2, 2, 1, 'Active'],
            [79, 'CV for Check Writing',                  1, 1, 2, 2, 1, 'Active'],
            [80, 'Sales Invoice and Delivery Receipt From Supplier', 1, 1, 2, 4, 1, 'Active'],
            [81, 'Return Other Document',                 1, 1, 2, 3, 1, 'Active'],
            [82, 'Return Other Document (Branch)',        1, 1, 2, 2, 1, 'Active'],
            [83, 'Official Receipts Monitoring (Head Office)', 1, 1, 2, 3, 1, 'Active'],
            [84, 'Return of Folder For Write-Off',        1, 1, 2, 3, 1, 'Active'],
            [85, 'Deletion in LMS',                       1, 1, 2, 4, 1, 'Active'],
            [86, 'Official Receipts Monitoring',          2, 1, 2, 2, 1, 'Active'],
            [87, 'Official Receipts Monitoring (Branch)', 1, 1, 2, 2, 1, 'Active'],
            [88, 'IT Equipments (Replacement / Disposal)',1, 1, 11, 2, 1, 'Active'],
        ];

        foreach ($datas as $data) {
            DB::table('documents_actions')->insert([
                'id' => $data[0],
                'document' => $data[1],
                'sequence_type' => $data[2],
                'return_no' => $data[3],
                'option_type' => $data[4],
                'document_session' => $data[5],
                'department_code' => $data[6],
                'status' => $data[7],
                'created_at' => Carbon::now(), // Use the current date for created_at
                'updated_at' => Carbon::now(), // Use the current date for updated_at
            ]);
        }
    }
}
