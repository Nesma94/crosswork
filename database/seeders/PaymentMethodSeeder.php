<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            [
                'name' => 'Cash',
                'extra_details' => [
                    'description' => 'Payment made directly in cash at delivery or pickup.',
                ],
            ],
            [
                'name' => 'Credit Card',
                'extra_details' => [
                    'provider' => 'Visa / MasterCard',
                    'notes' => 'Processed via secure gateway.',
                ],
            ],
            [
                'name' => 'Wallet',
                'extra_details' => [
                    'provider' => 'In-app wallet',
                    'currency' => 'EGP',
                ],
            ],
            [
                'name' => 'Bank Transfer',
                'extra_details' => [
                    'account_name' => 'Crosswork Egypt',
                    'iban' => 'EG1234567890',
                ],
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate(['name' => $method['name']], $method);
        }
    }
}
