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
        // Schema::create('insulin_history', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });

        Schema::create('insulin_history', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users_register')
                ->restrictOnDelete();

            $table->foreignId('patient_insulin_plan_id')
                ->nullable()
                ->constrained('patient_insulin_plans')
                ->nullOnDelete();

            $table->foreignId('insulin_id')
                ->constrained('insulins')
                ->restrictOnDelete();

            $table->date('injection_date');
            $table->time('injection_time')->nullable();

            // จำนวนที่ฉีดจริง
            $table->decimal('dose_units', 10, 2)->nullable();

            /*
            * injected
            * skipped
            * forgot
            * other
            */
            $table->string('status', 30)->default('injected');

            $table->text('note')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'injection_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insulin_history');
    }
};
