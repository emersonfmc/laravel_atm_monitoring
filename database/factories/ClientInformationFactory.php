<?php

namespace Database\Factories;

use App\Models\ATM\AtmClientBanks;
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
        $randomYear = $this->faker->numberBetween(2025, 2024); // Random year between 2024 and 2025

        return [
            'first_name' => $this->faker->firstName, // Generate a random first name
            'middle_name' => strtoupper($this->faker->randomLetter), // Generate a random capital letter and add a dot
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
            $datePart = now()->format('Y');

            // Generate a shared pension number per client
            $sharedPensionNumber = fake()->unique()->numerify('###########');
            $sameBranchId = $this->faker->randomElement([4]);

            for ($i = 0; $i < 2; $i++) {
                $latestTransactionNumber = AtmClientBanks::where('transaction_number', 'LIKE', "AL-$datePart-%")
                    ->orderBy('transaction_number', 'desc')
                    ->value('transaction_number');

                $increment = $latestTransactionNumber ? ((int)substr($latestTransactionNumber, -5) + 1) : 1;
                $incrementedPart = str_pad($increment, 5, '0', STR_PAD_LEFT);
                $transactionNumber = "AL-$datePart-$incrementedPart";

                AtmClientBanks::factory()->create([
                    'client_information_id' => $client->id,
                    'transaction_number' => $transactionNumber,
                    'pension_number' => $sharedPensionNumber, // <- use the same pension number for both
                    'branch_id' => $sameBranchId, // <- use the same pension number for both
                ]);
            }
        });
    }



}
