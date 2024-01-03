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
        Schema::create('courses', function (Blueprint $table) {
            $table->id("course_id");
            $table->unsignedBigInteger("teacher_id");
            $table->foreign('teacher_id')->references('teacher_id')->on('teachers');
            $table->string("course_name");
            $table->string("course_duration");
            $table->string("course_desc")->nullable();
            $table->bigInteger("course_fee");
            $table->string("course_image")->nullable();
            $table->string("course_obj")->nullable();
            $table->date("start_date");
            $table->date("end_date");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
