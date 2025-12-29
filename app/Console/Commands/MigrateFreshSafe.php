<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateFreshSafe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:fresh-safe {--seed : Ejecutar los seeders después de migrar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ejecuta migrate:fresh pero protege las tablas de permisos y roles (Spatie)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando migrate:fresh-safe...');

        // Tablas que NO se borrarán (ajusta si usas otros nombres)
        $protectedTables = [
            'permissions',
            'roles',
            'model_has_permissions',
            'model_has_roles',
            'role_has_permissions',
            // Agrega más si tienes tablas adicionales que quieras proteger
            // 'users', // opcional: si no quieres borrar usuarios
        ];

        // Desactivar chequeo de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Obtener todas las tablas de la base de datos
        $tables = DB::select('SHOW TABLES');

        $dbName = config('database.connections.mysql.database');

        foreach ($tables as $tableObj) {
            $tableName = $tableObj->{"Tables_in_{$dbName}"};

            if (! in_array($tableName, $protectedTables)) {
                $this->info("Borrando tabla: {$tableName}");
                Schema::dropIfExists($tableName);
            } else {
                $this->info("Protegiendo tabla: {$tableName}");
            }
        }

        // Reactivar chequeo de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('Tablas borradas (excepto las protegidas). Ejecutando migraciones...');

        // Ejecutar las migraciones normales
        $this->call('migrate', ['--force' => true]);

        $this->info('Migraciones completadas.');

        // Si se pasó la opción --seed, ejecutar los seeders
        if ($this->option('seed')) {
            $this->info('Ejecutando seeders...');
            $this->call('db:seed', ['--class' => 'DatabaseSeeder']); // o el que uses, ej: AdminUsersSeeder
        }

        $this->info('Proceso finalizado con éxito.');
    }
}