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
        // Schema::create('birth_date', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });
          Schema::create('birth_date', function (Blueprint $table) {
            $table->string('user_id', 255);
            $table->string('birthdate', 255);
            $table->integer('week');

            $table->timestamps();

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('birth_date');
    }
};
