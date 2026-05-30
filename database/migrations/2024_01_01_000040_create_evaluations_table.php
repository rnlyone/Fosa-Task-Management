<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->unique()->constrained()->cascadeOnDelete();
            $table->timestamp('opens_at')->nullable();
            $table->timestamp('closes_at')->nullable();
            $table->timestamps();
        });

        Schema::create('evaluation_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('evaluator_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->unique(['evaluation_id', 'evaluator_id']);
        });

        Schema::create('evaluation_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('evaluation_submissions')->cascadeOnDelete();
            $table->foreignId('evaluated_user_id')->constrained('users')->cascadeOnDelete();
            $table->tinyInteger('rating')->unsigned()->comment('1-5 star rating');
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_entries');
        Schema::dropIfExists('evaluation_submissions');
        Schema::dropIfExists('evaluations');
    }
};
