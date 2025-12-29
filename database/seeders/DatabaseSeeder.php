<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Si quieres mantener el usuario de prueba, déjalo
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Llama al seeder de usuarios administradores
        $this->call(AdminUsersSeeder::class);

        // Opcional: si tienes más seeders (categorías, personas, etc.)
        // $this->call(CategoriesSeeder::class);
    }
}