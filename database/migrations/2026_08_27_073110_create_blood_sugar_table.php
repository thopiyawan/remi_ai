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
        // Schema::create('blood_sugar_', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });
         Schema::create('blood_sugar', function (Blueprint $table) {
            $table->id();

            // users.id ของระบบคุณเป็น varchar(50)
            $table->string('user_id', 50);

            // เช่น breakfast, lunch, dinner
            $table->string('meal', 50)->nullable();

            // เช่น before_meal, 1_hour_after, 2_hours_after
            $table->string('time_of_day', 50)->nullable();

            // วันและเวลาที่ตรวจน้ำตาล
            $table->dateTime('datetime');

            // ค่าน้ำตาลในเลือด
            $table->decimal('blood_sugar', 6, 2);

            // อายุครรภ์ (สัปดาห์)
            $table->integer('preg_week');

          

            // deleted_at
            $table->softDeletes();

            // Index ตามโครงสร้างเดิม
            $table->index('user_id');
            $table->index('datetime');
            $table->index('preg_week');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_sugar_');
    }
};
