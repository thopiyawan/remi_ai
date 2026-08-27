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
        // Schema::create('meal_item_logs', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });

         Schema::create('meal_item_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('meal_item_id')
                ->constrained('meal_items')
                ->restrictOnDelete();

            /*
             * เชื่อมว่าการแก้ field นี้
             * เกิดจากการ Save ครั้งไหน
             */
            $table->foreignId('meal_log_id')
                ->nullable()
                ->constrained('meal_logs')
                ->restrictOnDelete();

            /*
             * created
             * updated
             * deleted
             * restored
             */
            $table->string('action', 30);

            /*
             * เช่น
             * food_name
             * food_id
             * weight_g
             * portion
             * unit
             * calorie
             * carbohydrate
             * protein
             * fat
             * fiber
             */
            $table->string('field_name', 50)->nullable();

            /*
             * ใช้ text เพราะ old/new อาจเป็น
             * string หรือ number
             *
             * ยังถือว่าเป็น field ปกติ
             * ไม่ใช่ JSON
             */
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();

            /*
             * เหตุผลที่แก้
             *
             * wrong_food
             * wrong_portion
             * wrong_weight
             * wrong_nutrition
             * missing_food
             * other
             */
            $table->string('change_reason', 50)->nullable();

            /*
             * ai
             * patient
             * nutritionist
             * doctor
             * system
             */
            $table->string('actor_type', 30);

            // $table->foreignId('actor_id')
            //     ->nullable()
            //     ->constrained('users')
            //     ->nullOnDelete();

            $table->timestamp('created_at')->useCurrent();

            $table->index([
                'meal_item_id',
                'created_at'
            ]);

            $table->index('field_name');
            $table->index('change_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_item_logs');
    }
};
