<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'merchant']);

        $user = \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin@#'),
            'tag' => Str::random(10),
        ]);
        
        $user->assignRole('admin');

        $user = \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'wakeel@mail.com',
            'password' => bcrypt('@#12345base'),
            'tag' => Str::random(10),
        ]);
        $user->assignRole('merchant');

        \App\Models\PaymentGateway::create([
            'name' => 'CashApp',
            'description' => 'CashApp',
            'logo' => 'https://cdn.iconscout.com/icon/free/png-256/cash-app-1-283994.png',
            'config' => [
                'client_id' => 'client_id',
                'client_secret' => 'client_secret',
                'mode' => 'sandbox',
            ],
        ]);

        foreach (['Game Points 1', 'Game Points v2', 'Game Points v3'] as  $value) {
            \App\Models\Product::create([
                'name' => $value,
                'user_id' => 2,
                'description' => 'Game Points',
                'price' => 10,
                'discount' => 0,
                'tax' => 0.5,
            ]);
        }

        \App\Models\Invoice::create([
            'user_id' => 2,
            'payment_gateway_id' => 1,
            'tx_id' => Str::random(10),
            'amount' => 10,
            'amount_paid' => 10,
            'tax' => 0.5,
            'status' => 'paid',
        ]);
    }
}
