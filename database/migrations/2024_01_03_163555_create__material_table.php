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
        Schema::create('_material', function (Blueprint $table) {
            $table->id("material_id");
            $table->unsignedBigInteger("course_id");
            $table->foreign('course_id')->references('course_id')->on('courses');
            $table->string("title");
            $table->string("content");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_material');
    }
};
