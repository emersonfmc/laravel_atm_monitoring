<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            CompanySeeder::class,
            DataDepartmentsSeeder::class,
            DataBorrowedReasonSeeder::class,
            DistrictSeeder::class,
            UserGroupSeeder::class,
            AreaSeeder::class,
            BranchesSeeder::class,
            AtmBanksSeeder::class,
            AtmPensionTypesSeeder::class,
            AtmTransactionActionSeeder::class,
            UserSeeder::class,
            AtmTrasanctionSequenceSeeder::class,
            DataCollectionDateSeeder::class,
            DataReleaseReasonSeeder::class,
            AtmMaintenancePageSeeder::class,
            DocumentActionSeeder::class,
            DocumentsActionSequenceSeeder::class,
            ClientsInformationSeeder::class,
        ]);
    }
}
