<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            // MySQL requires an explicit ALTER COLUMN to extend the enum
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('president','vice_president','member','administrator') NOT NULL DEFAULT 'member'");
        }
        // SQLite stores enums as plain strings — no DDL change needed
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("UPDATE users SET role = 'member' WHERE role = 'administrator'");
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('president','vice_president','member') NOT NULL DEFAULT 'member'");
        }
    }
};
