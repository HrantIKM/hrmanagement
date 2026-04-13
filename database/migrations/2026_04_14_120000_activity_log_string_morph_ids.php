<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * File (and other models) use UUID primary keys; default morph columns are unsignedBigInteger,
 * which truncates UUIDs and triggers MySQL 1265. Store morph ids as strings for int and UUID subjects.
 */
return new class extends Migration
{
    public function up(): void
    {
        $connectionName = config('activitylog.database_connection');
        $db = $connectionName ? DB::connection($connectionName) : DB::connection();
        $table = config('activitylog.table_name', 'activity_log');
        $driver = $db->getDriverName();
        $fullTable = $db->getTablePrefix() . $table;

        if ($driver === 'mysql' || $driver === 'mariadb') {
            $db->statement("ALTER TABLE `{$fullTable}` MODIFY `subject_id` VARCHAR(36) NULL");
            $db->statement("ALTER TABLE `{$fullTable}` MODIFY `causer_id` VARCHAR(36) NULL");
        }
    }

    public function down(): void
    {
        $connectionName = config('activitylog.database_connection');
        $db = $connectionName ? DB::connection($connectionName) : DB::connection();
        $table = config('activitylog.table_name', 'activity_log');
        $driver = $db->getDriverName();
        $fullTable = $db->getTablePrefix() . $table;

        if ($driver === 'mysql' || $driver === 'mariadb') {
            $db->statement("ALTER TABLE `{$fullTable}` MODIFY `subject_id` BIGINT UNSIGNED NULL");
            $db->statement("ALTER TABLE `{$fullTable}` MODIFY `causer_id` BIGINT UNSIGNED NULL");
        }
    }
};
