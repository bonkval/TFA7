<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['full_name' => 'Cedrick Vales', 'email' => 'cedrick.vales@example.com', 'phone' => '0967-676-7676'],
            ['full_name' => 'Joseph Bondoc', 'email' => 'joseph.bondoc@example.com', 'phone' => '0967-676-7676'],
            ['full_name' => 'Philyip Caluag', 'email' => 'philyip.caluag@example.com', 'phone' => '0967-676-7676'],
            ['full_name' => 'Lexus Medina', 'email' => 'lexus.medina@example.com', 'phone' => '0967-676-7676'],
            ['full_name' => 'Raining Odarbe', 'email' => 'raining.odarbe@example.com', 'phone' => '0967-676-7676'],
        ];

        foreach ($customers as $customer) {
            Customer::updateOrCreate(['email' => $customer['email']], $customer);
        }

        $users = [
            ['username' => 'cvales', 'full_name' => 'Cedrick Vales'],
            ['username' => 'jbondoc', 'full_name' => 'Joseph Bondoc'],
            ['username' => 'pcaluag', 'full_name' => 'Philyip Caluag'],
            ['username' => 'lmedina', 'full_name' => 'Lexus Medina'],
            ['username' => 'rodarbe', 'full_name' => 'Raining Odarbe'],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['username' => $user['username']], $user);
        }
    }
}
