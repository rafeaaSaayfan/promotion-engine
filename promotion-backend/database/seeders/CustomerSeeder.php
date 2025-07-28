<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create(['email' => 'alice@apple.com', 'type' => 'restaurants', 'loyalty_tier' => 'silver', 'orders_count' => 3, 'city' => 'Riyadh']);
        Customer::create(['email' => 'bob@techcorp.io', 'type' => 'retail', 'loyalty_tier' => 'gold', 'orders_count' => 15, 'city' => 'Jeddah']);
        Customer::create(['email' => 'carol@diner.sa', 'type' => 'restaurants', 'loyalty_tier' => 'none', 'orders_count' => 0, 'city' => 'Jeddah']);
        Customer::create(['email' => 'dave@example.com', 'type' => 'retail', 'loyalty_tier' => 'gold', 'orders_count' => 7, 'city' => 'Tabuk']);
    }
}
