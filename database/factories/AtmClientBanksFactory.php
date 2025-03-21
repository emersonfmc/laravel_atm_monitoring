<?php

namespace Database\Factories;

use App\Models\AtmClientBanks;
use Illuminate\Database\Eloquent\Factories\Factory;

class AtmClientBanksFactory extends Factory
{
    protected $model = AtmClientBanks::class;

    public function definition()
    {
        $atmType = $this->faker->randomElement(['ATM', 'Passbook', 'Sim Card']);

        $randomMonth = $this->faker->numberBetween(1, 12); // Random month between 1 and 12
        $randomDay = $this->faker->numberBetween(1, 28); // Random day between 1 and 28 (to avoid month length issues)
        $randomYear = $this->faker->numberBetween(2025, 2024);

        return [
            'client_information_id' => null, // This will be filled in by ClientInformationFactory
            'atm_type' => $atmType,
            'bank_name' => $this->faker->randomElement(['BDO', 'BPI', 'RCBC']),
            'bank_account_no' => $this->faker->unique()->numerify('################'),
            'pin_no' => $this->faker->unique()->numerify('########'),
            'atm_status' => $this->faker->randomElement(['new']),
            'expiration_date' => date('Y-m-d', mktime(0, 0, 0, rand(1, 12), 1, 2030)),
            'collection_date' => $this->faker->randomElement(['1st', '8th', '16th', '1st and 8th', '1st and 16th']),
            'cash_box_no' => null,
            'safekeep_cash_box_no' => null,
            'location' => 'Head Office',
            'branch_id' => null, // This will be set to the client's branch_id in ClientInformationFactory
            'status' => '1',
            'created_at' => "{$randomYear}-{$randomMonth}-{$randomDay} 00:00:00",
        ];
    }
}
