<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar caché de permisos de Spatie (importante)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear roles si no existen
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'developer']);

        // Gina → admin
        $gina = User::updateOrCreate(
            ['email' => 'ginaamgeotop.sac@gmail.com'],
            [
                'name'              => 'Gina Amgeotop',
                'password'          => Hash::make('Cement0GravaHY!2025&'),
                'email_verified_at' => now(),
            ]
        );
        $gina->assignRole('admin');

        // Miguel → admin
        $miguel = User::updateOrCreate(
            ['email' => 'miguelamgeotop.sac@gmail.com'],
            [
                'name'              => 'Miguel Amgeotop',
                'password'          => Hash::make('Vibrad0r@Concretera#HY2025'),
                'email_verified_at' => now(),
            ]
        );
        $miguel->assignRole('admin');

        // Andrew → developer
        $andrew = User::updateOrCreate(
            ['email' => 'geremy_rko56@hotmail.com'],
            [
                'name'              => 'Andrew Vega Reyes',
                'password'          => Hash::make('Andr3wD3v3lop3r2025!'),
                'email_verified_at' => now(),
            ]
        );
        $andrew->assignRole('developer');

        // Mensaje de éxito
        $this->command->info('Usuarios y roles asignados correctamente:');
        $this->command->info('- Gina: admin');
        $this->command->info('- Miguel: admin');
        $this->command->info('- Andrew: developer');
    }
}