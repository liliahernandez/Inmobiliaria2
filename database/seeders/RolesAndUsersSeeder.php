<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndUsersSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::updateOrCreate(['name' => 'admin']);
        $userRole = Role::updateOrCreate(['name' => 'user']); 

        $adminUser = User::updateOrCreate(
            ['email' => 'liliahernandez2004@gmail.com'],
            [
                'name' => 'adminnistrador',
                'password' => Hash::make('santes2004'),
            ]
        );
        $adminUser->assignRole($adminRole);

        $liliaUser = User::updateOrCreate(
            ['email' => 'lilia.hernandez.23e@utzmg.edu.mx'], 
            [
                'name' => 'Usuario',
                'password' => Hash::make('hernandez2003'),
            ]
        );
        $liliaUser->assignRole($userRole);
    }
}
