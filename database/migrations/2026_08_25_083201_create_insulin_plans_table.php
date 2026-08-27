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
        // Schema::create('insulin_plans', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });

        Schema::create('insulin_plans', function (Blueprint $table) {
                $table->id();

                $table->foreignId('user_id')
                    ->constrained('users_register')
                    ->restrictOnDelete();

                $table->foreignId('insulin_id')
                    ->constrained('insulins')
                    ->restrictOnDelete();

                // ขนาดยา
                $table->decimal('dose_units', 10, 2);

                /*
                * before_breakfast
                * before_lunch
                * before_dinner
                * bedtime
                * other
                */
                $table->string('injection_period', 50);

                // ถ้าต้องการระบุเวลาจริง เช่น 07:00
                $table->time('injection_time')->nullable();

                // วันที่เริ่มใช้แผน
                $table->date('start_date');

                $table->date('end_date')->nullable();

                /*
                * active
                * stopped
                * replaced
                */
                $table->string('status', 30)->default('active');
                $table->string('prescribed_by');
                
                $table->text('note')->nullable();

                $table->timestamps();

                $table->index(['user_id', 'status']);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insulin_plans');
    }
};
