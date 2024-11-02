<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClientInformation;

class ClientsInformationSeeder extends Seeder
{
    public function run()
    {
        // Create 10 clients, each with 2 ATMs
        ClientInformation::factory()->count(300)->create();
    }
}
