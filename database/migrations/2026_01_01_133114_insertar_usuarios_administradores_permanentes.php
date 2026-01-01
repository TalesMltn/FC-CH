<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'ginaamgeotop.sac@gmail.com'],
            [
                'name'              => 'Gina Amgeotop',
                'email'             => 'ginaamgeotop.sac@gmail.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('Cement0GravaHY!2025&'),
                'role'              => 'admin',
                'created_at'        => now(),
                'updated_at'        => now(),
            ]
        );

        DB::table('users')->updateOrInsert(
            ['email' => 'miguelamgeotop.sac@gmail.com'],
            [
                'name'              => 'Miguel Amgeotop',
                'email'             => 'miguelamgeotop.sac@gmail.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('Vibrad0r@Concretera#HY2025'),
                'role'              => 'admin',
                'created_at'        => now(),
                'updated_at'        => now(),
            ]
        );

        DB::table('users')->updateOrInsert(
            ['email' => 'geremy_rko56@hotmail.com'],
            [
                'name'              => 'Andrew Vega Reyes',
                'email'             => 'geremy_rko56@hotmail.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('Andr3wD3v3lop3r2025!'),
                'role'              => 'developer',
                'created_at'        => now(),
                'updated_at'        => now(),
            ]
        );
    }

    public function down(): void
    {
        // Opcional: borrar si haces rollback
        DB::table('users')->whereIn('email', [
            'ginaamgeotop.sac@gmail.com',
            'miguelamgeotop.sac@gmail.com',
            'geremy_rko56@hotmail.com',
        ])->delete();
    }
};