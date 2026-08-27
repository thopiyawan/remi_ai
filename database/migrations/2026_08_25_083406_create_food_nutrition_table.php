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
        Schema::create('food_nutritions', function (Blueprint $table) {
            $table->id();

            // $table->foreignId('food_id')
            //     ->constrained('foods')
            //     ->restrictOnDelete();

            // ค่ามาตรฐานต่อ 100 กรัม
            $table->decimal('reference_weight_g', 8, 2);

            $table->decimal('energy_kcal', 10, 2)->nullable();
            $table->decimal('carbohydrate_g', 10, 2)->nullable();
            $table->decimal('protein_g', 10, 2)->nullable();
            $table->decimal('fat_g', 10, 2)->nullable();
            $table->decimal('fiber_g', 10, 2)->nullable();
            $table->decimal('sugar_g', 10, 2)->nullable();
            $table->decimal('sodium_mg', 10, 2)->nullable();

            // pending, verified, rejected
            $table->string('status', 30)->default('pending');

            // แหล่งข้อมูล
            // nutritionist_review, thai_food_database, manual
            $table->string('source', 50)->nullable();

            // อ้างกลับไปยัง meal item ที่เป็นต้นทางได้
            $table->foreignId('source_meal_item_id')
                ->nullable()
                ->constrained('meal_items')
                ->nullOnDelete();

            // นักโภชนาการ/ผู้เชี่ยวชาญที่ยืนยัน
            // $table->foreignId('verified_by')
            //     ->nullable()
            //     ->constrained('users')
            //     ->nullOnDelete();

            $table->timestamp('verified_at')->nullable();

            $table->text('note')->nullable();

            $table->timestamps();

            $table->index(['food_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_nutrition');
    }
};
