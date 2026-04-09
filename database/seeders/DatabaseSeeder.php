<?php

namespace Database\Seeders;

use App\Models\BankAccounts;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ✅ Create SuperAdmin User
        User::create([
            'first_name' => 'Dulana',
            'last_name' => 'Nuwanjith',
            'email' => 'dulana69@gmail.com',
            'password' => Hash::make('Dulana@123'),
            'nic' => '199916000833',
            'role' => 'superadmin',
        ]);

        // ✅ Initial Bank Accounts
        $bankAccounts = [
            ['bank_name' => 'HNB', 'company_name' => 'Stretchtec'],
            ['bank_name' => 'NDB', 'company_name' => 'Stretchtec'],
            ['bank_name' => 'Sampath', 'company_name' => 'Stretchtec'],
            ['bank_name' => 'Sampath', 'company_name' => 'Bandulasena'],
            ['bank_name' => 'Commercial', 'company_name' => 'Bandulasena'],
            ['bank_name' => 'Sampath', 'company_name' => 'Rangiri Aqua'],
            ['bank_name' => 'Sampath', 'company_name' => 'Nisu Creations'],
            ['bank_name' => 'NDB', 'company_name' => 'Nisu Apperals'],
            ['bank_name' => 'NDB', 'company_name' => 'Livinco'],
        ];

        foreach ($bankAccounts as $account) {
            BankAccounts::create([
                'bank_name' => $account['bank_name'],
                'company_name' => $account['company_name'],
                'is_active' => true,
                'branch_name' => 'Default Branch', // Added default branch to avoid null if required
            ]);
        }
    }
}
