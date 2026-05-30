<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mailer_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('host');
            $table->unsignedSmallInteger('port')->default(587);
            $table->enum('encryption', ['tls', 'ssl', 'none'])->default('tls');
            $table->string('username');
            $table->text('password'); // stored encrypted
            $table->string('from_address');
            $table->string('from_name');
            $table->unsignedInteger('priority')->default(0); // lower = tried first
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mailer_accounts');
    }
};
