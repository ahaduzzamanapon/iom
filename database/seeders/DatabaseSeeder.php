<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Roles ──────────────────────────────────────────────
        $roles = ['admin', 'teacher', 'student', 'staff'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // ── Admin User ──────────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@iom.edu'],
            [
                'name'     => 'IOM Admin',
                'password' => Hash::make('admin12345'),
            ]
        );
        $admin->assignRole('admin');

        // ── Default System Settings ──────────────────────────────
        $defaults = [
            'institute_name' => 'Islamic Online Madrasah',
            'address'        => 'Dhaka, Bangladesh',
            'phone'          => '01700-000000',
            'email'          => 'info@iom.edu',
            'logo'           => '',
            'website'        => 'https://iom.edu',
            'gpa_a_plus'     => '80',
            'gpa_a'          => '70',
            'gpa_a_minus'    => '60',
            'gpa_b_plus'     => '55',
            'gpa_b'          => '50',
            'gpa_b_minus'    => '45',
            'min_attendance'  => '75',
        ];
        foreach ($defaults as $key => $value) {
            SystemSetting::firstOrCreate(['key' => $key], ['value' => $value]);
        }

        // Call DemoDataSeeder to seed realistic academic demo records
        $this->call(DemoDataSeeder::class);

        // Call SurveySeeder to seed the branching survey records
        $this->call(SurveySeeder::class);
    }
}
