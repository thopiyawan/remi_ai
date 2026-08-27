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
        // Schema::create('tracker_activity', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });
        Schema::create('tracker_activity', function (Blueprint $table) {
            $table->id();

            // user_id เป็น varchar(50)
            $table->string('user_id', 50)->index();

            // food_id เป็น bigint unsigned nullable
            $table->unsignedBigInteger('food_id')->nullable()->index();

            $table->date('date')->index();

            $table->time('time')->nullable();

            $table->string('meal', 50)->nullable();

            $table->string('food_name', 255)->nullable();

            $table->decimal('portion', 6, 2)->nullable();

            $table->string('unit', 50)->nullable();

            $table->decimal('calorie', 8, 2)->nullable();

            $table->string('exercise', 255)->nullable();

            $table->string('vitamin', 255)->nullable();

            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracker_activity');
    }
};
