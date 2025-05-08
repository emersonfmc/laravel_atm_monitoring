<?php

namespace Database\Seeders;

use Carbon\Carbon;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\ClientsInformationSeeder;
use Database\Seeders\ElogSeeder\AtmBanksSeeder;

use Database\Seeders\ElogSeeder\AtmPensionTypesSeeder;
use Database\Seeders\ElogSeeder\AtmTransactionActionSeeder;
use Database\Seeders\ElogSeeder\AtmTrasanctionSequenceSeeder;
use Database\Seeders\ElogSeeder\DataBorrowOptionSeeder;
use Database\Seeders\ElogSeeder\DataCollectionDateSeeder;
use Database\Seeders\ElogSeeder\DataReleaseReasonSeeder;
use Database\Seeders\ElogSeeder\DocumentActionSeeder;
use Database\Seeders\ElogSeeder\DocumentsActionSequenceSeeder;


class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AtmBanksSeeder::class,
            AtmPensionTypesSeeder::class,
            AtmTransactionActionSeeder::class,
            AtmTrasanctionSequenceSeeder::class,
            DataBorrowOptionSeeder::class,
            DataCollectionDateSeeder::class,
            DataReleaseReasonSeeder::class,

            DocumentActionSeeder::class,
            DocumentsActionSequenceSeeder::class,

            UserSeeder::class,
            ClientsInformationSeeder::class,
        ]);
    }
}
