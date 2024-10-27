<?php

namespace Database\Factories;

use App\Models\AtmClientBanks;
use Illuminate\Database\Eloquent\Factories\Factory;

class AtmClientBanksFactory extends Factory
{
    protected $model = AtmClientBanks::class;

    public function definition()
    {
        return [
            'client_information_id' => null, // This will be filled in by ClientInformationFactory
            'atm_type' => $this->faker->randomElement(['ATM', 'Passbook']),
            'bank_name' => $this->faker->randomElement(['BDO', 'BPI', 'RCBC']),
            'bank_account_no' => $this->faker->unique()->numerify('################'),
            'pin_no' => $this->faker->unique()->numerify('########'),
            'atm_status' => $this->faker->randomElement(['new']),
            'expiration_date' => null,
            'collection_date' => $this->faker->randomElement(['1st', '8th', '16th', '1st and 8th', '1st and 16th']),
            'cash_box_no' => null,
            'safekeep_cash_box_no' => null,
            'location' => 'Head Office',
            'branch_id' => null, // This will be set to the client's branch_id in ClientInformationFactory
        ];
    }
}
