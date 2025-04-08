<?php

namespace Database\Factories;

use App\Models\ATM\AtmClientBanks;
use Illuminate\Support\Facades\DB;
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
            'account_type' => $this->faker->randomElement(['SSS', 'GSIS']), // Select from predefined pension types

            // Select pension_name from data_pension_types_lists where types matches the account_type
            'pension_type' => function (array $attributes) {
                return DB::table('data_pension_types_lists')
                    ->where('types', $attributes['account_type']) // Filter by account_type
                    ->inRandomOrder()
                    ->value('pension_name');
            },

            'atm_type' => $atmType,
            'bank_name' => $this->faker->randomElement(['BDO', 'BPI', 'RCBC']),
            'bank_account_no' => $this->faker->unique()->numerify('################'),
            'pin_no' => $this->faker->unique()->numerify('########'),
            'atm_status' => $this->faker->randomElement(['new']),
            'expiration_date' => date('Y-m-d', mktime(0, 0, 0, rand(1, 12), 1, 2030)),
            'collection_date' => DB::table('data_collection_dates')->inRandomOrder()->value('collection_date'),
            'cash_box_no' => null,
            'safekeep_cash_box_no' => null,
            'location' => 'Head Office',
            'status' => '1',
            'created_at' => "{$randomYear}-{$randomMonth}-{$randomDay} 00:00:00",
        ];
    }
}
