<?php

namespace Database\Seeders\SettingsSeeder;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DocumentsActionSequenceSeeder extends Seeder
{
    public function run()
    {
        $datas =
        [
            // Reinforcement Fund (Request)
            [1, 1, 10, 'Received'],
            [1, 2, 17, 'Received'],
            [1, 3, 12, 'Received'],
            [1, 4, 18, 'Received'],
            [1, 5, 17, 'Received'],
            [1, 6, 5, 'Received'],

            // Printer OR (Official Receipt)
            [2, 1, 5, 'Received'],

            // LC Original Copy of AFCR
            [3, 1, 10, 'Received'],
            [3, 2, 5, 'Received'],

            // ATM / Passbook Released paper (Finished Client)
            [4, 1, 25, 'Received'],
            [4, 2, 30, 'Received'],
            [4, 3, 15, 'Received'],
            [4, 4, 31, 'Received'],
            [4, 5, 32, 'Received'],
            [4, 6, 5, 'Received'],
            [4, 7, 15, 'Received'],
            [4, 8, 31, 'Received'],

            // ATM / Passbook Request (Cancelled Loan)
            [5, 1, 12, 'Received'],
            [5, 2, 25, 'Received'],
            [5, 3, 30, 'Received'],
            [5, 4, 31, 'Received'],
            [5, 5, 32, 'Received'],
            [5, 6, 5, 'Received'],

            // Disposal Form
            [6, 1, 6, 'Received'],
            [6, 2, 7, 'Received'],
            [6, 3, 30, 'Received'],
            [6, 4, 33, 'Received'],
            [6, 5, 5, 'Received'],

            // RPO For TCA/PCA
            [7, 1, 10, 'Received'],
            [7, 2, 17, 'Received'],
            [7, 3, 12, 'Received'],
            [7, 4, 18, 'Received'],
            [7, 5, 17, 'Received'],
            [7, 6, 5, 'Received'],

            // Documents for Filling Clerk
            [8, 1, 14, 'Received'],

            // Billing / RPO Utilities / Rental
            [9, 1, 10, 'Received'],
            [9, 2, 17, 'Received'],
            [9, 3, 12, 'Received'],
            [9, 4, 18, 'Received'],
            [9, 5, 17, 'Received'],
            [9, 6, 5, 'Received'],

            // ATM / Passbook Borrow
            [10, 1, 32, 'Received'],
            [10, 2, 5, 'Received'],
            [10, 3, 15, 'Received'],
            [10, 4, 31, 'Received'],

            // Passbook Collection
            [11, 1, 32, 'Received'],
            [11, 2, 5, 'Received'],
            [11, 3, 15, 'Received'],
            [11, 4, 31, 'Received'],

            // ATM / Passbook New Balik Loob Client
            [12, 1, 15, 'Received'],
            [12, 2, 31, 'Received'],

            // ATM / Passbook Released Paper for Safekeeping
            [13, 1, 15, 'Received'],
            [13, 2, 31, 'Received'],
            [13, 3, 32, 'Received'],
            [13, 4, 5, 'Received'],
            [13, 5, 15, 'Received'],
            [13, 6, 31, 'Received'],

            // ATM / Passbook Cancelled Loan
            [14, 1, 12, 'Received'],
            [14, 2, 25, 'Received'],

            // Folder for Write Off
            [15, 1, 6, 'Received'],
            [15, 2, 7, 'Received'],
            [15, 3, 10, 'Received'],
            [15, 4, 16, 'Received'],
            [15, 5, 3, 'Received'],
            [15, 6, 16, 'Received'],
            [15, 7, 30, 'Received'],

            // Petty Cash, Cash Referral Reward or Motorcycle Fund (Liquidation or TCA)
            [16, 1, 17, 'Received'],
            [16, 2, 12, 'Received'],
            [16, 3, 10, 'Received'],
            [16, 4, 25, 'Received'],

            // RPO of Petty Cash Fund (Replenishment or Request of Fund on Additional Fund)
            [17, 1, 10, 'Received'],
            [17, 2, 17, 'Received'],
            [17, 3, 12, 'Received'],
            [17, 4, 18, 'Received'],
            [17, 5, 17, 'Received'],
            [17, 6, 5, 'Received'],

            // Replenishment of Cash Referral Fund
            [18, 1, 10, 'Received'],
            [18, 2, 17, 'Received'],
            [18, 3, 12, 'Received'],
            [18, 4, 18, 'Received'],
            [18, 5, 17, 'Received'],
            [18, 6, 5, 'Received'],

            // LC Incentives
            [19, 1, 10, 'Received'],

            // Additional LC Incentives ( RPO )
            [20, 1, 10, 'Received'],
            [20, 2, 17, 'Received'],
            [20, 3, 12, 'Received'],
            [20, 4, 18, 'Received'],
            [20, 5, 17, 'Received'],
            [20, 6, 5, 'Received'],

            // Revolving Fund (Replenishment Thru Paycard)
            [21, 1, 10, 'Received'],
            [21, 2, 17, 'Received'],
            [21, 3, 10, 'Received'],
            [21, 4, 17, 'Received'],
            [21, 5, 12, 'Received'],
            [21, 6, 18, 'Received'],
            [21, 7, 17, 'Received'],

            // Revolving Fund ( Replenishment Thru Cheque)
            [22, 1, 10, 'Received'],
            [22, 2, 17, 'Received'],
            [22, 3, 12, 'Received'],
            [22, 4, 18, 'Received'],
            [22, 5, 17, 'Received'],
            [22, 6, 5, 'Received'],

            // Reinforcement Fund (Request Thru Paycard)
            [23, 1, 10, 'Received'],
            [23, 2, 17, 'Received'],
            [23, 3, 10, 'Received'],
            [23, 4, 17, 'Received'],
            [23, 5, 12, 'Received'],
            [23, 6, 18, 'Received'],
            [23, 7, 17, 'Received'],

            // Reinforcement Fund (Request Thru Cheque)
            [24, 1, 10, 'Received'],
            [24, 2, 17, 'Received'],
            [24, 3, 12, 'Received'],
            [24, 4, 18, 'Received'],
            [24, 5, 17, 'Received'],
            [24, 6, 5, 'Received'],

            // Other RPO for Disbursements
            [25, 1, 10, 'Received'],
            [25, 2, 17, 'Received'],
            [25, 3, 12, 'Received'],
            [25, 4, 18, 'Received'],
            [25, 5, 17, 'Received'],
            [25, 6, 5, 'Received'],

            // Salary Upgrade
            [26, 1, 6, 'Received'],
            [26, 2, 7, 'Received'],
            [26, 3, 10, 'Received'],
            [26, 4, 37, 'Received'],
            [26, 5, 3, 'Received'],
            [26, 6, 43, 'Received'],

            // Bank Documents
            [27, 1, 5, 'Received'],

            // Checkbook Request
            [28, 1, 17, 'Received'],
            [28, 2, 5, 'Received'],

            // Check Booklet
            [29, 1, 17, 'Received'],
            [29, 2, 5, 'Received'],

            // Fund Transfer CV and Deposit Slip
            [30, 1, 17, 'Received'],
            [30, 2, 25, 'Received'],

            // Cancelled Process
            [31, 1, 17, 'Received'],
            [31, 2, 12, 'Received'],
            [31, 3, 41, 'Received'],
            [31, 4, 25, 'Received'],

            // Utilities (HO Printing)
            [32, 1, 18, 'Received'],
            [32, 2, 10, 'Received'],
            [32, 3, 17, 'Received'],
            [32, 4, 12, 'Received'],
            [32, 5, 18, 'Received'],
            [32, 6, 17, 'Received'],
            [32, 7, 5, 'Received'],

            // Voucher Payable Filing
            [33, 1, 17, 'Received'],
            [33, 2, 24, 'Received'],

            // Document for HR
            [34, 1, 12, 'Received'],
            [34, 2, 45, 'Received'],
            [34, 3, 43, 'Received'],

            // Payable in Multi-line
            [35, 1, 17, 'Received'],
            [35, 2, 45, 'Received'],
            [35, 3, 46, 'Received'],

            // Documents for Purchasing
            [36, 1, 17, 'Received'],
            [36, 2, 45, 'Received'],
            [36, 3, 44, 'Received'],

            // Acknowledgement Receipt, CV and Deposit Slip
            [37, 1, 17, 'Received'],
            [37, 2, 25, 'Received'],

            // ADA Forms
            [38, 1, 15, 'Received'],
            [38, 2, 31, 'Received'],

            // CASH PASSBOOK COLLECTION ATTACHMENT
            [39, 1, 15, 'Received'],
            [39, 2, 31, 'Received'],

            // Quit Claim Waiver
            [40, 1, 15, 'Received'],
            [40, 2, 31, 'Received'],

            // Cancelled ATM / Passbook Released Form
            [41, 1, 15, 'Received'],
            [41, 2, 31, 'Received'],

            // Cancelled Temporary Receipt
            [42, 1, 15, 'Received'],
            [42, 2, 31, 'Received'],

            // REQUEST ATM/PB CANCELLED LOAN
            [43, 1, 12, 'Received'],
            [43, 2, 30, 'Received'],
            [43, 3, 15, 'Received'],
            [43, 4, 31, 'Received'],
            [43, 5, 32, 'Received'],
            [43, 6, 35, 'Received'],
            [43, 7, 15, 'Received'],

            // ATM Passbook
            [44, 1, 15, 'Received'],

            // Reinforcement Fund (Liquidation)
            [45, 1, 10, 'Received'],

            // LC AFCR For Signing
            [46, 1, 6, 'Received'],
            [46, 2, 5, 'Received'],

            // Evaluation Form For Employee
            [47, 1, 10, 'Received'],
            [47, 2, 31, 'Received'],
            [47, 3, 17, 'Received'],
            [47, 4, 6, 'Received'],
            [47, 5, 7, 'Received'],
            [47, 6, 11, 'Received'],
            [47, 7, 4, 'Received'],

            // Document
            [48, 1, 16, 'Received'],
            [48, 2, 37, 'Received'],

            // Replenishment of Revolving Fund / Reinforcement Fund (For Audit Purpose)
            [49, 1, 16, 'Received'],
            [49, 2, 14, 'Received'],

            // Replenishment of Petty Cash Fund (For Audit Purpose)
            [50, 1, 16, 'Received'],
            [50, 2, 14, 'Received'],

            // Replenishment of Cash Referral Reward  (For Audit Purpose)
            [51, 1, 16, 'Received'],
            [51, 2, 14, 'Received'],

            // Cancelled DSB  (For Audit Purpose)
            [52, 1, 16, 'Received'],
            [52, 2, 14, 'Received'],

            // CV & Receipts for Bills & Rentals  (For Audit Purpose)
            [53, 1, 16, 'Received'],
            [53, 2, 14, 'Received'],

            // CV for Loan Release  (For Audit Purpose)
            [54, 1, 16, 'Received'],
            [54, 2, 14, 'Received'],

            // CV for Check Change (For Audit Purpose)
            [55, 1, 16, 'Received'],
            [55, 2, 14, 'Received'],

            // Yellow Copy of Release Paper for Finish Client  (For Audit Purpose)
            [56, 1, 16, 'Received'],
            [56, 2, 31, 'Received'],

            // Employee Performance Evaluation Form (Rider)
            [57, 1, 6, 'Received'],
            [57, 2, 7, 'Received'],
            [57, 3, 11, 'Received'],
            [57, 4, 4, 'Received'],

            // Employee Performance Evaluation Form (Loan Consultant)
            [58, 1, 6, 'Received'],
            [58, 2, 10, 'Received'],
            [58, 3, 7, 'Received'],
            [58, 4, 11, 'Received'],
            [58, 5, 4, 'Received'],

            // Employee Performance Evaluation Form (Loan Processor)
            [59, 1, 6, 'Received'],
            [59, 2, 10, 'Received'],
            [59, 3, 12, 'Received'],
            [59, 4, 21, 'Received'],
            [59, 5, 7, 'Received'],
            [59, 6, 11, 'Received'],
            [59, 7, 43, 'Received'],

            // Cash Referral (Treasury Receipt)
            [60, 1, 17, 'Received'],

            // Health Declaration Form
            [61, 1, 6, 'Received'],
            [61, 2, 7, 'Received'],
            [61, 3, 5, 'Received'],

            // OM
            [62, 1, 11, 'Received'],
            [62, 2, 3, 'Received'],

            // Loan Release (HO Cheque Printing)
            [63, 1, 18, 'Received'],
            [63, 2, 3, 'Received'],
            [63, 3, 25, 'Received'],
            [63, 4, 10, 'Received'],
            [63, 5, 17, 'Received'],
            [63, 6, 12, 'Received'],
            [63, 7, 18, 'Received'],
            [63, 8, 17, 'Received'],

            // Loan Release ( Cheque Writing )
            [64, 1, 18, 'Received'],
            [64, 2, 3, 'Received'],
            [64, 3, 25, 'Received'],
            [64, 4, 10, 'Received'],
            [64, 5, 17, 'Received'],
            [64, 6, 12, 'Received'],
            [64, 7, 18, 'Received'],
            [64, 8, 17, 'Received'],

            // Loan Release (Branch Cheque Printing)
            [65, 1, 18, 'Received'],
            [65, 2, 3, 'Received'],
            [65, 3, 25, 'Received'],
            [65, 4, 10, 'Received'],
            [65, 5, 17, 'Received'],
            [65, 6, 12, 'Received'],

            // Loan Release (Branch Cheque Printing)
            [66, 1, 25, 'Received'],
            [66, 2, 10, 'Received'],
            [66, 3, 17, 'Received'],
            [66, 4, 12, 'Received'],
            [66, 5, 18, 'Received'],
            [66, 6, 17, 'Received'],

            // Loan Release (Branch Cheque Printing)
            [67, 1, 25, 'Received'],
            [67, 2, 10, 'Received'],
            [67, 3, 17, 'Received'],
            [67, 4, 12, 'Received'],
            [67, 5, 18, 'Received'],
            [67, 6, 17, 'Received'],

            // Check Change (Branch Cheque Printing)
            [68, 1, 25, 'Received'],
            [68, 2, 10, 'Received'],
            [68, 3, 17, 'Received'],
            [68, 4, 12, 'Received'],

            // Cash Change
            [69, 1, 25, 'Received'],

            // Liquidation (TCA, PCA, Advances to Supplier and Other Advances)
            [70, 1, 10, 'Received'],

            // Check Change (Branch Cheque Printing)
            [71, 1, 25, 'Received'],
            [71, 2, 10, 'Received'],

            // Returned Ada Form
            [72, 1, 32, 'Received'],
            [72, 2, 5, 'Received'],

            // ATM /PB REPLACEMENT
            [73, 1, 15, 'Received'],
            [73, 2, 31, 'Received'],

            // Request TR Form Pad
            [74, 1, 32, 'Received'],
            [74, 2, 5, 'Received'],

            // Request ATM / PB Released Form Pad
            [75, 1, 32, 'Received'],
            [75, 2, 5, 'Received'],

            // Cancelled Checked
            [76, 1, 17, 'Received'],
            [76, 2, 41, 'Received'],
            [76, 3, 25, 'Received'],
            [76, 4, 17, 'Received'],

            // Cancelled Checked (Head Office Process)
            [77, 1, 17, 'Received'],
            [77, 2, 41, 'Received'],
            [77, 3, 25, 'Received'],
            [77, 4, 17, 'Received'],

            // Sample Back-Out/Apply As Payment
            [78, 1, 17, 'Received'],
            [78, 2, 41, 'Received'],
            [78, 3, 25, 'Received'],

            // CV for Check Writing
            [79, 1, 14, 'Received'],

            // Sales Invoice and Delivery Receipt From Supplier
            [80, 1, 44, 'Received'],
            [80, 2, 3, 'Received'],
            [80, 3, 10, 'Received'],
            [80, 4, 17, 'Received'],

            // Return Other Document
            [81, 1, 32, 'Received'],
            [81, 2, 5, 'Received'],

            // Return Other Document (Branch)
            [82, 1, 32, 'Received'],
            [82, 2, 5, 'Received'],

            // Official Receipts Monitoring (Head Office)
            [83, 1, 5, 'Received'],
            [83, 2, 30, 'Received'],

            // Return of Folder For Write-Off
            [84, 1, 16, 'Received'],
            [84, 2, 5, 'Received'],
            [84, 3, 16, 'Received'],

            // Deletion in LMS
            [85, 1, 17, 'Received'],
            [85, 2, 41, 'Received'],
            [85, 3, 25, 'Received'],

            // Official Receipts Monitoring
            [86, 1, 30, 'Received'],

            // Official Receipts Monitoring (Branch)
            [87, 1, 30, 'Received'],

            // IT Equipments ( Replacement / Disposal )
            [88, 1, 41, 'Received'],
            [88, 2, 5, 'Received'],
        ];

        foreach ($datas as $data) {
            DB::table('documents_sequences')->insert([
                'documents_actions_id' => $data[0],
                'sequence_no' => $data[1],
                'user_group_id' => $data[2],
                'type' => $data[3],
                'created_at' => Carbon::now(), // Use the current date for created_at
                'updated_at' => Carbon::now(), // Use the current date for updated_at
            ]);
        }
    }
}
