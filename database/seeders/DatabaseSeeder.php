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
            'name' => 'Wakeel Ahmad',
            'email' => 'wakeel@mail.com',
            'password' => bcrypt('@#wakeel'),
            'tag' => Str::random(10),
        ]);
        $user->assignRole('merchant');

        $user = \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'officalbusiness@mail.com',
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
    }
}
