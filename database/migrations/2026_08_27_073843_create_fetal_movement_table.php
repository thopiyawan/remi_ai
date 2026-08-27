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
        // Schema::create('fetal_movement', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });

        Schema::create('fetal_movement', function (Blueprint $table) {
            $table->id();

            // users.id ของระบบคุณเป็น varchar(50)
            $table->string('user_id', 50);

            $table->integer('preg_week')->nullable();

            $table->date('date')->nullable();

            $table->integer('num_morning')->nullable();
            $table->integer('num_noon')->nullable();
            $table->integer('num_evening')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index('user_id');
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fetal_movement');
    }
};
