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
        Schema::create('exercise_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users_register')
                ->restrictOnDelete();

            // เดิน, วิ่ง, โยคะ, ว่ายน้ำ ฯลฯ
            $table->string('exercise_type', 100);

            $table->date('exercise_date');
            $table->time('start_time')->nullable();

            // นาที
            $table->integer('duration_minutes')->nullable();

            // light, moderate, vigorous
            $table->string('intensity', 30)->nullable();

            $table->decimal('calories_burned', 10, 2)->nullable();

            $table->text('note')->nullable();

            // patient, doctor, nutritionist, system
            $table->string('created_source', 30)->default('patient');

            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();


            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'exercise_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercise_logs');
    }
};
