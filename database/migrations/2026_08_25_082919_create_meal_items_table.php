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
        // Schema::create('meal_items', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });

        Schema::create('meal_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('meal_transaction_id')
                ->constrained('meal_transactions')
                ->cascadeOnDelete();

            /*
             * ถ้ามี foods table อยู่แล้ว ให้ใช้ส่วนนี้
             * AI บางครั้งอาจวิเคราะห์อาหารที่ยังไม่มีใน master
             * จึงอนุญาต NULL
             */
            // $table->foreignId('food_id')
            //     ->nullable()
            //     ->constrained('foods')
            //     ->nullOnDelete();

            // ชื่ออาหาร ณ ตอนที่บันทึก
            // เก็บแยกไว้แม้มี food_id เพื่อเป็น Snapshot
            $table->string('food_name');

            // ปริมาณอาหาร
            $table->decimal('portion', 10, 2)->nullable();

            // เช่น ทัพพี, ฟอง, ช้อนโต๊ะ, จาน
            $table->string('unit', 50)->nullable();

            // ใช้เป็นหน่วยกลางในการคำนวณ
            $table->decimal('weight_g', 10, 2)->nullable();

            // Nutrition ของรายการอาหาร
            $table->decimal('calorie', 10, 2)->default(0);
            $table->decimal('carbohydrate', 10, 2)->default(0);
            $table->decimal('protein', 10, 2)->default(0);
            $table->decimal('fat', 10, 2)->default(0);
            $table->decimal('fiber', 10, 2)->default(0);
            $table->decimal('sugar', 10, 2)->default(0);
            $table->decimal('sodium', 10, 2)->default(0);

            /*
             * Confidence จาก AI เช่น 85.50
             * NULL ได้ กรณีคนเพิ่มรายการเอง
             */
            $table->decimal('confidence_score', 5, 2)->nullable();

            /*
             * ใครสร้างข้อมูลครั้งแรก
             * ai
             * patient
             * nutritionist
             * doctor
             * system
             */
            $table->string('created_source', 30);

            /*
             * แหล่งที่มาของการแก้ไขล่าสุด
             */
            $table->string('last_updated_source', 30)->nullable();

            /*
             * created_by / updated_by เป็น NULL ได้
             * เพราะ AI/System ไม่มี users.id
             */
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();

            $table->timestamps();

            /*
             * ไม่ลบข้อมูลจริง
             * เพื่อรักษาประวัติ
             */
            $table->softDeletes();

            $table->index('meal_transaction_id');
            $table->index('food_id');
            $table->index('created_source');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_items');
    }
};
