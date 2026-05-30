<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('manager_id')->constrained('users')->restrictOnDelete();
            $table->date('event_date')->nullable();
            $table->date('start_preparing_date')->nullable();
            $table->enum('status', ['planning', 'preparation', 'active', 'completed', 'cancelled'])->default('planning');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('event_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['event_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_members');
        Schema::dropIfExists('events');
    }
};
