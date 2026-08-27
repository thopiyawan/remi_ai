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
        // Schema::create('insulins', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });
        Schema::create('insulins', function (Blueprint $table) {
            $table->id();

            $table->string('name_th');
            $table->string('name_en')->nullable();

            // rapid, short, intermediate, long
            $table->string('insulin_type', 50)->nullable();

            $table->string('brand_name')->nullable();

            // เช่น 100 units/mL
            $table->decimal('concentration', 10, 2)->nullable();
            $table->string('concentration_unit', 30)->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insulins');
    }
};
