<?php

namespace Database\Factories;

use App\Models\AtmClientBanks;
use App\Models\ClientInformation;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientInformationFactory extends Factory
{
    protected $model = ClientInformation::class;

    public function definition()
    {
        $randomMonth = $this->faker->numberBetween(1, 12); // Random month between 1 and 12
        $randomDay = $this->faker->numberBetween(1, 28); // Random day between 1 and 28 (to avoid month length issues)
        $randomYear = $this->faker->numberBetween(2024, 2023); // Random day between 1 and 28 (to avoid month length issues)

        return [
            'branch_id' => $this->faker->randomElement([4, 5, 6, 8]), // Select branch_id from 5 or 6
            'pension_number' => $this->faker->unique()->numerify('###########'), // Generate 11-digit pension number
            'pension_type' => $this->faker->randomElement(['SSS','GSIS']), // Select from predefined pension types
            'pension_account_type' => DB::table('data_pension_types_lists')->inRandomOrder()->value('pension_name'), // Randomly select pension_name from atm_pension_types
            'first_name' => $this->faker->firstName, // Generate a random first name
            'middle_name' => $this->faker->firstName, // Generate a random middle name
            'last_name' => $this->faker->lastName, // Generate a random last name
            'suffix' => $this->faker->optional()->randomElement(['Jr.', 'Sr.', 'Ma.', 'I', 'II', 'III', 'IV']), // Random suffix or null
            'birth_date' => $this->faker->dateTimeBetween('1950-01-01', '1960-12-31')->format('Y-m-d'), // Random birth date
            'passbook_for_collection' => 'no',
            'created_at' => "{$randomYear}-{$randomMonth}-{$randomDay} 00:00:00",
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (ClientInformation $client) {
            // Format today's date as MMDDYY for the transaction number prefix
            $datePart = now()->format('Y'); // e.g., "103024" for October 30, 2024

            // Create 2 unique ATMs for each client
            for ($i = 0; $i < 2; $i++) {
                // Fetch the latest transaction number for today and increment it
                $latestTransactionNumber = AtmClientBanks::where('transaction_number', 'LIKE', "TS-$datePart-%")
                    ->orderBy('transaction_number', 'desc')
                    ->value('transaction_number');

                // Determine the next increment
                $increment = $latestTransactionNumber ? ((int)substr($latestTransactionNumber, -5) + 1) : 1;

                // Generate a 5-digit incremented part, e.g., "00001"
                $incrementedPart = str_pad($increment, 5, '0', STR_PAD_LEFT);

                // Form the full transaction number
                $transactionNumber = "TS-$datePart-$incrementedPart";

                // Create an ATM record for the client with a unique transaction number
                AtmClientBanks::factory()->create([
                    'client_information_id' => $client->id,
                    'branch_id' => $client->branch_id,
                    'transaction_number' => $transactionNumber,
                ]);
            }
        });
    }


}
