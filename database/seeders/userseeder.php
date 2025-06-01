<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class userseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::firstOrCreate(
            ['email' => 'admin1@example.com'],
            [
                'name' => 'Administrador1',
                'apellido' => 'Admin',
                'telefono' => '123456789',
                'rol_id' => 1, // Assuming 1 is the ID for the admin role
                'password' => bcrypt('password'),
            ]
        );
$adminUser->assignRole('admin');
    }
}
