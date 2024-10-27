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
        return [
            'branch_id' => $this->faker->randomElement([5, 6]), // Select branch_id from 5 or 6
            'pension_number' => $this->faker->unique()->numerify('###########'), // Generate 11-digit pension number
            'pension_type' => $this->faker->randomElement(['SSS','GSIS']), // Select from predefined pension types
            'pension_account_type' => DB::table('data_pension_types_lists')->inRandomOrder()->value('pension_name'), // Randomly select pension_name from atm_pension_types
            'first_name' => $this->faker->firstName, // Generate a random first name
            'middle_name' => $this->faker->firstName, // Generate a random middle name
            'last_name' => $this->faker->lastName, // Generate a random last name
            'suffix' => $this->faker->optional()->randomElement(['Jr.', 'Sr.', 'Ma.', 'I', 'II', 'III', 'IV']), // Random suffix or null
            'birth_date' => $this->faker->dateTimeBetween('1950-01-01', '1960-12-31')->format('Y-m-d'), // Random birth date
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (ClientInformation $client) {
            // Create 2 ATMs for each client
            AtmClientBanks::factory()->count(2)->create([
                'client_information_id' => $client->id,
                'branch_id' => $client->branch_id, // Ensure branch_id matches the client
            ]);
        });
    }
}
