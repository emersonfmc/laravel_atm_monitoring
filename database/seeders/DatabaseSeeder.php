<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\ClientsInformationSeeder;
use Database\Seeders\SettingsSeeder\AreaSeeder;
use Database\Seeders\SettingsSeeder\CompanySeeder;
use Database\Seeders\SettingsSeeder\AtmBanksSeeder;
use Database\Seeders\SettingsSeeder\BranchesSeeder;
use Database\Seeders\SettingsSeeder\DistrictSeeder;
use Database\Seeders\SettingsSeeder\DataBorrowOption;
use Database\Seeders\SettingsSeeder\UserGroupSeeder;
use Database\Seeders\SettingsSeeder\DocumentActionSeeder;
use Database\Seeders\SettingsSeeder\AtmPensionTypesSeeder;
use Database\Seeders\SettingsSeeder\DataDepartmentsSeeder;
use Database\Seeders\SettingsSeeder\DataReleaseReasonSeeder;
use Database\Seeders\SettingsSeeder\AtmMaintenancePageSeeder;
use Database\Seeders\SettingsSeeder\DataBorrowedReasonSeeder;
use Database\Seeders\SettingsSeeder\DataCollectionDateSeeder;
use Database\Seeders\SettingsSeeder\AtmTransactionActionSeeder;
use Database\Seeders\SettingsSeeder\AtmTrasanctionSequenceSeeder;
use Database\Seeders\SettingsSeeder\DocumentsActionSequenceSeeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            ClientsInformationSeeder::class,
        ]);
    }
}
