<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetTablesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:reset-tables {--y|yes : Confirm the action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset all tables with data and restart sequences.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->confirmation()) {
            $this->info('La acción ha sido cancelada.');

            return self::FAILURE;
        }

        // Deshabilitar restricciones de integridad referencial
        DB::statement('SET CONSTRAINTS ALL DEFERRED;');

        // Obtener todos los esquemas excepto los esquemas del sistema de PostgreSQL
        foreach ($this->getSchemas() as $schema) {
            $schemaName = $schema->nspname;

            // Obtener todas las tablas del esquema actual creadas por el usuario, con su cantidad de filas
            foreach ($this->getTables($schemaName) as $table) {
                $tableName = $table->table_name;
                $rowCount = $table->row_count;

                // if ($rowCount > 0) {
                $this->truncateTable($schemaName, $tableName);
                // }
            }
        }

        // Habilitar restricciones de integridad referencial
        DB::statement('SET CONSTRAINTS ALL IMMEDIATE;');

        $this->info('Todas las tablas han sido truncadas y las secuencias reiniciadas.');

        return self::SUCCESS;
    }

    private function truncateTable(string $schemaName, string $tableName): void
    {
        $stament = sprintf(
            'TRUNCATE TABLE "%s"."%s" RESTART IDENTITY CASCADE;',
            $schemaName,
            $tableName
        );

        DB::statement($stament);
    }

    private function getTables(string $schemaName): array
    {
        return DB::select("
            SELECT
                relname AS table_name,
                n_live_tup AS row_count
            FROM pg_stat_user_tables
            WHERE schemaname = '{$schemaName}';
        ");
    }

    private function getSchemas(): array
    {
        return DB::select(
            "SELECT nspname
            FROM pg_namespace
            WHERE nspname NOT LIKE 'pg_%'
            AND nspname != 'information_schema'"
        );
    }

    private function confirmation(): bool
    {
        $yes = $this->option('yes');

        if ($yes) {
            return true;
        }

        $message = "¿Estás seguro de que deseas eliminar todos los datos de las tablas?
            Esta acción no se puede deshacer.\n\n";

        return $this->confirm($message, false);
    }
}
