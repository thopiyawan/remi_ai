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
            Schema::create('meal_logs', function (Blueprint $table) {
                $table->id();

                $table->foreignId('meal_transaction_id')
                    ->constrained('meal_transactions')
                    ->restrictOnDelete();

                // created, updated, reviewed, approved, rejected
                $table->string('action', 30);

                // เช่น meal_type, meal_date, meal_time, recommendation
                $table->string('field_name', 50)->nullable();

                $table->text('old_value')->nullable();
                $table->text('new_value')->nullable();

                // patient, ai, nutritionist, doctor, system
                $table->string('actor_type', 30);

                $table->foreignId('actor_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                $table->string('change_reason', 100)->nullable();
                $table->text('note')->nullable();

                $table->index(['meal_transaction_id', 'created_at']);
                $table->index('field_name');
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_logs');
    }
};
