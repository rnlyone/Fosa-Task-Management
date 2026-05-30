<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Stores per-user email notification preferences as JSON.
            // Null means "use defaults" (all enabled).
            $table->json('mail_preferences')->nullable()->after('email_change_token');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('mail_preferences');
        });
    }
};
