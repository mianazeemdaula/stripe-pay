<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin@#'),
            'type' => 'admin',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'officialwakeelahmad@gmail.com',
            'password' => bcrypt('wakeelofficial722462@'),
        ]);

        \App\Models\PaymentGateway::create([
            'name' => 'Stripe',
        ]);

        foreach (['P1', 'P2', 'P3'] as  $value) {
            \App\Models\Product::create([
                'name' => $value,
                'user_id' => 2,
            ]);
        }

        \App\Models\Invoice::create([
            'invoice_id' => Str::random(50),
            'user_id' => 2,
            'payment_gateway_id' => 1,
            'product_id' => 1,
        ]);
    }
}
