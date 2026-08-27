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
        Schema::create('meal_transactions', function (Blueprint $table) {
            $table->id();

            // ผู้รับประทานอาหาร
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            // breakfast, lunch, dinner, snack
            $table->string('meal_type', 30);

            $table->date('meal_date');
            $table->time('meal_time')->nullable();

            // ภาพอาหารต้นฉบับ
            $table->text('image_url')->nullable();

            /*
             * AI Status
             * pending
             * processing
             * completed
             * failed
             */
            $table->string('ai_status', 30)
                ->default('pending');

            /*
             * Review Status
             * pending
             * reviewing
             * reviewed
             * rejected
             */
            $table->string('review_status', 30)
                ->default('pending');

            // ค่ารวมปัจจุบันของทั้งมื้อ
            $table->decimal('total_calorie', 10, 2)->default(0);
            $table->decimal('total_carbohydrate', 10, 2)->default(0);
            $table->decimal('total_protein', 10, 2)->default(0);
            $table->decimal('total_fat', 10, 2)->default(0);
            $table->decimal('total_fiber', 10, 2)->default(0);
            $table->decimal('total_sugar', 10, 2)->default(0);
            $table->decimal('total_sodium', 10, 2)->default(0);

            // ผลวิเคราะห์ภาพรวมของมื้อ
            $table->string('gdm_risk', 30)->nullable();
            $table->text('recommendation')->nullable();

            // ใช้บ่อยในหน้า History / Dashboard
            $table->index(['user_id', 'meal_date']);
            $table->index('ai_status');
            $table->index('review_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_transactions');
    }
};
