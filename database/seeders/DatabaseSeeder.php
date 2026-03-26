<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        $adminRole = Role::query()->where('name', 'admin')->firstOrFail();
        $employeeRole = Role::query()->where('name', 'empleado')->firstOrFail();

        User::query()->updateOrCreate(
            ['email' => 'admin@carrental.test'],
            [
                'name' => 'Administrador',
                'role_id' => $adminRole->id,
                'password' => Hash::make('password'),
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'empleado@carrental.test'],
            [
                'name' => 'Empleado',
                'role_id' => $employeeRole->id,
                'password' => Hash::make('password'),
            ]
        );
    }
}
